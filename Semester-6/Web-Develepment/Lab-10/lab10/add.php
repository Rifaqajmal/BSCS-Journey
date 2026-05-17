<?php include 'db.php'; ?>

<h3>Add Employee</h3>

<form method="POST" enctype="multipart/form-data">
<input type="file" name="photo"><br><br>
<input name="cnic" placeholder="CNIC"><br><br>
<input name="full_name" placeholder="Full Name"><br><br>
<input name="father_name" placeholder="Father Name"><br><br>
<input name="email" placeholder="Email"><br><br>
<input name="phone" placeholder="Phone"><br><br>

<button name="save">Save</button>
</form>

<?php
if(isset($_POST['save'])){

$photo = $_FILES['photo']['name'];
$tmp = $_FILES['photo']['tmp_name'];

move_uploaded_file($tmp, "uploads/".$photo);

mysqli_query($conn,"INSERT INTO employees(cnic,full_name,father_name,email,phone,photo)
VALUES('{$_POST['cnic']}','{$_POST['full_name']}','{$_POST['father_name']}','{$_POST['email']}','{$_POST['phone']}','$photo')");

header("Location:dashboard.php");
}
?>