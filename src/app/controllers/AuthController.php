<?php
require_once __DIR__ . "/../models/User.php";

class AuthController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new User($db);
    }

    public function login()
    {
        $error = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST["email"] ?? "";
            $password = $_POST["password"] ?? "";

            if (empty($email) || empty($password)) {
                $error = "Please fill in all fields";
            } else {
                $user = $this->userModel->login($email, $password);
                if ($user) {
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["user_email"] = $user["email"];
                    $_SESSION["user_name"] = $user["full_name"];
                    header("Location: /");
                    exit();
                } else {
                    $error = "Invalid email or password";
                }
            }
        }

        return ["error" => $error];
    }

    public function register()
    {
        $error = "";
        $success = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST["email"] ?? "";
            $password = $_POST["password"] ?? "";
            $confirmPassword = $_POST["confirm_password"] ?? "";
            $fullName = $_POST["full_name"] ?? "";

            if (empty($email) || empty($password) || empty($fullName)) {
                $error = "Please fill in all fields";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format";
            } elseif (strlen($password) < 6) {
                $error = "Password must be at least 6 characters";
            } elseif ($password !== $confirmPassword) {
                $error = "Passwords do not match";
            } elseif ($this->userModel->emailExists($email)) {
                $error = "Email already exists";
            } else {
                if ($this->userModel->register($email, $password, $fullName)) {
                    $success = "Registration successful! Please login.";
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }

        return ["error" => $error, "success" => $success];
    }

    public function forgotPassword()
    {
        $error = "";
        $success = "";
        $resetLink = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST["email"] ?? "";

            if (empty($email)) {
                $error = "Please enter your email";
            } elseif (!$this->userModel->emailExists($email)) {
                $error = "Email not found";
            } else {
                $token = $this->userModel->createResetToken($email);
                if ($token) {
                    $resetLink = "http://{$_SERVER["HTTP_HOST"]}/reset-password?token={$token}";
                    $success = "Password reset link generated successfully!";
                } else {
                    $error = "Failed to generate reset token";
                }
            }
        }

        return [
            "error" => $error,
            "success" => $success,
            "resetLink" => $resetLink,
        ];
    }

    public function resetPassword($token)
    {
        $error = "";
        $success = "";

        if (empty($token)) {
            header("Location: /forgot-password");
            exit();
        }

        $user = $this->userModel->verifyResetToken($token);
        if (!$user) {
            $error = "Invalid or expired reset token";
        } elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
            $password = $_POST["password"] ?? "";
            $confirmPassword = $_POST["confirm_password"] ?? "";

            if (empty($password)) {
                $error = "Please enter a new password";
            } elseif (strlen($password) < 6) {
                $error = "Password must be at least 6 characters";
            } elseif ($password !== $confirmPassword) {
                $error = "Passwords do not match";
            } else {
                if ($this->userModel->resetPassword($token, $password)) {
                    $success =
                        'Password reset successful! <a href="/login" style="color: #ffffff; text-decoration: underline;">Login here</a>';
                } else {
                    $error = "Failed to reset password";
                }
            }
        }

        return ["error" => $error, "success" => $success, "user" => $user];
    }
}
