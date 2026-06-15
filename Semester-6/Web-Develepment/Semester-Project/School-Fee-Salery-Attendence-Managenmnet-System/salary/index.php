<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$filter_teacher = intval($_GET['teacher_id'] ?? 0);
$where = $filter_teacher ? "WHERE sp.teacher_id=$filter_teacher" : "";

$salaries = mysqli_query($conn, "
    SELECT sp.*, t.full_name, t.monthly_salary
    FROM salary_payments sp
    JOIN teachers t ON sp.teacher_id = t.id
    $where
    ORDER BY sp.salary_year DESC, sp.salary_month DESC
");

$teachers  = mysqli_query($conn, "SELECT * FROM teachers WHERE status='active' ORDER BY full_name");
$months    = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$pageTitle = "Salaries";
require_once '../includes/header.php';
?>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">Done successfully.</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Salary Records</h5>
    <a href="pay.php" class="btn btn-success btn-sm">
        <i class="bi bi-plus"></i> Process Salary
    </a>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex gap-3 align-items-center">
            <label class="mb-0 fw-bold small">Filter by Teacher:</label>
            <select name="teacher_id" class="form-select form-select-sm"
                    style="width:220px" onchange="this.form.submit()">
                <option value="">All Teachers</option>
                <?php while ($t = mysqli_fetch_assoc($teachers)): ?>
                    <option value="<?= $t['id'] ?>"
                        <?= $t['id']==$filter_teacher?'selected':'' ?>>
                        <?= htmlspecialchars($t['full_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <?php if ($filter_teacher): ?>
                <a href="index.php" class="btn btn-sm btn-outline-secondary">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th><th>Teacher</th><th>Period</th>
                    <th>Salary</th><th>Paid</th><th>Remaining</th>
                    <th>Payment Date</th><th>Status</th><th>Actions</th>
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
                    <td><?= $s['id'] ?></td>
                    <td><strong><?= htmlspecialchars($s['full_name']) ?></strong></td>
                    <td><?= $months[$s['salary_month']] ?> <?= $s['salary_year'] ?></td>
                    <td>Rs. <?= number_format($s['salary_amount'], 0) ?></td>
                    <td class="text-success fw-bold">
                        Rs. <?= number_format($s['paid_amount'], 0) ?>
                    </td>
                    <td class="text-danger fw-bold">
                        Rs. <?= number_format($s['remaining'], 0) ?>
                    </td>
                    <td>
                        <?= $s['payment_date']
                            ? date('d M Y', strtotime($s['payment_date']))
                            : '—' ?>
                    </td>
                    <td>
                        <span class="badge bg-<?= $badge[$s['status']] ?>">
                            <?= ucfirst($s['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($s['status'] !== 'paid'): ?>
                        <a href="pay.php?salary_id=<?= $s['id'] ?>"
                           class="btn btn-sm btn-outline-success">Pay</a>
                        <?php endif; ?>
                        <a href="receipt.php?salary_id=<?= $s['id'] ?>"
                           class="btn btn-sm btn-outline-primary">Receipt</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (!$has): ?>
                <tr><td colspan="9" class="text-center text-muted py-3">
                    No salary records found.
                </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>