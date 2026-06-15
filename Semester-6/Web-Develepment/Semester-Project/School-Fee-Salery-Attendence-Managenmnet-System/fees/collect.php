<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$preselected = intval($_GET['voucher_id'] ?? 0);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voucher_id     = intval($_POST['voucher_id']);
    $amount_paid    = floatval($_POST['amount_paid']);
    $payment_date   = $_POST['payment_date'];
    $payment_method = $_POST['payment_method'];
    $received_by    = trim($_POST['received_by']);
    $notes          = trim($_POST['notes']);

    $voucher = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM fee_vouchers WHERE id=$voucher_id"
    ));

    if (!$voucher) {
        $error = "Voucher not found.";
    } elseif ($amount_paid <= 0) {
        $error = "Amount must be greater than 0.";
    } else {
        $balance    = $voucher['total_amount'] - $voucher['paid_amount'];
        $student_id = $voucher['student_id'];

        // Calculate advance
        $advance = 0;
        $actual_paid = $amount_paid;

        if ($amount_paid > $balance) {
            $advance     = $amount_paid - $balance;
            $actual_paid = $balance; // only credit balance to this voucher
        }

        // Insert payment
        $stmt = mysqli_prepare($conn,
            "INSERT INTO fee_payments
             (voucher_id, student_id, amount_paid, payment_date,
              payment_method, received_by, notes)
             VALUES (?,?,?,?,?,?,?)"
        );
        mysqli_stmt_bind_param($stmt, "iidssss",
            $voucher_id, $student_id, $amount_paid,
            $payment_date, $payment_method, $received_by, $notes
        );
        mysqli_stmt_execute($stmt);
        $payment_id = mysqli_insert_id($conn);

        // Update voucher — mark as paid fully
        $new_paid   = $voucher['paid_amount'] + $actual_paid;
        $new_status = 'paid'; // since we covered full balance

        mysqli_query($conn,
            "UPDATE fee_vouchers
             SET paid_amount=$new_paid, status='$new_status'
             WHERE id=$voucher_id"
        );

        // Save advance balance to student
        if ($advance > 0) {
            mysqli_query($conn,
                "UPDATE students
                 SET advance_balance = advance_balance + $advance
                 WHERE id=$student_id"
            );
        }

        header("Location: /04. eaglets_school/receipts/index.php?payment_id=$payment_id");
        exit();
    }
}

// Get all unpaid/partial vouchers
$vouchers = mysqli_query($conn, "
    SELECT v.*, s.full_name, s.roll_no, s.advance_balance,
           c.name AS class_name
    FROM fee_vouchers v
    JOIN students s ON v.student_id = s.id
    JOIN classes c  ON s.class_id   = c.id
    WHERE v.status != 'paid'
    ORDER BY v.fee_year DESC, v.fee_month DESC
");

$months   = ['','Jan','Feb','Mar','Apr','May','Jun',
             'Jul','Aug','Sep','Oct','Nov','Dec'];
$pageTitle = "Collect Fee";
require_once '../includes/header.php';
?>

<div class="card border-0 shadow-sm" style="max-width:540px">
    <div class="card-header bg-white fw-bold">Collect Fee Payment</div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Select Voucher *</label>
                <select name="voucher_id" id="voucher_id"
                        class="form-select"
                        onchange="updateBalance(this)" required>
                    <option value="">-- Select Unpaid Voucher --</option>
                    <?php while ($v = mysqli_fetch_assoc($vouchers)):
                        $bal = $v['total_amount'] - $v['paid_amount'];
                    ?>
                        <option value="<?= $v['id'] ?>"
                            data-balance="<?= $bal ?>"
                            data-advance="<?= $v['advance_balance'] ?>"
                            <?= $v['id']==$preselected?'selected':'' ?>>
                            <?= htmlspecialchars($v['full_name']) ?> —
                            <?= htmlspecialchars($v['class_name']) ?> —
                            <?= $months[$v['fee_month']] ?> <?= $v['fee_year'] ?> —
                            Due: Rs. <?= number_format($bal, 0) ?>
                            <?= $v['advance_balance'] > 0
                                ? '| Advance: Rs.'.number_format($v['advance_balance'],0)
                                : '' ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Balance Info -->
            <div class="alert alert-warning py-2 small"
                 id="balance-box" style="display:none">
                <i class="bi bi-info-circle"></i>
                Balance due: <strong id="balance-display">Rs. 0</strong>
                <span id="advance-info" style="display:none">
                    | Advance balance:
                    <strong id="advance-display" class="text-success"></strong>
                </span>
            </div>

            <!-- Advance Alert -->
            <div class="alert alert-success py-2 small"
                 id="advance-box" style="display:none">
                <i class="bi bi-piggy-bank me-1"></i>
                Amount exceeds balance — extra will be saved as
                <strong>advance balance</strong> for next month.
            </div>

            <div class="mb-3">
                <label class="form-label">Amount (Rs.) *</label>
                <input type="number" name="amount_paid" id="amount_paid"
                       class="form-control" step="0.01" min="1"
                       oninput="checkAdvance(this.value)"
                       required>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Date *</label>
                <input type="date" name="payment_date" class="form-control"
                       value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
                <div class="col mb-3">
                    <label class="form-label">Received By</label>
                    <input type="text" name="received_by" class="form-control"
                           placeholder="Staff name">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <input type="text" name="notes" class="form-control"
                       placeholder="Optional">
            </div>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-cash-coin me-1"></i> Collect Payment
            </button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
let currentBalance = 0;

function updateBalance(sel) {
    const opt = sel.options[sel.selectedIndex];
    const bal = parseFloat(opt.getAttribute('data-balance')) || 0;
    const adv = parseFloat(opt.getAttribute('data-advance')) || 0;
    currentBalance = bal;

    if (bal) {
        document.getElementById('balance-box').style.display = 'block';
        document.getElementById('balance-display').textContent =
            'Rs. ' + bal.toLocaleString();
        document.getElementById('amount_paid').value = bal;

        if (adv > 0) {
            document.getElementById('advance-info').style.display = 'inline';
            document.getElementById('advance-display').textContent =
                'Rs. ' + adv.toLocaleString();
        } else {
            document.getElementById('advance-info').style.display = 'none';
        }
    } else {
        document.getElementById('balance-box').style.display = 'none';
    }
    checkAdvance(bal);
}

function checkAdvance(val) {
    const amount = parseFloat(val) || 0;
    const box = document.getElementById('advance-box');
    if (amount > currentBalance && currentBalance > 0) {
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}

window.onload = function() {
    const sel = document.getElementById('voucher_id');
    if (sel.value) updateBalance(sel);
}
</script>

<?php require_once '../includes/footer.php'; ?>