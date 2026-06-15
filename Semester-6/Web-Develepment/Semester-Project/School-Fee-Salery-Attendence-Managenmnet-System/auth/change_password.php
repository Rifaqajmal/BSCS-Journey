<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current  = $_POST['current_password'];
    $new      = $_POST['new_password'];
    $confirm  = $_POST['confirm_password'];

    // Get current user
    $user = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM users WHERE id={$_SESSION['user_id']}"
    ));

    if (!password_verify($current, $user['password'])) {
        $error = "Current password is incorrect.";
    } elseif (strlen($new) < 6) {
        $error = "New password must be at least 6 characters.";
    } elseif ($new !== $confirm) {
        $error = "New passwords do not match.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $stmt   = mysqli_prepare($conn,
            "UPDATE users SET password=? WHERE id=?"
        );
        mysqli_stmt_bind_param($stmt, "si", $hashed, $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        $success = "Password changed successfully!";
    }
}

$pageTitle = "Change Password";
require_once '../includes/header.php';
?>

<div class="card border-0 shadow-sm" style="max-width:450px">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-lock me-2"></i>Change Password
    </div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Current Password *</label>
                <input type="password" name="current_password"
                       class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password *</label>
                <input type="password" name="new_password"
                       class="form-control"
                       placeholder="Min 6 characters" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password *</label>
                <input type="password" name="confirm_password"
                       class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-lock me-1"></i> Change Password
            </button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>