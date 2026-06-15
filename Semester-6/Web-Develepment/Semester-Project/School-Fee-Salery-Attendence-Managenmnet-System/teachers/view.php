<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$id      = intval($_GET['id'] ?? 0);
$teacher = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM teachers WHERE id=$id"
));
if (!$teacher) { echo "Not found."; exit(); }

// Salary summary
$summary = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(*)            AS total_months,
        SUM(salary_amount)  AS total_salary,
        SUM(paid_amount)    AS total_paid,
        SUM(remaining)      AS total_remaining
    FROM salary_payments WHERE teacher_id=$id
"));

// Recent salary records
$salaries = mysqli_query($conn, "
    SELECT * FROM salary_payments
    WHERE teacher_id=$id
    ORDER BY salary_year DESC, salary_month DESC
    LIMIT 6
");

$months = ['','Jan','Feb','Mar','Apr','May','Jun',
           'Jul','Aug','Sep','Oct','Nov','Dec'];

$pageTitle = "Teacher Profile";
require_once '../includes/header.php';
?>

<div class="row g-4">
    <!-- Profile -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div style="width:80px; height:80px; background:#e8f5e9; border-radius:50%;
                            display:flex; align-items:center; justify-content:center;
                            margin:0 auto 15px;">
                    <i class="bi bi-person-badge-fill text-success fs-2"></i>
                </div>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($teacher['full_name']) ?></h5>
                <span class="badge bg-<?= $teacher['status']==='active'?'success':'secondary' ?> mb-3">
                    <?= ucfirst($teacher['status']) ?>
                </span>
                <hr>
                <div class="text-start small">
                    <div class="mb-2">
                        <strong>Phone:</strong>
                        <?= htmlspecialchars($teacher['phone']) ?>
                    </div>
                    <div class="mb-2">
                        <strong>Email:</strong>
                        <?= htmlspecialchars($teacher['email']) ?>
                    </div>
                    <div class="mb-2">
                        <strong>CNIC:</strong>
                        <?= htmlspecialchars($teacher['cnic']) ?>
                    </div>
                    <div class="mb-2">
                        <strong>Joining:</strong>
                        <?= $teacher['joining_date'] ?>
                    </div>
                    <div class="mb-2">
                        <strong>Monthly Salary:</strong>
                        Rs. <?= number_format($teacher['monthly_salary'], 0) ?>
                    </div>
                    <div>
                        <strong>Address:</strong>
                        <?= htmlspecialchars($teacher['address']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2 mt-3">
            <a href="edit.php?id=<?= $id ?>"
               class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit Teacher
            </a>
            <a href="/04. eaglets_school/salary/index.php?teacher_id=<?= $id ?>"
               class="btn btn-success btn-sm">
                <i class="bi bi-wallet2 me-1"></i> Manage Salary
            </a>
        </div>
    </div>

    <!-- Salary Summary -->
    <div class="col-md-8">
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">Monthly Salary</div>
                    <div class="fw-bold fs-5">
                        Rs. <?= number_format($teacher['monthly_salary'], 0) ?>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">Total Paid</div>
                    <div class="fw-bold fs-5 text-success">
                        Rs. <?= number_format($summary['total_paid'] ?? 0, 0) ?>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">Total Remaining</div>
                    <div class="fw-bold fs-5 text-danger">
                        Rs. <?= number_format($summary['total_remaining'] ?? 0, 0) ?>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">Months Processed</div>
                    <div class="fw-bold fs-5">
                        <?= $summary['total_months'] ?? 0 ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Salary Records -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold d-flex justify-content-between">
                <span>Recent Salary Records</span>
                <a href="/04. eaglets_school/salary/index.php?teacher_id=<?= $id ?>"
                   class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Period</th><th>Salary</th>
                            <th>Paid</th><th>Remaining</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $has = false;
                    while ($s = mysqli_fetch_assoc($salaries)):
                        $has = true;
                        $badge = ['unpaid'=>'danger','partial'=>'warning','paid'=>'success'];
                    ?>
                        <tr>
                            <td><?= $months[$s['salary_month']] ?> <?= $s['salary_year'] ?></td>
                            <td>Rs. <?= number_format($s['salary_amount'], 0) ?></td>
                            <td class="text-success">
                                Rs. <?= number_format($s['paid_amount'], 0) ?>
                            </td>
                            <td class="text-danger">
                                Rs. <?= number_format($s['remaining'], 0) ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $badge[$s['status']] ?>">
                                    <?= ucfirst($s['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if (!$has): ?>
                        <tr><td colspan="5" class="text-center text-muted py-3">
                            No salary records yet.
                        </td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>