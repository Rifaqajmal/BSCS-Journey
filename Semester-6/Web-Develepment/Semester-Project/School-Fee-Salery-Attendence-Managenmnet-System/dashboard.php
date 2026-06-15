<?php
$pageTitle = "Dashboard";
require_once 'includes/header.php';

$total_students  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM students WHERE status='active'"))['c'];
$total_classes   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM classes WHERE deleted=0"))['c'];
$total_due       = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount - paid_amount) AS c FROM fee_vouchers WHERE status != 'paid'"))['c'];
$month_collected = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount_paid) AS c FROM fee_payments WHERE MONTH(payment_date)=MONTH(NOW()) AND YEAR(payment_date)=YEAR(NOW())"))['c'];
$unpaid_count    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM fee_vouchers WHERE status='unpaid'"))['c'];
$total_teachers  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM teachers WHERE status='active'"))['c'];
$salary_due      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(remaining) AS c FROM salary_payments WHERE status != 'paid'"))['c'];
?>

<!-- Row 1 -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e8f5e9; padding:15px; border-radius:12px;">
                    <i class="bi bi-people-fill text-success fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Students</div>
                    <div class="fs-3 fw-bold"><?= $total_students ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e3f2fd; padding:15px; border-radius:12px;">
                    <i class="bi bi-journal-bookmark-fill text-primary fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Classes</div>
                    <div class="fs-3 fw-bold"><?= $total_classes ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e8f5e9; padding:15px; border-radius:12px;">
                    <i class="bi bi-person-badge-fill text-success fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Active Teachers</div>
                    <div class="fs-3 fw-bold"><?= $total_teachers ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2 -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e8f5e9; padding:15px; border-radius:12px;">
                    <i class="bi bi-cash-coin text-success fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">This Month Collected</div>
                    <div class="fs-3 fw-bold">
                        Rs. <?= number_format($month_collected ?? 0, 0) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#fce4ec; padding:15px; border-radius:12px;">
                    <i class="bi bi-exclamation-circle-fill text-danger fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Fee Outstanding</div>
                    <div class="fs-3 fw-bold text-danger">
                        Rs. <?= number_format($total_due ?? 0, 0) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#fce4ec; padding:15px; border-radius:12px;">
                    <i class="bi bi-receipt text-danger fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Unpaid Vouchers</div>
                    <div class="fs-3 fw-bold text-danger"><?= $unpaid_count ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 3 -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#fce4ec; padding:15px; border-radius:12px;">
                    <i class="bi bi-wallet2 text-danger fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Salary Due</div>
                    <div class="fs-3 fw-bold text-danger">
                        Rs. <?= number_format($salary_due ?? 0, 0) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e3f2fd; padding:15px; border-radius:12px;">
                    <i class="bi bi-people text-primary fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Staff & Students</div>
                    <div class="fs-3 fw-bold">
                        <?= $total_teachers + $total_students ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#e8f5e9; padding:15px; border-radius:12px;">
                    <i class="bi bi-calendar-check text-success fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Today</div>
                    <div class="fs-5 fw-bold"><?= date('d M Y') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold">Quick Actions</div>
    <div class="card-body d-flex flex-wrap gap-2">
        <a href="/04. eaglets_school/students/add.php"
           class="btn btn-sm btn-success">
            <i class="bi bi-plus"></i> Add Student
        </a>
        <a href="/04. eaglets_school/fees/bulk_generate.php"
           class="btn btn-sm btn-warning">
            <i class="bi bi-lightning-charge"></i> Bulk Generate Fee
        </a>
        <a href="/04. eaglets_school/fees/generate.php"
           class="btn btn-sm btn-primary">
            <i class="bi bi-receipt"></i> Generate Fee Voucher
        </a>
        <a href="/04. eaglets_school/fees/collect.php"
           class="btn btn-sm btn-info">
            <i class="bi bi-cash"></i> Collect Fee
        </a>
        <a href="/04. eaglets_school/attendance/index.php"
           class="btn btn-sm btn-secondary">
            <i class="bi bi-calendar-check"></i> Mark Attendance
        </a>
        <a href="/04. eaglets_school/teachers/add.php"
           class="btn btn-sm btn-dark">
            <i class="bi bi-person-badge"></i> Add Teacher
        </a>
        <a href="/04. eaglets_school/salary/pay.php"
           class="btn btn-sm btn-danger">
            <i class="bi bi-wallet2"></i> Pay Salary
        </a>
        <a href="/04. eaglets_school/reports/index.php"
           class="btn btn-sm btn-outline-dark">
            <i class="bi bi-bar-chart"></i> View Reports
        </a>
    </div>
</div>

<!-- Recent Students + Teachers -->
<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold d-flex justify-content-between">
                <span>Recently Added Students</span>
                <a href="/04. eaglets_school/students/index.php"
                   class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Roll #</th><th>Name</th>
                            <th>Class</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $recent = mysqli_query($conn, "
                        SELECT s.*, c.name AS class_name
                        FROM students s
                        JOIN classes c ON s.class_id = c.id
                        ORDER BY s.created_at DESC LIMIT 5
                    ");
                    $has = false;
                    while ($r = mysqli_fetch_assoc($recent)):
                        $has = true;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($r['roll_no']) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($r['full_name']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($r['class_name']) ?></td>
                            <td>
                                <span class="badge bg-<?= $r['status']==='active'?'success':'secondary' ?>">
                                    <?= ucfirst($r['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if (!$has): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">
                            No students added yet.
                        </td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold d-flex justify-content-between">
                <span>Teachers</span>
                <a href="/04. eaglets_school/teachers/index.php"
                   class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Name</th><th>Salary</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    $recent_teachers = mysqli_query($conn,
                        "SELECT * FROM teachers ORDER BY created_at DESC LIMIT 5"
                    );
                    $has_t = false;
                    while ($t = mysqli_fetch_assoc($recent_teachers)):
                        $has_t = true;
                    ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($t['full_name']) ?></strong>
                            </td>
                            <td>Rs. <?= number_format($t['monthly_salary'], 0) ?></td>
                            <td>
                                <span class="badge bg-<?= $t['status']==='active'?'success':'secondary' ?>">
                                    <?= ucfirst($t['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if (!$has_t): ?>
                        <tr><td colspan="3" class="text-center text-muted py-3">
                            No teachers yet.
                        </td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        a.style.transition = 'opacity 0.5s';
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 500);
    });
}, 3000);
</script>

<?php require_once 'includes/footer.php'; ?>