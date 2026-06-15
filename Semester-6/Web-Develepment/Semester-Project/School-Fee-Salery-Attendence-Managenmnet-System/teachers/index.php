<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM teachers WHERE id=$id");
    header("Location: index.php?msg=deleted"); exit();
}

$teachers  = mysqli_query($conn, "SELECT * FROM teachers ORDER BY full_name");
$pageTitle = "Teachers";
require_once '../includes/header.php';
?>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">Done successfully.</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">All Teachers</h5>
    <a href="add.php" class="btn btn-success btn-sm">
        <i class="bi bi-plus"></i> Add Teacher
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th><th>Name</th><th>Phone</th><th>CNIC</th>
                    <th>Monthly Salary</th><th>Joining Date</th>
                    <th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($t = mysqli_fetch_assoc($teachers)): ?>
                <tr>
                    <td><?= $t['id'] ?></td>
                    <td><strong><?= htmlspecialchars($t['full_name']) ?></strong></td>
                    <td><?= htmlspecialchars($t['phone']) ?></td>
                    <td><?= htmlspecialchars($t['cnic']) ?></td>
                    <td>Rs. <?= number_format($t['monthly_salary'], 0) ?></td>
                    <td><?= $t['joining_date'] ?></td>
                    <td>
                        <span class="badge bg-<?= $t['status']==='active'?'success':'secondary' ?>">
                            <?= ucfirst($t['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="view.php?id=<?= $t['id'] ?>"
                           class="btn btn-sm btn-outline-success">View</a>
                        <a href="edit.php?id=<?= $t['id'] ?>"
                           class="btn btn-sm btn-outline-primary">Edit</a>
                        <a href="index.php?delete=<?= $t['id'] ?>"
                           onclick="return confirm('Delete this teacher?')"
                           class="btn btn-sm btn-outline-danger">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>