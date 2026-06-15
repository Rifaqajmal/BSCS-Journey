<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $check = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) AS total FROM salary_payments WHERE teacher_id=$id"
    ));

    if ($check['total'] > 0) {
        header("Location: index.php?error=hasSalary&count={$check['total']}");
        exit();
    }

    mysqli_query($conn, "DELETE FROM teachers WHERE id=$id");
    header("Location: index.php?msg=deleted"); exit();
}

$teachers  = mysqli_query($conn,
    "SELECT * FROM teachers ORDER BY full_name"
);
$pageTitle = "Teachers";
require_once '../includes/header.php';
?>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">Done successfully.</div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error']==='hasSalary'): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Cannot delete this teacher — they have
        <strong><?= intval($_GET['count']) ?></strong>
        salary record(s). Please delete salary records first.
    </div>
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
            <?php
            $has = false;
            while ($t = mysqli_fetch_assoc($teachers)):
                $has = true;
            ?>
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
            <?php if (!$has): ?>
                <tr><td colspan="8" class="text-center text-muted py-3">
                    No teachers found.
                </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        a.style.transition = 'opacity 0.5s';
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 500);
    });
}, 4000);
</script>

<?php require_once '../includes/footer.php'; ?>