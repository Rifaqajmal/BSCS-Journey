<?php include 'includes/header.php'; ?>
<?php include 'includes/db.php'; ?>

<?php

$result = mysqli_query($conn,"SELECT * FROM employees ORDER BY id DESC LIMIT 1");

$row = mysqli_fetch_assoc($result);

?>

<h3 class="mb-4">User Profile</h3>

<div class="card p-4">

<img src="uploads/<?php echo $row['photo']; ?>" width="120" class="mb-3">

<h4><?php echo $row['full_name']; ?></h4>

<p><b>CNIC:</b> <?php echo $row['cnic']; ?></p>

<p><b>Father Name:</b> <?php echo $row['father_name']; ?></p>

<p><b>Email:</b> <?php echo $row['email']; ?></p>

<p><b>Phone:</b> <?php echo $row['phone']; ?></p>

</div>

<?php include 'includes/footer.php'; ?>