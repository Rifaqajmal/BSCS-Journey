<!DOCTYPE html>
<html>
<head>
<title>User Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

<h2>User Management System</h2>

<a href="<?= base_url('users/create') ?>" class="btn btn-success mb-3">
    Add User
</a>

<table class="table table-bordered table-striped">

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Actions</th>
</tr>

<?php foreach($users as $user): ?>

<tr>

<td><?= $user['id']; ?></td>
<td><?= $user['name']; ?></td>
<td><?= $user['email']; ?></td>
<td><?= $user['phone']; ?></td>

<td>

<a href="<?= base_url('users/edit/'.$user['id']) ?>"
class="btn btn-primary btn-sm">
Edit
</a>

<a href="<?= base_url('users/delete/'.$user['id']) ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Are you sure?')">
Delete
</a>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</body>
</html>