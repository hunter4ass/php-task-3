<?php

namespace Controller;

use Model\Appointment;
use Model\User;
use Src\Auth\Auth;
use Src\Request;
use Src\View;

class DashboardController
{
    public function index(Request $request): string
    {
        if (!Auth::check()) {
            app()->route->redirect('/login');
        }

        $user = Auth::user();

        $appointments = $this->getAppointmentsForUser($user->role);

        $stats = [
            'totalDoctors' => User::where('role', 'Врач')->count(),
            'totalPatients' => User::where('role', 'Пациент')->count(),
            'upcomingAppointments' => Appointment::where('appointment_date', '>=', date('Y-m-d'))->count(),
        ];

        return (new View())->render('site.dashboard', [
            'user' => $user,
            'appointments' => $appointments,
            'stats' => $stats,
        ]);
    }

    private function getAppointmentsForUser(string $role)
    {
        $baseQuery = Appointment::query()
            ->with(['patient', 'doctor'])
            ->where('appointment_date', '>=', date('Y-m-d'))
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5);

        $user = Auth::user();

        if ($role === 'Пациент') {
            $baseQuery->where('patient_id', $user->id);
        } elseif ($role === 'Врач') {
            $baseQuery->where('doctor_id', $user->id);
        }

        return $baseQuery->get();
    }
}


