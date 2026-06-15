<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$salary_id = intval($_GET['salary_id'] ?? 0);

$record = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT sp.*, t.full_name, t.phone, t.cnic, t.address
    FROM salary_payments sp
    JOIN teachers t ON sp.teacher_id = t.id
    WHERE sp.id = $salary_id
"));

if (!$record) {
    echo '<div class="alert alert-warning">Record not found.</div>';
    echo '<a href="index.php" class="btn btn-secondary">Back</a>';
    require_once '../includes/footer.php';
    exit();
}

$months     = ['','January','February','March','April','May','June',
               'July','August','September','October','November','December'];
$receipt_no = 'SAL-' . str_pad($record['id'], 5, '0', STR_PAD_LEFT);
$pageTitle  = "Salary Receipt";
require_once '../includes/header.php';
?>

<style>
@media print {
    .sidebar, .topbar, .d-print-none { display:none !important; }
    body { background:white !important; }
    .receipt-card { box-shadow:none !important; border:1px solid #ddd !important; }
}
</style>

<div class="mb-3 d-print-none d-flex gap-2">
    <button onclick="window.print()" class="btn btn-success">
        <i class="bi bi-printer me-1"></i> Print Receipt
    </button>
    <a href="index.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="receipt-card card border-0 shadow-sm" style="max-width:620px">
    <div class="card-body p-4">

        <!-- Header -->
        <div class="text-center mb-3">
            <div style="width:60px; height:60px; background:#1b4332; border-radius:50%;
                        display:flex; align-items:center; justify-content:center;
                        margin:0 auto 10px;">
                <i class="bi bi-mortarboard-fill text-white fs-4"></i>
            </div>
            <h5 class="fw-bold mb-0">The Eaglets Nursery School System</h5>
            <div class="text-muted small">Shah Noor Pull</div>
            <hr>
            <h6 class="fw-bold text-success">
                <i class="bi bi-check-circle-fill"></i> SALARY PAYMENT RECEIPT
            </h6>
        </div>

        <!-- Receipt Info -->
        <div class="row mb-3">
            <div class="col-6">
                <div class="text-muted small">Receipt No.</div>
                <div class="fw-bold"><?= $receipt_no ?></div>
            </div>
            <div class="col-6 text-end">
                <div class="text-muted small">Payment Date</div>
                <div class="fw-bold">
                    <?= $record['payment_date']
                        ? date('d M Y', strtotime($record['payment_date']))
                        : '—' ?>
                </div>
            </div>
        </div>

        <hr>

        <!-- Teacher Info -->
        <div class="row mb-3">
            <div class="col-6">
                <div class="text-muted small">Teacher Name</div>
                <div class="fw-bold"><?= htmlspecialchars($record['full_name']) ?></div>
                <div class="small"><?= htmlspecialchars($record['phone']) ?></div>
                <div class="small text-muted">
                    CNIC: <?= htmlspecialchars($record['cnic']) ?>
                </div>
            </div>
            <div class="col-6">
                <div class="text-muted small">Salary Period</div>
                <div class="fw-bold">
                    <?= $months[$record['salary_month']] ?> <?= $record['salary_year'] ?>
                </div>
                <div class="small text-muted">
                    Method: <?= ucfirst(str_replace('_',' ',$record['payment_method'])) ?>
                </div>
            </div>
        </div>

        <hr>

        <!-- Salary Breakdown -->
        <table class="table table-borderless mb-0">
            <tr>
                <td>Monthly Salary</td>
                <td class="text-end">
                    Rs. <?= number_format($record['salary_amount'], 2) ?>
                </td>
            </tr>
            <tr class="border-top bg-success bg-opacity-10">
                <td><strong>Amount Paid</strong></td>
                <td class="text-end text-success fw-bold fs-5">
                    Rs. <?= number_format($record['paid_amount'], 2) ?>
                </td>
            </tr>
            <?php if ($record['remaining'] > 0): ?>
            <tr class="border-top">
                <td class="text-danger">Remaining Balance</td>
                <td class="text-end text-danger fw-bold">
                    Rs. <?= number_format($record['remaining'], 2) ?>
                </td>
            </tr>
            <?php endif; ?>
            <tr class="border-top">
                <td>Status</td>
                <td class="text-end">
                    <?php $badge = ['unpaid'=>'danger','partial'=>'warning','paid'=>'success']; ?>
                    <span class="badge bg-<?= $badge[$record['status']] ?>">
                        <?= ucfirst($record['status']) ?>
                    </span>
                </td>
            </tr>
        </table>

        <?php if ($record['notes']): ?>
        <div class="mt-2 small text-muted">
            <strong>Note:</strong> <?= htmlspecialchars($record['notes']) ?>
        </div>
        <?php endif; ?>

        <hr>

        <div class="row mt-3">
            <div class="col-6 text-center">
                <div style="border-top:1px solid #333; padding-top:5px;
                            margin-top:30px; font-size:12px;">
                    Paid By (School)
                </div>
            </div>
            <div class="col-6 text-center">
                <div style="border-top:1px solid #333; padding-top:5px;
                            margin-top:30px; font-size:12px;">
                    Teacher Signature
                </div>
            </div>
        </div>

        <div class="text-center mt-3 text-muted small">
            This is a computer-generated receipt — The Eaglets Nursery School System
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>