<?php include 'includes/header.php'; ?>
<?php include 'includes/db.php'; ?>

<div class="container mt-4">

<h3 class="mb-4">Add Employee</h3>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label>Photo</label>
<input type="file" name="photo" class="form-control">
</div>

<div class="mb-3">
<label>CNIC</label>
<input type="text" name="cnic" class="form-control" placeholder="Enter CNIC">
</div>

<div class="mb-3">
<label>Full Name</label>
<input type="text" name="full_name" class="form-control" placeholder="Enter Full Name">
</div>

<div class="mb-3">
<label>Father Name</label>
<input type="text" name="father_name" class="form-control" placeholder="Enter Father Name">
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" placeholder="Enter Email">
</div>

<div class="mb-3">
<label>Phone</label>
<input type="text" name="phone" class="form-control" placeholder="Enter Phone">
</div>

<button name="save" class="btn btn-success">Save Employee</button>

</form>

</div>

<?php

if(isset($_POST['save'])){
    if(empty($_POST['full_name']) || empty($_POST['email'])){

echo "<div class='alert alert-danger'>Name and Email are required</div>";

}
else{

$photo = $_FILES['photo']['name'];
$tmp = $_FILES['photo']['tmp_name'];

move_uploaded_file($tmp, "uploads/".$photo);

mysqli_query($conn,"INSERT INTO employees(cnic,full_name,father_name,email,phone,photo)
VALUES('{$_POST['cnic']}','{$_POST['full_name']}','{$_POST['father_name']}','{$_POST['email']}','{$_POST['phone']}','$photo')");

header("Location:dashboard.php");

}
}
?>

<?php include 'includes/footer.php'; ?>