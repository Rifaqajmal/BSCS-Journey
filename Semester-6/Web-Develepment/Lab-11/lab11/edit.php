<?php include 'includes/header.php'; ?>
<?php include 'includes/db.php'; ?>

<?php

$id = $_GET['id'];

$result = mysqli_query($conn,"SELECT * FROM employees WHERE id=$id");

$data = mysqli_fetch_assoc($result);

?>

<div class="container mt-4">

<h3 class="mb-4">Edit Employee</h3>

<form method="POST">

<div class="mb-3">
<label>Full Name</label>
<input type="text" name="full_name" class="form-control"
value="<?php echo $data['full_name']; ?>">
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control"
value="<?php echo $data['email']; ?>">
</div>

<button name="update" class="btn btn-primary">Update Employee</button>

</form>

</div>

<?php

if(isset($_POST['update'])){

mysqli_query($conn,"UPDATE employees
SET full_name='{$_POST['full_name']}',
email='{$_POST['email']}'
WHERE id=$id");

header("Location:dashboard.php");

}
?>

<?php include 'includes/footer.php'; ?>