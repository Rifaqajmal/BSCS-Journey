<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$filter_student = intval($_GET['student_id'] ?? 0);
$where = $filter_student ? "WHERE v.student_id=$filter_student" : "";

$vouchers = mysqli_query($conn, "
    SELECT v.*, s.full_name AS student_name, s.roll_no,
           c.name AS class_name
    FROM fee_vouchers v
    JOIN students s ON v.student_id = s.id
    JOIN classes c  ON s.class_id   = c.id
    $where
    ORDER BY v.fee_year DESC, v.fee_month DESC
");

$months   = ['','Jan','Feb','Mar','Apr','May','Jun',
             'Jul','Aug','Sep','Oct','Nov','Dec'];
$pageTitle = "Fee Vouchers";
require_once '../includes/header.php';
?>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">Done successfully.</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Fee Vouchers</h5>
    <a href="generate.php" class="btn btn-success btn-sm">
        <i class="bi bi-plus"></i> Generate Voucher
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th><th>Student</th><th>Class</th><th>Period</th>
                    <th>Fee</th><th>Dues</th><th>Total</th>
                    <th>Paid</th><th>Balance</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $has = false;
            while ($v = mysqli_fetch_assoc($vouchers)):
                $has = true;
                $balance = $v['total_amount'] - $v['paid_amount'];
                $badge   = ['unpaid'=>'danger','partial'=>'warning','paid'=>'success'];
            ?>
                <tr>
                    <td><?= $v['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($v['student_name']) ?></strong>
                        <div class="small text-muted">
                            <?= htmlspecialchars($v['roll_no']) ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($v['class_name']) ?></td>
                    <td><?= $months[$v['fee_month']] ?> <?= $v['fee_year'] ?></td>
                    <td>Rs. <?= number_format($v['fee_amount'], 0) ?></td>
                    <td class="text-danger">
                        Rs. <?= number_format($v['previous_dues'], 0) ?>
                    </td>
                    <td><strong>Rs. <?= number_format($v['total_amount'], 0) ?></strong></td>
                    <td class="text-success">
                        Rs. <?= number_format($v['paid_amount'], 0) ?>
                    </td>
                    <td class="text-danger">
                        Rs. <?= number_format($balance, 0) ?>
                    </td>
                    <td>
                        <span class="badge bg-<?= $badge[$v['status']] ?>">
                            <?= ucfirst($v['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="view.php?id=<?= $v['id'] ?>"
                           class="btn btn-sm btn-outline-secondary">View</a>
                        <?php if ($v['status'] !== 'paid'): ?>
                        <a href="collect.php?voucher_id=<?= $v['id'] ?>"
                           class="btn btn-sm btn-outline-success">Collect</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (!$has): ?>
                <tr><td colspan="11" class="text-center text-muted py-3">
                    No vouchers found.
                </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>