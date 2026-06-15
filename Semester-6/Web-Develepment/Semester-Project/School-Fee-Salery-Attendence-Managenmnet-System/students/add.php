<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roll_no        = trim($_POST['roll_no']);
    $full_name      = trim($_POST['full_name']);
    $father_name    = trim($_POST['father_name']);
    $phone          = trim($_POST['phone']);
    $address        = trim($_POST['address']);
    $dob            = $_POST['date_of_birth'];
    $gender         = $_POST['gender'];
    $class_id       = intval($_POST['class_id']);
    $admission_date = $_POST['admission_date'];

    if (empty($full_name) || !$class_id || empty($admission_date)) {
        $error = "Name, class and admission date are required.";
    } else {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO students
             (roll_no, full_name, father_name, phone, address,
              date_of_birth, gender, class_id, admission_date)
             VALUES (?,?,?,?,?,?,?,?,?)"
        );
        mysqli_stmt_bind_param($stmt, "sssssssis",
            $roll_no, $full_name, $father_name, $phone, $address,
            $dob, $gender, $class_id, $admission_date
        );
        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?msg=added"); exit();
        } else {
            $error = "Roll number already exists or invalid data.";
        }
    }
}

$pageTitle = "Add Student";
$classes   = mysqli_query($conn, "
    SELECT * FROM classes
    ORDER BY FIELD(name,
    'Play Group','Nursery','Prep',
    'Class 1','Class 2','Class 3','Class 4','Class 5',
    'Class 6','Class 7','Class 8','Class 9','Class 10')
");
require_once '../includes/header.php';
?>

<div class="card border-0 shadow-sm" style="max-width:650px">
    <div class="card-header bg-white fw-bold">Add New Student</div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Roll Number</label>
                    <input type="text" name="roll_no" class="form-control"
                           placeholder="e.g. 2024-001"
                           value="<?= htmlspecialchars($_POST['roll_no'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="full_name" class="form-control"
                           value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                           required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Father Name</label>
                    <input type="text" name="father_name" class="form-control"
                           value="<?= htmlspecialchars($_POST['father_name'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control"
                           value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Class *</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">-- Select Class --</option>
                        <?php while ($c = mysqli_fetch_assoc($classes)): ?>
                            <option value="<?= $c['id'] ?>">
                                <?= htmlspecialchars($c['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Admission Date *</label>
                    <input type="date" name="admission_date" class="form-control"
                           value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control"
                           value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-success">Save Student</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>