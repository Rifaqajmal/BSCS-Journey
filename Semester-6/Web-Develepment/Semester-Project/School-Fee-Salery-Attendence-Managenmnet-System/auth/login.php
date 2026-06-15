<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: /04. eaglets_school/dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        $stmt = mysqli_prepare($conn,
            "SELECT id, username, password, full_name FROM users WHERE username = ?"
        );
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user   = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']       = $user['id'];
            $_SESSION['username']      = $user['username'];
            $_SESSION['full_name']     = $user['full_name'];
            $_SESSION['last_activity'] = time();
            header("Location: /04. eaglets_school/dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login — The Eaglets Nursery School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1b4332, #2d6a4f);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 16px;
            padding: 45px 40px;
            width: 100%;
            max-width: 430px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .school-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #1b4332, #2d6a4f);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        .btn-login {
            background: linear-gradient(135deg, #1b4332, #2d6a4f);
            border: none;
            color: white;
        }
        .btn-login:hover { background: #1b4332; color: white; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <div class="school-logo">
                <i class="bi bi-mortarboard-fill text-white fs-2"></i>
            </div>
            <h5 class="fw-bold mb-0">The Eaglets Nursery School</h5>
            <div class="text-muted small">Shah Noor Pull — Management System</div>
            <hr>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2">
                <i class="bi bi-exclamation-circle me-1"></i><?= $error ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['timeout'])): ?>
            <div class="alert alert-warning py-2">
                <i class="bi bi-clock me-1"></i>
                Session expired due to inactivity. Please login again.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['logout'])): ?>
            <div class="alert alert-success py-2">
                <i class="bi bi-check-circle me-1"></i>
                You have been logged out successfully.
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" name="username" class="form-control"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                           placeholder="Enter username" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" name="password" class="form-control"
                           placeholder="Enter password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-login w-100 py-2">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </button>
        </form>

        <div class="text-center mt-3 text-muted small">
            © <?= date('Y') ?> The Eaglets Nursery School System
        </div>
    </div>
</body>
</html>