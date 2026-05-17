<?php
$conn = mysqli_connect("localhost","root","","ecommerce_db");

if(!$conn){
    die("Connection failed");
}

$sql = "SELECT * FROM products";
$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Products</title>
</head>
<body>

<h2>Products List</h2>

<table border="1">
<tr>
<th>ID</th>
<th>SKU</th>
<th>Name</th>
<th>Price</th>
<th>Stock</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($result)){
?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['sku']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['price']; ?></td>
<td><?php echo $row['stock']; ?></td>
</tr>

<?php
}
?>

</table>

</body>
</html>