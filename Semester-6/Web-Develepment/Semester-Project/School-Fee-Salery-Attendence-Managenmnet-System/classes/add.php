<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id    = intval($_POST['class_id']);
    $name        = trim(strtoupper($_POST['name']));
    $description = trim($_POST['description']);

    if (!$class_id || empty($name)) {
        $error = "Class and section name are required.";
    } else {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO sections (class_id, name, description) VALUES (?,?,?)"
        );
        mysqli_stmt_bind_param($stmt, "iss", $class_id, $name, $description);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?msg=added"); exit();
        } else {
            $error = "This section already exists for that class.";
        }
    }
}

$pageTitle = "Add Section";
$classes   = mysqli_query($conn, "
    SELECT * FROM classes
    ORDER BY FIELD(name, 'Nursery','Prep','KG',
    'Class 1','Class 2','Class 3','Class 4','Class 5',
    'Class 6','Class 7','Class 8','Class 9','Class 10')
");
require_once '../includes/header.php';
?>

<div class="card border-0 shadow-sm" style="max-width:480px">
    <div class="card-header bg-white fw-bold">Add New Section</div>
    <div class="card-body">
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Class *</label>
                <select name="class_id" class="form-select" required>
                    <option value="">-- Select Class --</option>
                    <?php while ($c = mysqli_fetch_assoc($classes)): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Section Name * (e.g. A, B, C)</label>
                <input type="text" name="name" class="form-control"
                       placeholder="A" maxlength="5" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description (optional)</label>
                <input type="text" name="description" class="form-control"
                       placeholder="e.g. Girls Section">
            </div>
            <button type="submit" class="btn btn-success">Save Section</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>