<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$preselected = intval($_GET['salary_id'] ?? 0);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id    = intval($_POST['teacher_id']);
    $salary_month  = intval($_POST['salary_month']);
    $salary_year   = intval($_POST['salary_year']);
    $paid_amount   = floatval($_POST['paid_amount']);
    $payment_date  = $_POST['payment_date'];
    $payment_method = $_POST['payment_method'];
    $notes         = trim($_POST['notes']);

    // Get teacher salary
    $teacher = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM teachers WHERE id=$teacher_id"
    ));

    if (!$teacher) {
        $error = "Teacher not found.";
    } elseif ($paid_amount <= 0) {
        $error = "Amount must be greater than 0.";
    } else {
        // Check if record exists
        $existing = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT * FROM salary_payments
             WHERE teacher_id=$teacher_id
             AND salary_month=$salary_month
             AND salary_year=$salary_year"
        ));

        if ($existing) {
            // Update existing
            $new_paid = $existing['paid_amount'] + $paid_amount;
            if ($new_paid > $existing['salary_amount']) {
                $error = "Amount exceeds salary (Rs.".number_format($existing['remaining'],0)." remaining)";
            } else {
                $remaining  = $existing['salary_amount'] - $new_paid;
                $new_status = ($new_paid >= $existing['salary_amount']) ? 'paid' : 'partial';
                mysqli_query($conn,
                    "UPDATE salary_payments
                     SET paid_amount=$new_paid, remaining=$remaining,
                         status='$new_status', payment_date='$payment_date',
                         payment_method='$payment_method', notes='$notes'
                     WHERE id={$existing['id']}"
                );
                header("Location: receipt.php?salary_id={$existing['id']}"); exit();
            }
        } else {
            // New record
            $salary_amount = $teacher['monthly_salary'];
            if ($paid_amount > $salary_amount) {
                $error = "Amount exceeds monthly salary (Rs.".number_format($salary_amount,0).")";
            } else {
                $remaining  = $salary_amount - $paid_amount;
                $status     = ($paid_amount >= $salary_amount) ? 'paid' : 'partial';
                $stmt = mysqli_prepare($conn,
                    "INSERT INTO salary_payments
                     (teacher_id, salary_month, salary_year, salary_amount,
                      paid_amount, remaining, payment_date, payment_method, notes, status)
                     VALUES (?,?,?,?,?,?,?,?,?,?)"
                );
                mysqli_stmt_bind_param($stmt, "iiidddssss",
                    $teacher_id, $salary_month, $salary_year,
                    $salary_amount, $paid_amount, $remaining,
                    $payment_date, $payment_method, $notes, $status
                );
                mysqli_stmt_execute($stmt);
                $new_id = mysqli_insert_id($conn);
                header("Location: receipt.php?salary_id=$new_id"); exit();
            }
        }
    }
}

$teachers = mysqli_query($conn,
    "SELECT * FROM teachers WHERE status='active' ORDER BY full_name"
);

$months_list = ['1'=>'January','2'=>'February','3'=>'March','4'=>'April',
                '5'=>'May','6'=>'June','7'=>'July','8'=>'August',
                '9'=>'September','10'=>'October','11'=>'November','12'=>'December'];

// If salary_id preselected get teacher info
$presel_data = null;
if ($preselected) {
    $presel_data = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT sp.*, t.full_name FROM salary_payments sp
         JOIN teachers t ON sp.teacher_id=t.id
         WHERE sp.id=$preselected"
    ));
}

$pageTitle = "Process Salary";
require_once '../includes/header.php';
?>

<div class="card border-0 shadow-sm" style="max-width:520px">
    <div class="card-header bg-white fw-bold">Process Salary Payment</div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Select Teacher *</label>
                <select name="teacher_id" class="form-select" required>
                    <option value="">-- Select Teacher --</option>
                    <?php while ($t = mysqli_fetch_assoc($teachers)): ?>
                        <option value="<?= $t['id'] ?>"
                            <?= ($presel_data && $presel_data['teacher_id']==$t['id'])
                                ?'selected':'' ?>>
                            <?= htmlspecialchars($t['full_name']) ?> —
                            Rs. <?= number_format($t['monthly_salary'], 0) ?>/mo
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Month *</label>
                    <select name="salary_month" class="form-select" required>
                        <?php foreach ($months_list as $n => $name): ?>
                            <option value="<?= $n ?>"
                                <?= $n==date('n')?'selected':'' ?>>
                                <?= $name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col mb-3">
                    <label class="form-label">Year *</label>
                    <select name="salary_year" class="form-select" required>
                        <?php for ($y=date('Y'); $y>=date('Y')-2; $y--): ?>
                            <option value="<?= $y ?>"><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Amount Paid (Rs.) *</label>
                <input type="number" name="paid_amount" class="form-control"
                       step="0.01" min="1"
                       value="<?= $presel_data
                           ? $presel_data['remaining']
                           : '' ?>"
                       required>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Date *</label>
                <input type="date" name="payment_date" class="form-control"
                       value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select">
                    <option value="cash">Cash</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cheque">Cheque</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <input type="text" name="notes" class="form-control" placeholder="Optional">
            </div>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-wallet2 me-1"></i> Process Payment
            </button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>