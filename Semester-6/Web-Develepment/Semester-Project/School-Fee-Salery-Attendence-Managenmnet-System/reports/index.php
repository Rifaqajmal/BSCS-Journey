<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$months_list = ['1'=>'January','2'=>'February','3'=>'March','4'=>'April',
                '5'=>'May','6'=>'June','7'=>'July','8'=>'August',
                '9'=>'September','10'=>'October','11'=>'November','12'=>'December'];

$sel_month = intval($_GET['month'] ?? date('n'));
$sel_year  = intval($_GET['year']  ?? date('Y'));

// Monthly fee summary
$monthly = mysqli_query($conn, "
    SELECT s.full_name, s.roll_no, c.name AS class_name,
           v.fee_amount, v.previous_dues, v.total_amount,
           v.paid_amount, (v.total_amount-v.paid_amount) AS balance,
           v.status
    FROM fee_vouchers v
    JOIN students s ON v.student_id = s.id
    JOIN classes c  ON s.class_id   = c.id
    WHERE v.fee_month=$sel_month AND v.fee_year=$sel_year
    ORDER BY c.id, s.full_name
");

$totals = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        SUM(total_amount)               AS total_billed,
        SUM(paid_amount)                AS total_collected,
        SUM(total_amount - paid_amount) AS total_outstanding,
        COUNT(*)                        AS total_vouchers,
        SUM(status='paid')              AS paid_count,
        SUM(status='partial')           AS partial_count,
        SUM(status='unpaid')            AS unpaid_count
    FROM fee_vouchers
    WHERE fee_month=$sel_month AND fee_year=$sel_year
"));

// Overall outstanding
$outstanding = mysqli_query($conn, "
    SELECT s.full_name, s.roll_no, c.name AS class_name,
           SUM(v.total_amount - v.paid_amount) AS total_due,
           COUNT(v.id) AS unpaid_count
    FROM fee_vouchers v
    JOIN students s ON v.student_id = s.id
    JOIN classes c  ON s.class_id   = c.id
    WHERE v.status != 'paid'
    GROUP BY s.id
    ORDER BY total_due DESC
");

$grand_due = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT SUM(total_amount-paid_amount) AS total
     FROM fee_vouchers WHERE status != 'paid'"
));

$pageTitle = "Reports";
require_once '../includes/header.php';
?>

<style>
@media print {
    .sidebar, .topbar, .d-print-none { display:none !important; }
    body { background:white !important; }
}
</style>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-4 d-print-none">
    <div class="card-body">
        <form method="GET" class="d-flex gap-3 align-items-end flex-wrap">
            <div>
                <label class="form-label fw-bold">Month</label>
                <select name="month" class="form-select">
                    <?php foreach ($months_list as $n => $name): ?>
                        <option value="<?= $n ?>" <?= $n==$sel_month?'selected':'' ?>>
                            <?= $name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label fw-bold">Year</label>
                <select name="year" class="form-select">
                    <?php for ($y=date('Y'); $y>=date('Y')-2; $y--): ?>
                        <option value="<?= $y ?>" <?= $y==$sel_year?'selected':'' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-filter me-1"></i> Filter
            </button>
            <button type="button" onclick="window.print()"
                    class="btn btn-outline-secondary">
                <i class="bi bi-printer me-1"></i> Print
            </button>
        </form>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-muted small">Total Billed</div>
            <div class="fw-bold fs-5">
                Rs. <?= number_format($totals['total_billed'] ?? 0, 0) ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-muted small">Collected</div>
            <div class="fw-bold fs-5 text-success">
                Rs. <?= number_format($totals['total_collected'] ?? 0, 0) ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-muted small">Outstanding</div>
            <div class="fw-bold fs-5 text-danger">
                Rs. <?= number_format($totals['total_outstanding'] ?? 0, 0) ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-muted small">Paid / Partial / Unpaid</div>
            <div class="fw-bold">
                <span class="text-success"><?= $totals['paid_count'] ?? 0 ?></span> /
                <span class="text-warning"><?= $totals['partial_count'] ?? 0 ?></span> /
                <span class="text-danger"><?= $totals['unpaid_count'] ?? 0 ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Collection Table -->
<h5 class="fw-bold mb-3">
    <i class="bi bi-bar-chart me-2"></i>
    Fee Collection — <?= $months_list[$sel_month] ?> <?= $sel_year ?>
</h5>

<div class="card border-0 shadow-sm mb-5">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Student</th><th>Roll No</th><th>Class</th>
                    <th>Fee</th><th>Dues</th><th>Total</th>
                    <th>Paid</th><th>Balance</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $has = false;
            while ($r = mysqli_fetch_assoc($monthly)):
                $has = true;
                $badge = ['unpaid'=>'danger','partial'=>'warning','paid'=>'success'];
            ?>
                <tr>
                    <td><strong><?= htmlspecialchars($r['full_name']) ?></strong></td>
                    <td><?= htmlspecialchars($r['roll_no']) ?></td>
                    <td><?= htmlspecialchars($r['class_name']) ?></td>
                    <td>Rs. <?= number_format($r['fee_amount'],0) ?></td>
                    <td class="text-danger">Rs. <?= number_format($r['previous_dues'],0) ?></td>
                    <td><strong>Rs. <?= number_format($r['total_amount'],0) ?></strong></td>
                    <td class="text-success">Rs. <?= number_format($r['paid_amount'],0) ?></td>
                    <td class="text-danger">Rs. <?= number_format($r['balance'],0) ?></td>
                    <td>
                        <span class="badge bg-<?= $badge[$r['status']] ?>">
                            <?= ucfirst($r['status']) ?>
                        </span>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (!$has): ?>
                <tr><td colspan="9" class="text-center text-muted py-3">
                    No vouchers for this period.
                </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Outstanding Dues -->
<h5 class="fw-bold mb-3">
    <i class="bi bi-exclamation-triangle me-2 text-danger"></i>
    Overall Outstanding Dues
    <span class="text-danger ms-2">
        (Rs. <?= number_format($grand_due['total'] ?? 0, 0) ?>)
    </span>
</h5>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Student</th><th>Roll No</th><th>Class</th>
                    <th>Unpaid Vouchers</th><th>Total Due</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $has_out = false;
            while ($o = mysqli_fetch_assoc($outstanding)):
                $has_out = true;
            ?>
                <tr>
                    <td><strong><?= htmlspecialchars($o['full_name']) ?></strong></td>
                    <td><?= htmlspecialchars($o['roll_no']) ?></td>
                    <td><?= htmlspecialchars($o['class_name']) ?></td>
                    <td>
                        <span class="badge bg-warning text-dark">
                            <?= $o['unpaid_count'] ?>
                        </span>
                    </td>
                    <td class="text-danger fw-bold">
                        Rs. <?= number_format($o['total_due'], 0) ?>
                    </td>
                    <td>
                        <a href="/04. eaglets_school/fees/index.php?student_id=<?php
                            $sid = mysqli_fetch_assoc(mysqli_query($conn,
                                "SELECT id FROM students WHERE roll_no='"
                                .mysqli_real_escape_string($conn,$o['roll_no'])."' LIMIT 1"
                            ));
                            echo $sid['id'] ?? 0;
                        ?>" class="btn btn-sm btn-outline-primary">
                            View Vouchers
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (!$has_out): ?>
                <tr><td colspan="6" class="text-center text-success py-3">
                    <i class="bi bi-check-circle"></i> No outstanding dues!
                </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>