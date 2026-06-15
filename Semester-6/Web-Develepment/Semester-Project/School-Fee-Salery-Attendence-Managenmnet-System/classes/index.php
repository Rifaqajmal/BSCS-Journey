<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

// Delete (soft)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $check = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) AS total FROM students WHERE class_id=$id"
    ));

    if ($check['total'] > 0) {
        header("Location: index.php?error=hasStudents&count={$check['total']}");
        exit();
    }

    mysqli_query($conn, "UPDATE classes SET deleted=1 WHERE id=$id");
    header("Location: index.php?msg=deleted"); exit();
}

// Restore
if (isset($_GET['restore'])) {
    $id = intval($_GET['restore']);
    mysqli_query($conn, "UPDATE classes SET deleted=0 WHERE id=$id");
    header("Location: index.php?msg=restored"); exit();
}

// Active classes
$classes = mysqli_query($conn, "
    SELECT c.*, COUNT(s.id) AS total_students
    FROM classes c
    LEFT JOIN students s ON s.class_id = c.id AND s.status='active'
    WHERE c.deleted = 0
    GROUP BY c.id
    ORDER BY
        FIELD(c.name,
        'Play Group','Nursery','Prep',
        'Class 1','Class 2','Class 3','Class 4','Class 5',
        'Class 6','Class 7','Class 8','Class 9','Class 10') = 0,
        FIELD(c.name,
        'Play Group','Nursery','Prep',
        'Class 1','Class 2','Class 3','Class 4','Class 5',
        'Class 6','Class 7','Class 8','Class 9','Class 10'),
        c.name
");

// Deleted classes
$deleted_classes = mysqli_query($conn,
    "SELECT * FROM classes WHERE deleted=1 ORDER BY name"
);
$deleted_count = mysqli_num_rows($deleted_classes);

$pageTitle = "Classes";
require_once '../includes/header.php';
?>

<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg']==='deleted'): ?>
        <div class="alert alert-warning">
            <i class="bi bi-trash me-2"></i>
            Class deleted.
            <a href="index.php?show_deleted=1" class="alert-link">View deleted classes</a>
            to restore.
        </div>
    <?php elseif ($_GET['msg']==='restored'): ?>
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>
            Class restored successfully!
        </div>
    <?php else: ?>
        <div class="alert alert-success">Done successfully.</div>
    <?php endif; ?>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error']==='hasStudents'): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Cannot delete this class — it has
        <strong><?= intval($_GET['count']) ?></strong>
        student(s) enrolled. Please move or delete them first.
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">All Classes</h5>
    <div class="d-flex gap-2">
        <?php if ($deleted_count > 0): ?>
            <a href="index.php?show_deleted=1"
               class="btn btn-outline-warning btn-sm">
                <i class="bi bi-archive me-1"></i>
                Deleted Classes (<?= $deleted_count ?>)
            </a>
        <?php endif; ?>
        <a href="add.php" class="btn btn-success btn-sm">
            <i class="bi bi-plus"></i> Add Class
        </a>
    </div>
</div>

<!-- Active Classes -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th><th>Class Name</th>
                    <th>Monthly Fee</th><th>Students</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $has = false;
            while ($c = mysqli_fetch_assoc($classes)):
                $has = true;
            ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
                    <td>Rs. <?= number_format($c['monthly_fee'], 0) ?></td>
                    <td>
                        <span class="badge bg-success">
                            <?= $c['total_students'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $c['id'] ?>"
                           class="btn btn-sm btn-outline-primary">Edit</a>
                        <a href="index.php?delete=<?= $c['id'] ?>"
                           onclick="return confirm('Delete class: <?= htmlspecialchars($c['name']) ?>? You can restore it later.')"
                           class="btn btn-sm btn-outline-danger">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (!$has): ?>
                <tr><td colspan="5" class="text-center text-muted py-3">
                    No classes found.
                </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Deleted Classes (Restore Section) -->
<?php if (isset($_GET['show_deleted']) && $deleted_count > 0): ?>
<h6 class="fw-bold text-warning mb-2">
    <i class="bi bi-archive me-1"></i> Deleted Classes
</h6>
<div class="card border-warning shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-warning">
                <tr>
                    <th>Class Name</th><th>Monthly Fee</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            mysqli_data_seek($deleted_classes, 0);
            while ($d = mysqli_fetch_assoc($deleted_classes)):
            ?>
                <tr>
                    <td><strong><?= htmlspecialchars($d['name']) ?></strong></td>
                    <td>Rs. <?= number_format($d['monthly_fee'], 0) ?></td>
                    <td>
                        <a href="index.php?restore=<?= $d['id'] ?>"
                           class="btn btn-sm btn-success">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Restore
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

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