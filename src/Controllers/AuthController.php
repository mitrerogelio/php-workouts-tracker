<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Redirect;
use App\Http\Request;
use App\Http\Session;
use App\Http\View;
use App\Models\Gender;
use App\Services\AuthService;

class AuthController
{
    public function __construct(private AuthService $auth) {}

    public function showRegister(): void
    {
        View::render('register', ['error' => null]);
    }

    public function register(): void
    {
        $firstName = Request::postString('first_name');
        $lastName = Request::postString('last_name');
        $username = Request::postString('username');
        $email = Request::postString('email');
        $password = Request::postString('password');
        $gender = Gender::tryFrom(Request::postString('gender'));

        if ($firstName === '' || $lastName === '' || $username === '' || $email === '' || $password === '') {
            View::render('register', ['error' => 'All fields except gender are required.']);
            return;
        }

        try {
            $profile = $this->auth->register($firstName, $lastName, $username, $gender, $email, $password);
        } catch (\RuntimeException $e) {
            View::render('register', ['error' => $e->getMessage()]);
            return;
        }

        Session::login($profile->id, $profile->username);
        Redirect::to('/dashboard');
    }

    public function showLogin(): void
    {
        View::render('login', ['error' => null]);
    }

    public function login(): void
    {
        $email = Request::postString('email');
        $password = Request::postString('password');

        $profile = $this->auth->login($email, $password);
        if ($profile === null) {
            View::render('login', ['error' => 'Invalid email or password.']);
            return;
        }

        Session::login($profile->id, $profile->username);
        Redirect::to('/dashboard');
    }

    public function logout(): void
    {
        Session::clear();
        Redirect::to('/login');
    }
}
