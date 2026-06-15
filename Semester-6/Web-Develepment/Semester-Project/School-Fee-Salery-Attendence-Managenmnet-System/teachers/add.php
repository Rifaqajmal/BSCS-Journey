<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name      = trim($_POST['full_name']);
    $phone          = trim($_POST['phone']);
    $email          = trim($_POST['email']);
    $cnic           = trim($_POST['cnic']);
    $address        = trim($_POST['address']);
    $joining_date   = $_POST['joining_date'];
    $monthly_salary = floatval($_POST['monthly_salary']);

    if (empty($full_name) || $monthly_salary <= 0) {
        $error = "Name and salary are required.";
    } else {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO teachers
             (full_name, phone, email, cnic, address, joining_date, monthly_salary)
             VALUES (?,?,?,?,?,?,?)"
        );
        mysqli_stmt_bind_param($stmt, "ssssssd",
            $full_name, $phone, $email, $cnic,
            $address, $joining_date, $monthly_salary
        );
        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?msg=added"); exit();
        } else {
            $error = "Failed to add teacher.";
        }
    }
}

$pageTitle = "Add Teacher";
require_once '../includes/header.php';
?>

<div class="card border-0 shadow-sm" style="max-width:600px">
    <div class="card-header bg-white fw-bold">Add New Teacher</div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="full_name" class="form-control"
                           value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control"
                           value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">CNIC</label>
                    <input type="text" name="cnic" class="form-control"
                           placeholder="xxxxx-xxxxxxx-x"
                           value="<?= htmlspecialchars($_POST['cnic'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Monthly Salary (Rs.) *</label>
                    <input type="number" name="monthly_salary" class="form-control"
                           placeholder="15000"
                           value="<?= $_POST['monthly_salary'] ?? '' ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Joining Date</label>
                    <input type="date" name="joining_date" class="form-control"
                           value="<?= $_POST['joining_date'] ?? date('Y-m-d') ?>">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control"
                           value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-success">Save Teacher</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>