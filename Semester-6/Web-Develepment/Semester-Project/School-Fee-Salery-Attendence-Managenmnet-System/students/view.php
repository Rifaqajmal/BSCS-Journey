<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$id      = intval($_GET['id'] ?? 0);
$student = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT s.*, c.name AS class_name, c.monthly_fee
    FROM students s
    JOIN classes c ON s.class_id = c.id
    WHERE s.id = $id
"));
if (!$student) { echo "Not found."; exit(); }

$fee_summary = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(*)            AS total_vouchers,
        SUM(total_amount)   AS total_billed,
        SUM(paid_amount)    AS total_paid,
        SUM(total_amount - paid_amount) AS total_due
    FROM fee_vouchers WHERE student_id=$id
"));

$vouchers = mysqli_query($conn, "
    SELECT * FROM fee_vouchers WHERE student_id=$id
    ORDER BY fee_year DESC, fee_month DESC LIMIT 5
");

$months   = ['','Jan','Feb','Mar','Apr','May','Jun',
             'Jul','Aug','Sep','Oct','Nov','Dec'];
$pageTitle = "Student Profile";
require_once '../includes/header.php';
?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div style="width:80px; height:80px; background:#e8f5e9;
                            border-radius:50%; display:flex;
                            align-items:center; justify-content:center;
                            margin:0 auto 15px;">
                    <i class="bi bi-person-fill text-success fs-2"></i>
                </div>
                <h5 class="fw-bold mb-1">
                    <?= htmlspecialchars($student['full_name']) ?>
                </h5>
                <div class="text-muted small mb-2">
                    <?= htmlspecialchars($student['father_name']) ?>
                </div>
                <span class="badge bg-<?= $student['status']==='active'?'success':'secondary' ?> mb-3">
                    <?= ucfirst($student['status']) ?>
                </span>

                <?php if ($student['advance_balance'] > 0): ?>
                <div class="alert alert-success py-2 small mb-2">
                    <i class="bi bi-piggy-bank me-1"></i>
                    Advance Balance:
                    <strong>Rs. <?= number_format($student['advance_balance'], 0) ?></strong>
                </div>
                <?php endif; ?>

                <hr>
                <div class="text-start small">
                    <div class="mb-2">
                        <strong>Roll No:</strong>
                        <?= htmlspecialchars($student['roll_no']) ?>
                    </div>
                    <div class="mb-2">
                        <strong>Class:</strong>
                        <?= htmlspecialchars($student['class_name']) ?>
                    </div>
                    <div class="mb-2">
                        <strong>Gender:</strong>
                        <?= ucfirst($student['gender']) ?>
                    </div>
                    <div class="mb-2">
                        <strong>Phone:</strong>
                        <?= htmlspecialchars($student['phone']) ?>
                    </div>
                    <div class="mb-2">
                        <strong>DOB:</strong>
                        <?= $student['date_of_birth'] ?>
                    </div>
                    <div class="mb-2">
                        <strong>Admission:</strong>
                        <?= $student['admission_date'] ?>
                    </div>
                    <div>
                        <strong>Address:</strong>
                        <?= htmlspecialchars($student['address']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2 mt-3">
            <a href="edit.php?id=<?= $id ?>"
               class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit Student
            </a>
            <a href="/04. eaglets_school/fees/generate.php?student_id=<?= $id ?>"
               class="btn btn-success btn-sm">
                <i class="bi bi-receipt me-1"></i> Generate Fee Voucher
            </a>
        </div>
    </div>

    <div class="col-md-8">
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">Total Billed</div>
                    <div class="fw-bold fs-5">
                        Rs. <?= number_format($fee_summary['total_billed'] ?? 0, 0) ?>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">Total Paid</div>
                    <div class="fw-bold fs-5 text-success">
                        Rs. <?= number_format($fee_summary['total_paid'] ?? 0, 0) ?>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">Outstanding Due</div>
                    <div class="fw-bold fs-5 text-danger">
                        Rs. <?= number_format($fee_summary['total_due'] ?? 0, 0) ?>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">Advance Balance</div>
                    <div class="fw-bold fs-5 text-success">
                        Rs. <?= number_format($student['advance_balance'] ?? 0, 0) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold d-flex justify-content-between">
                <span>Recent Fee Vouchers</span>
                <a href="/04. eaglets_school/fees/index.php?student_id=<?= $id ?>"
                   class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Period</th><th>Total</th>
                            <th>Paid</th><th>Balance</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $has = false;
                    while ($v = mysqli_fetch_assoc($vouchers)):
                        $has = true;
                        $bal   = $v['total_amount'] - $v['paid_amount'];
                        $badge = ['unpaid'=>'danger','partial'=>'warning','paid'=>'success'];
                    ?>
                        <tr>
                            <td><?= $months[$v['fee_month']] ?> <?= $v['fee_year'] ?></td>
                            <td>Rs. <?= number_format($v['total_amount'], 0) ?></td>
                            <td class="text-success">
                                Rs. <?= number_format($v['paid_amount'], 0) ?>
                            </td>
                            <td class="text-danger">
                                Rs. <?= number_format($bal, 0) ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $badge[$v['status']] ?>">
                                    <?= ucfirst($v['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if (!$has): ?>
                        <tr><td colspan="5" class="text-center text-muted py-3">
                            No vouchers yet.
                        </td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>