<?php

class AuthController
{
    public function showRegister(): void
    {
        if (current_user()) {
            redirect('/');
        }
        render('register', ['title' => 'Create account — KABEL', 'css' => 'register']);
    }

    public function register(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $errors = [];
        if ($name === '' || mb_strlen($name) > 100) {
            $errors[] = 'Please enter your name (max 100 characters).';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            $errors[] = 'Please enter a valid email address.';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Passwords do not match.';
        }

        $users = new User();
        if (!$errors && $users->findByEmail($email)) {
            $errors[] = 'This email is already registered.';
        }

        if ($errors) {
            foreach ($errors as $error) {
                flash('error', $error);
            }
            with_old(['name' => $name, 'email' => $email]);
            redirect('/register');
        }

        $id = $users->create($name, $email, $password);
        $this->startSession($users->find($id));
        flash('success', 'Welcome, ' . $name . '! Your account has been created.');
        redirect('/');
    }

    public function showLogin(): void
    {
        if (current_user()) {
            redirect('/');
        }
        render('login', ['title' => 'Login — KABEL', 'css' => 'login']);
    }

    public function login(): void
    {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $user = (new User())->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            flash('error', 'Invalid email or password.');
            with_old(['email' => $email]);
            redirect('/login');
        }

        $this->startSession($user);
        flash('success', 'You are logged in.');
        redirect($user['role'] === 'admin' ? '/admin' : '/');
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
        flash('success', 'You have been logged out.');
        redirect('/');
    }

    private function startSession(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'    => (int) $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ];
    }
}