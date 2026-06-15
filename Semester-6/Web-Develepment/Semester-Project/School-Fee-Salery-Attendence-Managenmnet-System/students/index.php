<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
requireLogin();

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM students WHERE id=$id");
    header("Location: index.php?msg=deleted"); exit();
}

$filter_class = intval($_GET['class_id'] ?? 0);
$search       = trim($_GET['search'] ?? '');

$where = "WHERE 1=1";
if ($filter_class) $where .= " AND s.class_id=$filter_class";
if ($search)       $where .= " AND (s.full_name LIKE '%".mysqli_real_escape_string($conn,$search)."%'
                               OR s.roll_no LIKE '%".mysqli_real_escape_string($conn,$search)."%'
                               OR s.father_name LIKE '%".mysqli_real_escape_string($conn,$search)."%'
                               OR s.phone LIKE '%".mysqli_real_escape_string($conn,$search)."%')";

$students = mysqli_query($conn, "
    SELECT s.*, c.name AS class_name
    FROM students s
    JOIN classes c ON s.class_id = c.id
    $where
    ORDER BY FIELD(c.name,
    'Play Group','Nursery','Prep',
    'Class 1','Class 2','Class 3','Class 4','Class 5',
    'Class 6','Class 7','Class 8','Class 9','Class 10'),
    s.full_name
");

$total = mysqli_num_rows($students);

$classes = mysqli_query($conn, "
    SELECT * FROM classes
    ORDER BY FIELD(name,
    'Play Group','Nursery','Prep',
    'Class 1','Class 2','Class 3','Class 4','Class 5',
    'Class 6','Class 7','Class 8','Class 9','Class 10')
");

$pageTitle = "Students";
require_once '../includes/header.php';
?>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">Done successfully.</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">All Students
        <span class="badge bg-success ms-2"><?= $total ?></span>
    </h5>
    <a href="add.php" class="btn btn-success btn-sm">
        <i class="bi bi-plus"></i> Add Student
    </a>
</div>

<!-- Search + Filter -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <!-- Search -->
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search"
                           class="form-control"
                           placeholder="Search by name, roll no, father, phone..."
                           value="<?= htmlspecialchars($search) ?>">
                    <?php if ($search): ?>
                        <a href="index.php<?= $filter_class ? '?class_id='.$filter_class : '' ?>"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Class Filter -->
            <div class="col-md-4">
                <select name="class_id" class="form-select"
                        onchange="this.form.submit()">
                    <option value="">All Classes</option>
                    <?php while ($c = mysqli_fetch_assoc($classes)): ?>
                        <option value="<?= $c['id'] ?>"
                            <?= $c['id']==$filter_class?'selected':'' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-search me-1"></i> Search
                </button>
                <?php if ($search || $filter_class): ?>
                    <a href="index.php" class="btn btn-outline-secondary">
                        Clear
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Roll No</th><th>Name</th><th>Father Name</th>
                    <th>Class</th><th>Phone</th>
                    <th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $has = false;
            mysqli_data_seek($students, 0);
            while ($s = mysqli_fetch_assoc($students)):
                $has = true;
            ?>
                <tr>
                    <td><?= htmlspecialchars($s['roll_no']) ?></td>
                    <td><strong><?= htmlspecialchars($s['full_name']) ?></strong></td>
                    <td><?= htmlspecialchars($s['father_name']) ?></td>
                    <td><?= htmlspecialchars($s['class_name']) ?></td>
                    <td><?= htmlspecialchars($s['phone']) ?></td>
                    <td>
                        <span class="badge bg-<?= $s['status']==='active'?'success':'secondary' ?>">
                            <?= ucfirst($s['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="view.php?id=<?= $s['id'] ?>"
                           class="btn btn-sm btn-outline-success">View</a>
                        <a href="edit.php?id=<?= $s['id'] ?>"
                           class="btn btn-sm btn-outline-primary">Edit</a>
                        <a href="index.php?delete=<?= $s['id'] ?>"
                           onclick="return confirm('Delete this student?')"
                           class="btn btn-sm btn-outline-danger">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (!$has): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <?php if ($search): ?>
                            <i class="bi bi-search me-2"></i>
                            No students found for "<strong><?= htmlspecialchars($search) ?></strong>"
                        <?php else: ?>
                            No students found.
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>