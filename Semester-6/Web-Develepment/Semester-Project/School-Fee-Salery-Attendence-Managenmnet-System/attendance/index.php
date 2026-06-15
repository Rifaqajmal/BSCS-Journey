<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$success = '';
$classes = mysqli_query($conn, "
    SELECT * FROM classes
    ORDER BY FIELD(name,
    'Play Group','Nursery','Prep',
    'Class 1','Class 2','Class 3','Class 4','Class 5',
    'Class 6','Class 7','Class 8','Class 9','Class 10')
");

$sel_class = intval($_GET['class_id'] ?? 0);
$sel_date  = $_GET['date'] ?? date('Y-m-d');

$students = [];
if ($sel_class) {
    $students = mysqli_query($conn, "
        SELECT s.*, a.status AS att_status
        FROM students s
        LEFT JOIN attendance a
            ON a.student_id=s.id AND a.attendance_date='$sel_date'
        WHERE s.class_id=$sel_class AND s.status='active'
        ORDER BY s.full_name
    ");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date     = $_POST['date'];
    $class_id = intval($_POST['class_id']);
    $statuses = $_POST['status'] ?? [];

    foreach ($statuses as $student_id => $status) {
        $student_id = intval($student_id);
        mysqli_query($conn, "
            INSERT INTO attendance
            (student_id, class_id, attendance_date, status)
            VALUES ($student_id, $class_id, '$date', '$status')
            ON DUPLICATE KEY UPDATE status='$status'
        ");
    }
    $success = "Attendance saved for " . date('d M Y', strtotime($date)) . "!";
}

$pageTitle = "Attendance";
require_once '../includes/header.php';
?>

<?php if ($success): ?>
    <div class="alert alert-success">
        <i class="bi bi-check-circle"></i> <?= $success ?>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold">Select Class & Date</div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Class *</label>
                <select name="class_id" class="form-select" required>
                    <option value="">-- Select Class --</option>
                    <?php while ($c = mysqli_fetch_assoc($classes)): ?>
                        <option value="<?= $c['id'] ?>"
                            <?= $c['id']==$sel_class?'selected':'' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Date *</label>
                <input type="date" name="date" class="form-control"
                       value="<?= $sel_date ?>"
                       max="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-search me-1"></i> Load Students
                </button>
            </div>
        </form>
    </div>
</div>

<?php if ($sel_class && $students): ?>
<?php
$student_list = [];
while ($s = mysqli_fetch_assoc($students)) $student_list[] = $s;
?>

<?php if (empty($student_list)): ?>
    <div class="alert alert-info">No active students in this class.</div>
<?php else: ?>

<form method="POST">
    <input type="hidden" name="date"     value="<?= $sel_date ?>">
    <input type="hidden" name="class_id" value="<?= $sel_class ?>">

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-bold">
                Mark Attendance — <?= date('d M Y', strtotime($sel_date)) ?>
            </span>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-success"
                        onclick="markAll('present')">All Present</button>
                <button type="button" class="btn btn-sm btn-danger"
                        onclick="markAll('absent')">All Absent</button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Roll No</th><th>Student Name</th>
                        <th class="text-success">Present</th>
                        <th class="text-danger">Absent</th>
                        <th class="text-warning">Leave</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($student_list as $i => $s): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($s['roll_no']) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($s['full_name']) ?></strong>
                        </td>
                        <td>
                            <input type="radio"
                                   name="status[<?= $s['id'] ?>]"
                                   value="present"
                                   class="form-check-input att-radio"
                                   <?= (!$s['att_status'] || $s['att_status']==='present')
                                       ? 'checked' : '' ?>>
                        </td>
                        <td>
                            <input type="radio"
                                   name="status[<?= $s['id'] ?>]"
                                   value="absent"
                                   class="form-check-input att-radio"
                                   <?= $s['att_status']==='absent'?'checked':'' ?>>
                        </td>
                        <td>
                            <input type="radio"
                                   name="status[<?= $s['id'] ?>]"
                                   value="leave"
                                   class="form-check-input att-radio"
                                   <?= $s['att_status']==='leave'?'checked':'' ?>>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-save me-1"></i> Save Attendance
            </button>
        </div>
    </div>
</form>
<?php endif; ?>
<?php endif; ?>

<script>
function markAll(status) {
    document.querySelectorAll('.att-radio').forEach(r => {
        if (r.value === status) r.checked = true;
    });
}
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        a.style.transition = 'opacity 0.5s';
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 500);
    });
}, 3000);
</script>

<?php require_once '../includes/footer.php'; ?>