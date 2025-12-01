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
        require_once __DIR__ . '/../../configs/rate_limit.php';

        $error = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST["email"] ?? "";
            # rate limiting
            if (!checkRateLimit($email)) {
                $error = "Too many login attempts. Please try again later.";
                return ["error" => $error];
            }

            $password = $_POST["password"] ?? "";

            if (empty($email) || empty($password)) {
                $error = "Please fill in all fields";
            } else {
                $user = $this->userModel->login($email, $password);
                if ($user) {
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["user_email"] = $user["email"];
                    $_SESSION["user_name"] = $user["full_name"];

                    # this regenerates session token,
                    # prevents session fixation
                    session_regenerate_id(true);

                    # log successful login
                    $this->logLoginAttempt($email, 'success');

                    header("Location: /");
                    exit();
                } else {
                    # log failed login
                    $this->logLoginAttempt($email, 'failed');
                    $error = "Invalid email or password";
                }
            }
        }

        return ["error" => $error];
    }

    public function register()
    {
        require_once __DIR__ . '/../../configs/validation.php';

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
            } elseif (!validatePassword($password)) {
                $error = "Password must be at least 8 characters and contain uppercase, lowercase, number, and special character";
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
        require_once __DIR__ . '/../../configs/validation.php';

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
            } elseif (!validatePassword($password)) {
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

    private function logLoginAttempt($email, $status)
    {
        try {
            $query = "INSERT INTO login_logs (email, status, ip_address, user_agent) 
                      VALUES (:email, :status, :ip, :user_agent)";
            $stmt = $this->userModel->getConnection()->prepare($query);
            $stmt->execute([
                ':email' => $email,
                ':status' => $status,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
        } catch (Exception $e) {
            // Silently fail - don't block login
            error_log("Login log error: " . $e->getMessage());
        }
    }
}
