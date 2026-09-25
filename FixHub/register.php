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
$name = "";
$email = "";
$role = "customer";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "customer";

    $message = $auth->register($name, $email, $password, $role);

    if ($message == "Registration successful") {
        header("Location: login.php?success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account | FixHub</title>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --orange: #ff6b00;
            --orange-dark: #e05e00;
            --black: #121212;
            --card: #1e1e1e;
            --white: #fff;
            --gray: #a0a0a0;
            --input: #2a2a2a;
            --border: #333;
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
            position: relative;
            overflow: hidden;
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
            width: 250px;
            height: 250px;
            left: 10%;
            top: 15%;
            animation-duration: 18s;
        }

        .circle:nth-child(2) {
            width: 380px;
            height: 380px;
            right: 10%;
            top: 45%;
            animation-duration: 25s;
        }

        .circle:nth-child(3) {
            width: 150px;
            height: 150px;
            left: 45%;
            top: 75%;
            animation-duration: 12s;
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

        .register-card {
            width: 100%;
            max-width: 440px;
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
            background: rgba(239, 68, 68, 0.1);
            border-left: 4px solid #ef4444;
            border-radius: 8px;
            color: #ef4444;
            font-size: 14px;
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

        .input-group input,
        .input-group select {
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

        .input-group input:focus,
        .input-group select:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: var(--orange);
            color: white;
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
        <div class="circle"></div>
    </div>

    <div class="register-card">

        <div class="card-header">
            <h2>Join <span>FixHub</span></h2>
            <p>Create your account to access home services</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert-message">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="input-group">
                <input type="text"
                    name="name"
                    value="<?php echo htmlspecialchars($name); ?>"
                    placeholder="Full Name"
                    required>
                <i class="fa-solid fa-user"></i>
            </div>

            <div class="input-group">
                <input type="email"
                    name="email"
                    value="<?php echo htmlspecialchars($email); ?>"
                    placeholder="Email Address"
                    required>
                <i class="fa-solid fa-envelope"></i>
            </div>

            <div class="input-group">
                <input type="password"
                    name="password"
                    placeholder="Password (Min. 6 chars)"
                    required>
                <i class="fa-solid fa-lock"></i>
            </div>

            <div class="input-group">
                <select name="role" required>
                    <option value="customer"
                        <?php echo $role == "customer" ? "selected" : ""; ?>>
                        Customer (I want home services)
                    </option>

                    <option value="provider"
                        <?php echo $role == "provider" ? "selected" : ""; ?>>
                        Service Provider (I offer services)
                    </option>
                </select>

                <i class="fa-solid fa-user-gear"></i>
            </div>

            <button type="submit" class="btn-submit">
                Create Account
            </button>

        </form>

        <div class="form-footer">
            Already have an account?
            <a href="login.php">Sign In</a>
        </div>

    </div>

</body>
</html>

