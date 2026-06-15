<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$error     = '';
$preselect = intval($_GET['student_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $fee_month  = intval($_POST['fee_month']);
    $fee_year   = intval($_POST['fee_year']);
    $discount   = floatval($_POST['discount'] ?? 0);

    $student = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT s.*, c.monthly_fee FROM students s
         JOIN classes c ON s.class_id = c.id
         WHERE s.id = $student_id"
    ));

    if (!$student) {
        $error = "Student not found.";
    } else {
        $exists = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT id FROM fee_vouchers
             WHERE student_id=$student_id
             AND fee_month=$fee_month
             AND fee_year=$fee_year"
        ));

        if ($exists) {
            $error = "Voucher for this period already exists.";
        } else {
            // Previous unpaid dues
            $dues = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT SUM(total_amount - paid_amount) AS dues
                 FROM fee_vouchers
                 WHERE student_id=$student_id AND status != 'paid'"
            ));
            $previous_dues = floatval($dues['dues'] ?? 0);
            $fee_amount    = $student['monthly_fee'];

            // Apply advance balance
            $advance_used  = 0;
            $advance_bal   = floatval($student['advance_balance']);

            $gross_total = ($fee_amount - $discount) + $previous_dues;

            if ($advance_bal > 0) {
                if ($advance_bal >= $gross_total) {
                    // Advance covers everything
                    $advance_used = $gross_total;
                    $total        = 0;
                } else {
                    // Advance covers partially
                    $advance_used = $advance_bal;
                    $total        = $gross_total - $advance_used;
                }
                // Deduct advance from student
                $new_advance = $advance_bal - $advance_used;
                mysqli_query($conn,
                    "UPDATE students
                     SET advance_balance=$new_advance
                     WHERE id=$student_id"
                );
            } else {
                $total = $gross_total;
            }

            // Determine initial status
            $paid_amount = $advance_used;
            $status = 'unpaid';
            if ($total <= 0) {
                $status      = 'paid';
                $paid_amount = $gross_total;
                $total       = $gross_total;
            }

            $stmt = mysqli_prepare($conn,
                "INSERT INTO fee_vouchers
                 (student_id, fee_month, fee_year, fee_amount,
                  discount, previous_dues, total_amount, paid_amount, status)
                 VALUES (?,?,?,?,?,?,?,?,?)"
            );
            mysqli_stmt_bind_param($stmt, "iiiddddss",
                $student_id, $fee_month, $fee_year,
                $fee_amount, $discount, $previous_dues,
                $total, $paid_amount, $status
            );

            if (mysqli_stmt_execute($stmt)) {
                $new_id = mysqli_insert_id($conn);
                header("Location: view.php?id=$new_id&new=1"); exit();
            } else {
                $error = "Failed to generate voucher.";
            }
        }
    }
}

$students = mysqli_query($conn,
    "SELECT s.*, c.name AS class_name FROM students s
     JOIN classes c ON s.class_id = c.id
     WHERE s.status='active'
     ORDER BY FIELD(c.name,
     'Play Group','Nursery','Prep',
     'Class 1','Class 2','Class 3','Class 4','Class 5',
     'Class 6','Class 7','Class 8','Class 9','Class 10'),
     s.full_name"
);

$months_list = ['1'=>'January','2'=>'February','3'=>'March','4'=>'April',
                '5'=>'May','6'=>'June','7'=>'July','8'=>'August',
                '9'=>'September','10'=>'October','11'=>'November','12'=>'December'];

$pageTitle = "Generate Fee Voucher";
require_once '../includes/header.php';
?>

<div class="card border-0 shadow-sm" style="max-width:520px">
    <div class="card-header bg-white fw-bold">Generate Fee Voucher</div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Select Student *</label>
                <select name="student_id" class="form-select" required>
                    <option value="">-- Select Student --</option>
                    <?php while ($s = mysqli_fetch_assoc($students)): ?>
                        <option value="<?= $s['id'] ?>"
                            <?= $s['id']==$preselect?'selected':'' ?>>
                            <?= htmlspecialchars($s['full_name']) ?> —
                            <?= htmlspecialchars($s['class_name']) ?>
                            <?= $s['roll_no'] ? '('.$s['roll_no'].')' : '' ?>
                            <?= $s['advance_balance'] > 0
                                ? '| Advance: Rs.'.number_format($s['advance_balance'],0)
                                : '' ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Month *</label>
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
                    <label class="form-label">Year *</label>
                    <select name="fee_year" class="form-select" required>
                        <?php for ($y=date('Y'); $y>=date('Y')-2; $y--): ?>
                            <option value="<?= $y ?>"><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Discount (Rs.) — optional</label>
                <input type="number" name="discount"
                       class="form-control" value="0" min="0">
            </div>
            <div class="alert alert-info small">
                <i class="bi bi-info-circle"></i>
                Previous unpaid dues +
                <strong>advance balance auto-applied</strong>.
            </div>
            <button type="submit" class="btn btn-success">
                Generate Voucher
            </button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>