<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$id    = intval($_GET['id'] ?? 0);
$class = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM classes WHERE id=$id"));
if (!$class) { echo "Not found."; exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $fee  = floatval($_POST['monthly_fee']);
    $stmt = mysqli_prepare($conn, "UPDATE classes SET name=?, monthly_fee=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sdi", $name, $fee, $id);
    mysqli_stmt_execute($stmt);
    header("Location: index.php?msg=updated"); exit();
}

$pageTitle = "Edit Class";
require_once '../includes/header.php';
?>
<div class="card border-0 shadow-sm" style="max-width:450px">
    <div class="card-header bg-white fw-bold">Edit Class</div>
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Class Name *</label>
                <input type="text" name="name" class="form-control"
                       value="<?= htmlspecialchars($class['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Monthly Fee (Rs.) *</label>
                <input type="number" name="monthly_fee" class="form-control"
                       value="<?= $class['monthly_fee'] ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>