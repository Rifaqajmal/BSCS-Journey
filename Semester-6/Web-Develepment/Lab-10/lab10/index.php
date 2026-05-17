<?php include 'db.php'; ?>

<h2>Employee List</h2>
<a href="add.php">Add Employee</a>

<table border="1">
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Action</th>
</tr>

<?php
$result = mysqli_query($conn,"SELECT * FROM employees");

while($row = mysqli_fetch_assoc($result)){
echo "<tr>
<td>{$row['id']}</td>
<td>{$row['full_name']}</td>
<td>{$row['email']}</td>
<td>
<a href='edit.php?id={$row['id']}'>Edit</a>
<a href='delete.php?id={$row['id']}'>Delete</a>
</td>
</tr>";
}
?>
</table>