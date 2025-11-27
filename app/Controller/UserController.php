<?php

namespace Controller;

use Model\User;
use Src\Request;
use Src\View;
use Src\Validator\Validator;

class UserController
{
    public function index(Request $request): string
    {
        $roleFilter = $request->get('role', 'all');
        $search = trim((string)$request->get('search', ''));

        $query = User::query();

        if ($roleFilter && $roleFilter !== 'all') {
            $query->where('role', $roleFilter);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('login', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->orderBy('role')->orderBy('name')->get();

        return (new View())->render('user.index', [
            'users' => $users,
            'filters' => [
                'role' => $roleFilter,
                'search' => $search,
            ],
        ]);
    }

    public function create(Request $request): string
    {
        if ($request->method === 'POST') {
            $data = $request->all();
            $safeData = $data;
            unset($safeData['password'], $safeData['csrf_token']);

            $validator = new Validator($data, [
                'name' => ['required'],
                'login' => ['required', 'unique:users,login'],
                'password' => ['required'],
                'phone' => ['required', 'phone'],
                'email' => ['required', 'unique:users,email'],
                'role' => ['required', 'enum:Администратор,Врач,Пациент'],
                'birth_date' => ['required', 'date_before:today'],
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Поле :field должно быть уникально',
            ]);

            $additionalErrors = $this->validateDomainFields($data);

            if ($validator->fails() || !empty($additionalErrors)) {
                $message = json_encode(
                    array_merge($validator->errors(), $additionalErrors),
                    JSON_UNESCAPED_UNICODE
                );

                return (new View())->render('user.create', [
                    'message' => $message,
                    'old' => $safeData,
                ]);
            }

            if (User::create($data)) {
                app()->route->redirect('/users');
            }

            return (new View())->render('user.create', [
                'message' => 'Не удалось сохранить пользователя',
                'old' => $safeData,
            ]);
        }

        return (new View())->render('user.create');
    }

    private function validateDomainFields(array $data): array
    {
        $errors = [];
        $role = $data['role'] ?? 'Пациент';
        $allowedRoles = ['Администратор', 'Врач', 'Пациент'];

        if (!in_array($role, $allowedRoles, true)) {
            $errors['role'][] = 'Выберите корректную роль';
        }

        if ($role === 'Врач' && empty($data['specialization'])) {
            $errors['specialization'][] = 'Для врача необходимо указать специализацию';
        }

        if ($role === 'Пациент' && empty($data['policy_number'])) {
            $errors['policy_number'][] = 'Для пациента необходимо указать номер полиса';
        }

        return $errors;
    }
}


