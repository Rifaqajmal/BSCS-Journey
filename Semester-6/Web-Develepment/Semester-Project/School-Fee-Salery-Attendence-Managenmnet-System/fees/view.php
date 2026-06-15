<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$id      = intval($_GET['id'] ?? 0);
$voucher = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT v.*,
           s.full_name, s.roll_no, s.father_name, s.phone, s.address,
           c.name AS class_name
    FROM fee_vouchers v
    JOIN students s ON v.student_id = s.id
    JOIN classes c  ON s.class_id   = c.id
    WHERE v.id = $id
"));

if (!$voucher) { echo "Voucher not found."; exit(); }

$months  = ['','January','February','March','April','May','June',
            'July','August','September','October','November','December'];
$balance = $voucher['total_amount'] - $voucher['paid_amount'];
$badge   = ['unpaid'=>'danger','partial'=>'warning','paid'=>'success'];

$payments = mysqli_query($conn,
    "SELECT * FROM fee_payments WHERE voucher_id=$id ORDER BY payment_date DESC"
);

$pageTitle = "Fee Voucher";
require_once '../includes/header.php';
?>

<?php if (isset($_GET['new'])): ?>
    <div class="alert alert-success">
        <i class="bi bi-check-circle"></i> Voucher generated successfully!
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-mortarboard-fill text-success"></i>
                            The Eaglets Nursery School
                        </h5>
                        <div class="text-muted small">Shah Noor Pull — Fee Voucher</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">
                            Voucher #<?= str_pad($voucher['id'], 4, '0', STR_PAD_LEFT) ?>
                        </div>
                        <div class="text-muted small">
                            <?= date('d M Y', strtotime($voucher['generated_at'])) ?>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="text-muted small">Student</div>
                        <div class="fw-bold">
                            <?= htmlspecialchars($voucher['full_name']) ?>
                        </div>
                        <div class="small">
                            <?= htmlspecialchars($voucher['father_name']) ?>
                        </div>
                        <div class="small text-muted">
                            Roll: <?= htmlspecialchars($voucher['roll_no']) ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Class</div>
                        <div class="fw-bold">
                            <?= htmlspecialchars($voucher['class_name']) ?>
                        </div>
                        <div class="small text-muted">
                            Period: <?= $months[$voucher['fee_month']] ?>
                            <?= $voucher['fee_year'] ?>
                        </div>
                    </div>
                </div>
                <hr>
                <table class="table table-borderless mb-0">
                    <tr>
                        <td>Monthly Fee</td>
                        <td class="text-end">
                            Rs. <?= number_format($voucher['fee_amount'], 2) ?>
                        </td>
                    </tr>
                    <?php if ($voucher['discount'] > 0): ?>
                    <tr>
                        <td class="text-success">Discount</td>
                        <td class="text-end text-success">
                            - Rs. <?= number_format($voucher['discount'], 2) ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="text-danger">Previous Dues</td>
                        <td class="text-end text-danger">
                            Rs. <?= number_format($voucher['previous_dues'], 2) ?>
                        </td>
                    </tr>
                    <tr class="border-top">
                        <td><strong>Total Amount</strong></td>
                        <td class="text-end fw-bold">
                            Rs. <?= number_format($voucher['total_amount'], 2) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-success">Amount Paid</td>
                        <td class="text-end text-success">
                            Rs. <?= number_format($voucher['paid_amount'], 2) ?>
                        </td>
                    </tr>
                    <tr class="border-top">
                        <td><strong class="text-danger">Balance Due</strong></td>
                        <td class="text-end text-danger fw-bold fs-5">
                            Rs. <?= number_format($balance, 2) ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <?php if (mysqli_num_rows($payments) > 0): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Payment History</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th><th>Amount</th>
                            <th>Method</th><th>Received By</th><th>Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($p = mysqli_fetch_assoc($payments)): ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($p['payment_date'])) ?></td>
                            <td class="text-success fw-bold">
                                Rs. <?= number_format($p['amount_paid'], 0) ?>
                            </td>
                            <td>
                                <?= ucfirst(str_replace('_',' ',$p['payment_method'])) ?>
                            </td>
                            <td><?= htmlspecialchars($p['received_by']) ?></td>
                            <td>
                                <a href="/04. eaglets_school/receipts/index.php?payment_id=<?= $p['id'] ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-receipt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-bold">Actions</div>
            <div class="card-body d-grid gap-2">
                <?php if ($voucher['status'] !== 'paid'): ?>
                <a href="collect.php?voucher_id=<?= $voucher['id'] ?>"
                   class="btn btn-success">
                    <i class="bi bi-cash-coin me-1"></i> Collect Fee
                </a>
                <?php endif; ?>
                <a href="/04. eaglets_school/receipts/index.php?voucher_id=<?= $voucher['id'] ?>"
                   class="btn btn-outline-primary">
                    <i class="bi bi-printer me-1"></i> Print Voucher
                </a>
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Status</div>
                <span class="badge bg-<?= $badge[$voucher['status']] ?> fs-6 mb-2">
                    <?= ucfirst($voucher['status']) ?>
                </span>
                <div class="text-muted small mt-2">Balance Due</div>
                <div class="fs-2 fw-bold text-<?= $balance > 0 ? 'danger':'success' ?>">
                    Rs. <?= number_format($balance, 0) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>