<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$payment_id  = intval($_GET['payment_id'] ?? 0);
$voucher_id  = intval($_GET['voucher_id'] ?? 0);

// If voucher_id given get latest payment
if (!$payment_id && $voucher_id) {
    $latest = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT id FROM fee_payments
         WHERE voucher_id=$voucher_id
         ORDER BY created_at DESC LIMIT 1"
    ));
    if ($latest) $payment_id = $latest['id'];
}

$payment = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT p.*,
           s.full_name, s.roll_no, s.father_name, s.phone, s.address,
           c.name AS class_name,
           sec.name AS section_name,
           v.fee_month, v.fee_year, v.fee_amount, v.discount,
           v.previous_dues, v.total_amount, v.paid_amount,
           v.status AS voucher_status
    FROM fee_payments p
    JOIN fee_vouchers v  ON p.voucher_id  = v.id
    JOIN students s      ON p.student_id  = s.id
    JOIN classes c       ON s.class_id    = c.id
    LEFT JOIN sections sec ON s.section_id = sec.id
    WHERE p.id = $payment_id
"));

if (!$payment) {
    echo '<div class="alert alert-warning">Receipt not found — please collect fee first.</div>';
    echo '<a href="/04. eaglets_school/fees/index.php" class="btn btn-secondary">Back to Vouchers</a>';
    require_once '../includes/footer.php';
    exit();
}

$months     = ['','January','February','March','April','May','June',
               'July','August','September','October','November','December'];
$balance    = $payment['total_amount'] - $payment['paid_amount'];
$receipt_no = 'ENS-' . str_pad($payment['id'], 5, '0', STR_PAD_LEFT);

$pageTitle  = "Payment Receipt";
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
    <a href="/04. eaglets_school/fees/view.php?id=<?= $payment['voucher_id'] ?>"
       class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Voucher
    </a>
</div>

<div class="receipt-card card border-0 shadow-sm" style="max-width:620px">
    <div class="card-body p-4">

        <!-- School Header -->
        <div class="text-center mb-3">
            <div style="width:60px; height:60px; background:#1b4332; border-radius:50%;
                        display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                <i class="bi bi-mortarboard-fill text-white fs-4"></i>
            </div>
            <h5 class="fw-bold mb-0">The Eaglets Nursery School System</h5>
            <div class="text-muted small">Shah Noor Pull</div>
            <hr>
            <h6 class="fw-bold text-success">
                <i class="bi bi-check-circle-fill"></i> FEE PAYMENT RECEIPT
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
                    <?= date('d M Y', strtotime($payment['payment_date'])) ?>
                </div>
            </div>
        </div>

        <hr>

        <!-- Student Info -->
        <div class="row mb-3">
            <div class="col-6">
                <div class="text-muted small">Student Name</div>
                <div class="fw-bold"><?= htmlspecialchars($payment['full_name']) ?></div>
                <div class="small">Father: <?= htmlspecialchars($payment['father_name']) ?></div>
                <div class="small text-muted">Roll: <?= htmlspecialchars($payment['roll_no']) ?></div>
            </div>
            <div class="col-6">
                <div class="text-muted small">Class</div>
                <div class="fw-bold"><?= htmlspecialchars($payment['class_name']) ?></div>
                <?php if ($payment['section_name']): ?>
                <div class="small">Section: <?= htmlspecialchars($payment['section_name']) ?></div>
                <?php endif; ?>
                <div class="small text-muted">
                    For: <?= $months[$payment['fee_month']] ?> <?= $payment['fee_year'] ?>
                </div>
            </div>
        </div>

        <hr>

        <!-- Fee Breakdown -->
        <table class="table table-borderless mb-0">
            <tr>
                <td>Monthly Fee</td>
                <td class="text-end">Rs. <?= number_format($payment['fee_amount'], 2) ?></td>
            </tr>
            <?php if ($payment['discount'] > 0): ?>
            <tr>
                <td class="text-success">Discount</td>
                <td class="text-end text-success">
                    - Rs. <?= number_format($payment['discount'], 2) ?>
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <td>Previous Dues</td>
                <td class="text-end text-danger">
                    Rs. <?= number_format($payment['previous_dues'], 2) ?>
                </td>
            </tr>
            <tr class="border-top">
                <td>Total Bill</td>
                <td class="text-end">Rs. <?= number_format($payment['total_amount'], 2) ?></td>
            </tr>
            <tr class="border-top bg-success bg-opacity-10">
                <td><strong>Amount Received</strong></td>
                <td class="text-end text-success fw-bold fs-5">
                    Rs. <?= number_format($payment['amount_paid'], 2) ?>
                </td>
            </tr>
            <tr>
                <td>Payment Method</td>
                <td class="text-end">
                    <?= ucfirst(str_replace('_',' ',$payment['payment_method'])) ?>
                </td>
            </tr>
            <?php if ($payment['received_by']): ?>
            <tr>
                <td>Received By</td>
                <td class="text-end"><?= htmlspecialchars($payment['received_by']) ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($balance > 0): ?>
            <tr class="border-top">
                <td class="text-danger">Remaining Balance</td>
                <td class="text-end text-danger fw-bold">
                    Rs. <?= number_format($balance, 2) ?>
                </td>
            </tr>
            <?php endif; ?>
        </table>

        <hr>

        <div class="row mt-3">
            <div class="col-6 text-center">
                <div style="border-top:1px solid #333; padding-top:5px;
                            margin-top:30px; font-size:12px;">
                    Received By
                </div>
            </div>
            <div class="col-6 text-center">
                <div style="border-top:1px solid #333; padding-top:5px;
                            margin-top:30px; font-size:12px;">
                    Parent / Guardian Signature
                </div>
            </div>
        </div>

        <div class="text-center mt-3 text-muted small">
            This is a computer-generated receipt — The Eaglets Nursery School System
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>