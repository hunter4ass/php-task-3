<?php

namespace Controller;

use Model\Appointment;
use Model\User;
use Src\View;
use Src\Request;
use Src\Auth\Auth;
use Src\Validator\Validator;

class AppointmentController
{
   private array $statuses = ['Запланировано', 'Подтверждено', 'Завершено', 'Отменено'];
   private array $services = ['Первичная консультация', 'Повторный прием', 'Диагностика', 'Анализы и исследования'];

   // Главная страница записей - показывает разный контент в зависимости от роли
   public function index(Request $request): string
   {
       if (!Auth::check()) {
           app()->route->redirect('/login');
       }

       $user = Auth::user();
       $filters = [
           'status' => $request->get('status', 'all'),
           'date_from' => $request->get('date_from'),
           'date_to' => $request->get('date_to'),
           'search' => trim((string)$request->get('search', '')),
       ];

       $query = Appointment::query();
       $doctors = [];
       $patients = [];

       if ($user->role === 'Пациент') {
           $query->where('patient_id', $user->id)->with('doctor');
       } elseif ($user->role === 'Врач') {
           $query->where('doctor_id', $user->id)->with('patient');
       } else {
           $query->with(['patient', 'doctor']);
           $doctors = User::where('role', 'Врач')->get();
           $patients = User::where('role', 'Пациент')->get();
       }

       if (!empty($filters['status']) && $filters['status'] !== 'all') {
           $query->where('status', $filters['status']);
       }

       if (!empty($filters['date_from'])) {
           $query->where('appointment_date', '>=', $filters['date_from']);
       }

       if (!empty($filters['date_to'])) {
           $query->where('appointment_date', '<=', $filters['date_to']);
       }

       if (!empty($filters['search'])) {
           $search = '%' . $filters['search'] . '%';
           $query->where(function ($q) use ($search, $user) {
               $q->where('service_type', 'like', $search)
                 ->orWhere('notes', 'like', $search)
                 ->orWhere('complaint', 'like', $search)
                 ->orWhere('room', 'like', $search);

               if ($user->role !== 'Пациент') {
                   $q->orWhereHas('patient', function ($sub) use ($search) {
                       $sub->where('name', 'like', $search);
                   });
               }

               if ($user->role !== 'Врач') {
                   $q->orWhereHas('doctor', function ($sub) use ($search) {
                       $sub->where('name', 'like', $search);
                   });
               }
           });
       }

       $appointments = $query->orderBy('appointment_date', 'desc')
           ->orderBy('appointment_time', 'desc')
           ->get();

       return (new View())->render('site.appointments', [
           'appointments' => $appointments,
           'doctors' => $doctors,
           'patients' => $patients,
           'user' => $user,
           'filters' => $filters,
           'statuses' => $this->statuses,
           'services' => $this->services,
       ]);
   }

   // Создание записи (для пациентов)
   public function create(Request $request): string
   {
       if (!Auth::check()) {
           app()->route->redirect('/login');
       }

       $user = Auth::user();
       
       if ($user->role !== 'Пациент' && $user->role !== 'Администратор') {
           app()->route->redirect('/appointments');
       }

       if ($request->method === 'GET') {
           $doctors = User::where('role', 'Врач')->get();
           $patients = [];
           if ($user->role === 'Администратор') {
               $patients = User::where('role', 'Пациент')->get();
           }
           return (new View())->render('site.appointment_create', [
               'doctors' => $doctors,
               'patients' => $patients,
               'services' => $this->services,
           ]);
       }

       $oldInput = $request->all();
       unset($oldInput['csrf_token']);

       $rules = [
           'doctor_id' => ['required'],
           'appointment_date' => ['required', 'date_after:today'],
           'appointment_time' => ['required'],
           'service_type' => array_merge(['required'], $this->enumServiceRule()),
           'attachment' => ['image:jpg,png,jpeg,2048'],
       ];

       if ($user->role === 'Администратор') {
           $rules['patient_id'] = ['required'];
       }

       $validator = new Validator($request->all(), $rules, [
           'required' => 'Поле :field пусто',
       ]);

       if ($validator->fails()) {
           $doctors = User::where('role', 'Врач')->get();
           $patients = [];
           if ($user->role === 'Администратор') {
               $patients = User::where('role', 'Пациент')->get();
           }
           return (new View())->render('site.appointment_create', [
               'doctors' => $doctors,
               'patients' => $patients,
               'message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE),
               'old' => $oldInput,
               'services' => $this->services,
           ]);
       }

       $data = [
           'doctor_id' => $request->get('doctor_id'),
           'patient_id' => $user->role === 'Администратор'
               ? $request->get('patient_id')
               : $user->id,
           'appointment_date' => $request->get('appointment_date'),
           'appointment_time' => $request->get('appointment_time'),
           'service_type' => $request->get('service_type'),
           'complaint' => $request->get('complaint'),
           'notes' => $request->get('notes'),
           'room' => $request->get('room'),
           'status' => 'Запланировано',
       ];

       $attachmentPath = $this->storeAttachment($request->file('attachment'));
       if ($attachmentPath) {
           $data['attachment_path'] = $attachmentPath;
       }

       if (Appointment::create($data)) {
           app()->route->redirect('/appointments');
       }

       $doctors = User::where('role', 'Врач')->get();
       $patients = [];
       if ($user->role === 'Администратор') {
           $patients = User::where('role', 'Пациент')->get();
       }
       return (new View())->render('site.appointment_create', [
           'doctors' => $doctors,
           'patients' => $patients,
           'message' => 'Ошибка при создании записи',
           'old' => $oldInput,
           'services' => $this->services,
       ]);
   }

   // Управление записями (для врачей и админов)
   public function manage(Request $request): string
   {
       if (!Auth::check()) {
           app()->route->redirect('/login');
       }

       $user = Auth::user();
       
       if ($user->role !== 'Врач' && $user->role !== 'Администратор') {
           app()->route->redirect('/appointments');
       }

       if ($request->method === 'POST') {
           $payload = array_merge($request->all(), ['attachment' => $request->file('attachment')]);
           $rules = [
               'appointment_id' => ['required'],
               'appointment_date' => ['required'],
               'appointment_time' => ['required'],
               'status' => array_merge(['required'], $this->enumStatusRule()),
               'attachment' => ['image:jpg,png,jpeg,2048'],
           ];

           if ($user->role === 'Администратор') {
               $rules['service_type'] = $this->enumServiceRule();
           }

           $validator = new Validator($payload, $rules, [
               'required' => 'Поле :field пусто',
           ]);

           if (!$validator->fails()) {
               $appointment = Appointment::find($request->appointment_id);
               
               if ($appointment && ($user->role === 'Администратор' || $appointment->doctor_id === $user->id)) {
                   $appointment->appointment_date = $request->appointment_date;
                   $appointment->appointment_time = $request->appointment_time;
                   $appointment->status = $request->get('status', $appointment->status);
                   $appointment->notes = $request->get('notes', $appointment->notes);
                   $appointment->room = $request->get('room', $appointment->room);
                   if ($user->role === 'Администратор' && $request->get('service_type')) {
                       $appointment->service_type = $request->get('service_type');
                   }
                   $attachmentPath = $this->storeAttachment($request->file('attachment'));
                   if ($attachmentPath) {
                       $appointment->attachment_path = $attachmentPath;
                   }
                   $appointment->save();
                   app()->route->redirect('/appointments');
               }
           }
       }

       $appointmentId = $request->get('id') ?? 0;
       $appointment = Appointment::with(['patient', 'doctor'])->find($appointmentId);
       
       if (!$appointment || ($user->role === 'Врач' && $appointment->doctor_id !== $user->id)) {
           app()->route->redirect('/appointments');
       }

       return (new View())->render('site.appointment_manage', [
           'appointment' => $appointment,
           'user' => $user,
           'statuses' => $this->statuses,
           'services' => $this->services,
       ]);
   }

   // Удаление записи (для админов)
   public function delete(Request $request): void
   {
       if (!Auth::check()) {
           app()->route->redirect('/login');
       }

       $user = Auth::user();
       
       if ($user->role !== 'Администратор') {
           app()->route->redirect('/appointments');
       }

       $appointmentId = $request->get('id') ?? 0;
       $appointment = Appointment::find($appointmentId);
       
       if ($appointment) {
           $appointment->delete();
       }

       app()->route->redirect('/appointments');
   }

   private function enumServiceRule(): array
   {
       return ['enum:' . implode(',', $this->services)];
   }

   private function enumStatusRule(): array
   {
       return ['enum:' . implode(',', $this->statuses)];
   }

   private function storeAttachment($file): ?string
   {
       if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
           return null;
       }

       if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
           return null;
       }

       $uploadDir = dirname(__DIR__, 2) . '/public/uploads/appointments';
       if (!is_dir($uploadDir)) {
           mkdir($uploadDir, 0777, true);
       }

       $extension = strtolower(pathinfo($file['name'] ?? 'file', PATHINFO_EXTENSION));
       $filename = uniqid('attach_', true) . '.' . $extension;
       $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;

       if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
           return null;
       }

       return 'uploads/appointments/' . $filename;
   }
}

