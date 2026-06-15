<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$id      = intval($_GET['id'] ?? 0);
$teacher = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM teachers WHERE id=$id"));
if (!$teacher) { echo "Not found."; exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name      = trim($_POST['full_name']);
    $phone          = trim($_POST['phone']);
    $email          = trim($_POST['email']);
    $cnic           = trim($_POST['cnic']);
    $address        = trim($_POST['address']);
    $joining_date   = $_POST['joining_date'];
    $monthly_salary = floatval($_POST['monthly_salary']);
    $status         = $_POST['status'];

    $stmt = mysqli_prepare($conn,
        "UPDATE teachers SET full_name=?, phone=?, email=?, cnic=?,
         address=?, joining_date=?, monthly_salary=?, status=?
         WHERE id=?"
    );
    mysqli_stmt_bind_param($stmt, "ssssssdsi",
        $full_name, $phone, $email, $cnic,
        $address, $joining_date, $monthly_salary, $status, $id
    );
    mysqli_stmt_execute($stmt);
    header("Location: index.php?msg=updated"); exit();
}

$pageTitle = "Edit Teacher";
require_once '../includes/header.php';
?>

<div class="card border-0 shadow-sm" style="max-width:600px">
    <div class="card-header bg-white fw-bold">Edit Teacher</div>
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="full_name" class="form-control"
                           value="<?= htmlspecialchars($teacher['full_name']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control"
                           value="<?= htmlspecialchars($teacher['phone']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($teacher['email']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">CNIC</label>
                    <input type="text" name="cnic" class="form-control"
                           value="<?= htmlspecialchars($teacher['cnic']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Monthly Salary (Rs.) *</label>
                    <input type="number" name="monthly_salary" class="form-control"
                           value="<?= $teacher['monthly_salary'] ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Joining Date</label>
                    <input type="date" name="joining_date" class="form-control"
                           value="<?= $teacher['joining_date'] ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control"
                           value="<?= htmlspecialchars($teacher['address']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active"
                            <?= $teacher['status']==='active'?'selected':'' ?>>Active</option>
                        <option value="inactive"
                            <?= $teacher['status']==='inactive'?'selected':'' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Update Teacher</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>