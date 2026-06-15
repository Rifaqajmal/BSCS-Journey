<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$classes = mysqli_query($conn, "
    SELECT * FROM classes
    ORDER BY FIELD(name,
    'Play Group','Nursery','Prep',
    'Class 1','Class 2','Class 3','Class 4','Class 5',
    'Class 6','Class 7','Class 8','Class 9','Class 10')
");

$sel_class = intval($_GET['class_id'] ?? 0);
$sel_month = intval($_GET['month']    ?? date('n'));
$sel_year  = intval($_GET['year']     ?? date('Y'));

$report = null;
if ($sel_class) {
    $report = mysqli_query($conn, "
        SELECT s.full_name, s.roll_no,
               SUM(a.status='present') AS present,
               SUM(a.status='absent')  AS absent,
               SUM(a.status='leave')   AS leave,
               COUNT(a.id)             AS total_days
        FROM students s
        LEFT JOIN attendance a ON a.student_id=s.id
            AND MONTH(a.attendance_date)=$sel_month
            AND YEAR(a.attendance_date)=$sel_year
        WHERE s.class_id=$sel_class AND s.status='active'
        GROUP BY s.id
        ORDER BY s.full_name
    ");
}

$months_list = ['1'=>'January','2'=>'February','3'=>'March','4'=>'April',
                '5'=>'May','6'=>'June','7'=>'July','8'=>'August',
                '9'=>'September','10'=>'October','11'=>'November','12'=>'December'];

$pageTitle = "Attendance Report";
require_once '../includes/header.php';
?>

<style>
@media print {
    .sidebar, .topbar, .d-print-none { display:none !important; }
    body { background:white !important; }
}
</style>

<div class="card border-0 shadow-sm mb-4 d-print-none">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold">Class</label>
                <select name="class_id" class="form-select">
                    <option value="">-- Select --</option>
                    <?php while ($c = mysqli_fetch_assoc($classes)): ?>
                        <option value="<?= $c['id'] ?>"
                            <?= $c['id']==$sel_class?'selected':'' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Month</label>
                <select name="month" class="form-select">
                    <?php foreach ($months_list as $n => $name): ?>
                        <option value="<?= $n ?>"
                            <?= $n==$sel_month?'selected':'' ?>>
                            <?= $name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Year</label>
                <select name="year" class="form-select">
                    <?php for ($y=date('Y'); $y>=date('Y')-2; $y--): ?>
                        <option value="<?= $y ?>"
                            <?= $y==$sel_year?'selected':'' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">View</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" onclick="window.print()"
                        class="btn btn-outline-secondary w-100">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
        </form>
    </div>
</div>

<?php if ($sel_class && $report): ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-bold">
        Attendance Report — <?= $months_list[$sel_month] ?> <?= $sel_year ?>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Roll No</th><th>Student Name</th>
                    <th class="text-success">Present</th>
                    <th class="text-danger">Absent</th>
                    <th class="text-warning">Leave</th>
                    <th>Total Days</th>
                    <th>Attendance %</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $has = false;
            while ($r = mysqli_fetch_assoc($report)):
                $has = true;
                $pct = $r['total_days'] > 0
                    ? round(($r['present'] / $r['total_days']) * 100) : 0;
            ?>
                <tr>
                    <td><?= htmlspecialchars($r['roll_no']) ?></td>
                    <td><strong><?= htmlspecialchars($r['full_name']) ?></strong></td>
                    <td class="text-success fw-bold"><?= $r['present'] ?></td>
                    <td class="text-danger fw-bold"><?= $r['absent'] ?></td>
                    <td class="text-warning fw-bold"><?= $r['leave'] ?></td>
                    <td><?= $r['total_days'] ?></td>
                    <td>
                        <div class="progress" style="height:20px; min-width:100px">
                            <div class="progress-bar bg-<?= $pct>=75?'success':($pct>=50?'warning':'danger') ?>"
                                 style="width:<?= $pct ?>%">
                                <?= $pct ?>%
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (!$has): ?>
                <tr><td colspan="7" class="text-center text-muted py-3">
                    No data found.
                </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>