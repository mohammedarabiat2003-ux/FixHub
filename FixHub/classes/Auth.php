<?php
class Auth
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function register($name, $email, $password, $role = 'customer')
    {
        if (empty($name) || empty($email) || empty($password)) {
            return "Please fill all fields";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Email is not valid";
        }

        if (strlen($password) < 6) {
            return "Password must be at least 6 characters";
        }

        if ($this->user->emailExists($email)) {
            return "Email already exists";
        }

        $this->user->register($name, $email, $password, $role);

        return "Registration successful";
    }

    public function login($email, $password)
    {
        if (empty($email) || empty($password)) {
            return "Please fill in all fields.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }

        $userData = $this->user->getUserByEmail($email);

        if (!$userData || !password_verify($password, $userData['password'])) {
            return "Invalid email or password.";
        }

        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_name'] = $userData['name'];
        $_SESSION['user_email'] = $userData['email'];
        $_SESSION['user_role'] = $userData['role'];

        return "Login successful";
    }
}
