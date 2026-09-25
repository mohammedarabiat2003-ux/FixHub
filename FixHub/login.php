<?php
session_start();

require_once "config/Database.php";
require_once "classes/User.php";
require_once "classes/Auth.php";

$database = new Database();
$db = $database->connect();

$user = new User($db);
$auth = new Auth($user);

$message = "";
$messageType = "error";

if (isset($_GET["success"]) && $_GET["success"] == 1) {
    $message = "Account created successfully! Please sign in.";
    $messageType = "success";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $result = $auth->login($email, $password);

    if ($result == "Login successful") {

        if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == "provider") {
            header("Location: provider_dashboard.php");
        } else {
            header("Location: index.php");
        }

        exit();
    }

    $message = $result;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign In | FixHub</title>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --orange: #ff6b00;
            --orange-dark: #e05e00;
            --black: #121212;
            --card: #1e1e1e;
            --input: #2a2a2a;
            --border: #333;
            --white: #fff;
            --gray: #a0a0a0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--black);
            overflow: hidden;
            position: relative;
        }

        .background-circles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            overflow: hidden;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 107, 0, 0.05);
            border: 1px solid rgba(255, 107, 0, 0.1);
            animation: float 15s infinite linear;
        }

        .circle:nth-child(1) {
            width: 300px;
            height: 300px;
            left: 5%;
            top: 10%;
            animation-duration: 20s;
        }

        .circle:nth-child(2) {
            width: 200px;
            height: 200px;
            right: 10%;
            top: 50%;
            animation-duration: 15s;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0);
            }

            50% {
                transform: translateY(-40px) rotate(180deg);
            }

            100% {
                transform: translateY(0) rotate(360deg);
            }
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 40px 30px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
            position: relative;
            z-index: 2;
            animation: slideUp 0.8s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .card-header h2 {
            color: var(--white);
            font-size: 28px;
            margin-bottom: 8px;
        }

        .card-header h2 span {
            color: var(--orange);
        }

        .card-header p {
            color: var(--gray);
            font-size: 14px;
        }

        .alert-message {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-message.error {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            border-left: 4px solid #ef4444;
        }

        .alert-message.success {
            color: #22c55e;
            background: rgba(34, 197, 94, 0.1);
            border-left: 4px solid #22c55e;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        .input-group input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            background: var(--input);
            color: var(--white);
            border: 1px solid var(--border);
            border-radius: 10px;
            outline: none;
            font-size: 15px;
        }

        .input-group input::placeholder {
            color: #71717a;
        }

        .input-group input:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.15);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            color: var(--gray);
            font-size: 13px;
        }

        .form-options a {
            color: var(--orange);
            text-decoration: none;
        }

        .form-options a:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--orange);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background: var(--orange-dark);
            transform: translateY(-2px);
        }

        .form-footer {
            text-align: center;
            margin-top: 25px;
            color: var(--gray);
            font-size: 14px;
        }

        .form-footer a {
            color: var(--orange);
            text-decoration: none;
            font-weight: bold;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="background-circles">
        <div class="circle"></div>
        <div class="circle"></div>
    </div>

    <div class="login-card">

        <div class="card-header">
            <h2>Welcome Back to <span>FixHub</span></h2>
            <p>Sign in to manage your account and services</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert-message <?php echo $messageType; ?>">
                <i class="fa-solid <?php echo $messageType == "success" ? "fa-circle-check" : "fa-circle-exclamation"; ?>"></i>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">

            <div class="input-group">
                <input type="email"
                    name="email"
                    placeholder="Email Address"
                    required>
                <i class="fa-solid fa-envelope"></i>
            </div>

            <div class="input-group">
                <input type="password"
                    name="password"
                    placeholder="Password"
                    required>
                <i class="fa-solid fa-lock"></i>
            </div>

            <div class="form-options">
                <label>
                    <input type="checkbox" name="remember">
                    Remember Me
                </label>

                <a href="#">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-submit">
                Sign In
            </button>

        </form>

        <div class="form-footer">
            Don't have an account?
            <a href="register.php">Create Account</a>
        </div>

    </div>

</body>
</html>

