<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$success = '';
$errors  = [];
$generated = 0;
$skipped   = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fee_month = intval($_POST['fee_month']);
    $fee_year  = intval($_POST['fee_year']);

    // Get all active students with their class fee
    $students = mysqli_query($conn, "
        SELECT s.*, c.monthly_fee
        FROM students s
        JOIN classes c ON s.class_id = c.id
        WHERE s.status = 'active'
    ");

    while ($student = mysqli_fetch_assoc($students)) {
        $student_id = $student['id'];

        // Check if voucher already exists
        $exists = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT id FROM fee_vouchers
             WHERE student_id=$student_id
             AND fee_month=$fee_month
             AND fee_year=$fee_year"
        ));

        if ($exists) {
            $skipped++;
            continue;
        }

        // Previous dues
        $dues = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT SUM(total_amount - paid_amount) AS dues
             FROM fee_vouchers
             WHERE student_id=$student_id AND status != 'paid'"
        ));
        $previous_dues = floatval($dues['dues'] ?? 0);
        $fee_amount    = $student['monthly_fee'];

        // Apply advance balance
        $advance_used = 0;
        $advance_bal  = floatval($student['advance_balance']);
        $gross_total  = $fee_amount + $previous_dues;

        if ($advance_bal > 0) {
            if ($advance_bal >= $gross_total) {
                $advance_used = $gross_total;
                $total        = 0;
            } else {
                $advance_used = $advance_bal;
                $total        = $gross_total - $advance_used;
            }
            $new_advance = $advance_bal - $advance_used;
            mysqli_query($conn,
                "UPDATE students
                 SET advance_balance=$new_advance
                 WHERE id=$student_id"
            );
        } else {
            $total = $gross_total;
        }

        $paid_amount = $advance_used;
        $status      = 'unpaid';
        if ($total <= 0) {
            $status      = 'paid';
            $paid_amount = $gross_total;
            $total       = $gross_total;
        }

        $stmt = mysqli_prepare($conn,
            "INSERT INTO fee_vouchers
             (student_id, fee_month, fee_year, fee_amount,
              previous_dues, total_amount, paid_amount, status)
             VALUES (?,?,?,?,?,?,?,?)"
        );
        mysqli_stmt_bind_param($stmt, "iiidddds",
            $student_id, $fee_month, $fee_year,
            $fee_amount, $previous_dues, $total,
            $paid_amount, $status
        );

        if (mysqli_stmt_execute($stmt)) {
            $generated++;
        } else {
            $errors[] = "Failed for: " . $student['full_name'];
        }
    }

    $success = "Done! Generated: $generated vouchers. Skipped (already exist): $skipped.";
}

$months_list = ['1'=>'January','2'=>'February','3'=>'March','4'=>'April',
                '5'=>'May','6'=>'June','7'=>'July','8'=>'August',
                '9'=>'September','10'=>'October','11'=>'November','12'=>'December'];

$pageTitle = "Bulk Fee Generation";
require_once '../includes/header.php';
?>

<?php if ($success): ?>
    <div class="alert alert-success">
        <i class="bi bi-check-circle me-2"></i><?= $success ?>
        <?php if (!empty($errors)): ?>
            <hr>
            <ul class="mb-0 small">
                <?php foreach ($errors as $e): ?>
                    <li><?= $e ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <a href="/04. eaglets_school/fees/index.php"
       class="btn btn-success mb-4">
        <i class="bi bi-receipt me-1"></i> View All Vouchers
    </a>
<?php endif; ?>

<div class="card border-0 shadow-sm" style="max-width:500px">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-lightning-charge me-2"></i>
        Bulk Fee Generation
    </div>
    <div class="card-body">
        <div class="alert alert-info small">
            <i class="bi bi-info-circle me-1"></i>
            This will generate fee vouchers for <strong>all active students</strong>
            for the selected month. Already existing vouchers will be skipped.
            Advance balances will be auto-applied.
        </div>

        <form method="POST">
            <div class="row">
                <div class="col mb-3">
                    <label class="form-label fw-bold">Month *</label>
                    <select name="fee_month" class="form-select" required>
                        <?php foreach ($months_list as $n => $name): ?>
                            <option value="<?= $n ?>"
                                <?= $n==date('n')?'selected':'' ?>>
                                <?= $name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col mb-3">
                    <label class="form-label fw-bold">Year *</label>
                    <select name="fee_year" class="form-select" required>
                        <?php for ($y=date('Y'); $y>=date('Y')-2; $y--): ?>
                            <option value="<?= $y ?>"><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <!-- Preview -->
            <?php
            $total_students = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT COUNT(*) AS c FROM students WHERE status='active'"
            ))['c'];
            ?>
            <div class="card bg-light border-0 mb-3 p-3">
                <div class="small text-muted">Will generate for:</div>
                <div class="fs-5 fw-bold">
                    <?= $total_students ?> Active Students
                </div>
            </div>

            <button type="submit"
                    class="btn btn-success w-100 py-2"
                    onclick="return confirm(
                        'Generate fee vouchers for ALL <?= $total_students ?> active students for selected month?'
                    )">
                <i class="bi bi-lightning-charge me-2"></i>
                Generate Fee for All Students
            </button>
            <a href="/04. eaglets_school/dashboard.php"
               class="btn btn-secondary w-100 mt-2">Cancel</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>