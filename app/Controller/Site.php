<?php

namespace Controller;

use Model\User;
use Src\Auth\Auth;
use Src\Request;
use Src\Validator\Validator;
use Src\View;

class Site
{
    public function index(Request $request): string
    {
        return $this->hello($request);
    }

    public function hello(Request $request): string
    {
        $highlights = [
            [
                'title' => 'Онлайн-расписание',
                'description' => 'Пациенты и врачи видят актуальные записи в один клик.',
            ],
            [
                'title' => 'Контроль статусов',
                'description' => 'Администратор управляет подтверждением и завершением визитов.',
            ],
            [
                'title' => 'Безопасный доступ',
                'description' => 'Данные разделяются по ролям и защищены CSRF middleware.',
            ],
        ];

        return (new View())->render('site.hello', [
            'message' => 'Информационная система клиники',
            'highlights' => $highlights,
        ]);
    }

    public function login(Request $request): string
    {
        if (Auth::check()) {
            app()->route->redirect('/');
        }

        if ($request->method === 'GET') {
            return (new View())->render('site.login');
        }

        $credentials = [
            'login' => $request->get('login'),
            'password' => $request->get('password'),
        ];

        if (Auth::attempt($credentials)) {
            app()->route->redirect('/');
        }

        return (new View())->render('site.login', [
            'message' => 'Неправильные логин или пароль',
            'old' => ['login' => $request->get('login')],
        ]);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/hello');
    }

    public function signup(Request $request): string
    {
        if ($request->method === 'POST') {
            $data = $request->all();
            $data['role'] = 'Пациент';
            $safeData = $data;
            unset($safeData['password'], $safeData['csrf_token']);

            $validator = new Validator($data, [
                'name' => ['required'],
                'login' => ['required', 'unique:users,login'],
                'password' => ['required'],
                'phone' => ['required', 'phone'],
                'email' => ['required', 'unique:users,email'],
                'policy_number' => ['required'],
                'birth_date' => ['required', 'date_before:today'],
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Поле :field должно быть уникально',
            ]);

            if ($validator->fails()) {
                return (new View())->render('site.signup', [
                    'message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE),
                    'old' => $safeData,
                ]);
            }

            $data['specialization'] = null;

            if (User::create($data)) {
                app()->route->redirect('/login');
            }

            return (new View())->render('site.signup', [
                'message' => 'Не удалось создать учетную запись',
                'old' => $safeData,
            ]);
        }

        return (new View())->render('site.signup');
    }
}
