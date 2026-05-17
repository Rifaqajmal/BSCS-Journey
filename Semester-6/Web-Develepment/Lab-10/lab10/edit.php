<?php include 'db.php';

$id = $_GET['id'];
$result = mysqli_query($conn,"SELECT * FROM employees WHERE id=$id");
$data = mysqli_fetch_assoc($result);
?>

<h3>Edit Employee</h3>

<form method="POST">
<input name="full_name" value="<?php echo $data['full_name']; ?>"><br><br>
<input name="email" value="<?php echo $data['email']; ?>"><br><br>

<button name="update">Update</button>
</form>

<?php
if(isset($_POST['update'])){
mysqli_query($conn,"UPDATE employees 
SET full_name='{$_POST['full_name']}', email='{$_POST['email']}' 
WHERE id=$id");

header("Location:dashboard.php");
}
?>