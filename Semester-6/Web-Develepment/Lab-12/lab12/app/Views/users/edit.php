<!DOCTYPE html>
<html>
<head>
<title>Edit User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

<h2>Edit User</h2>

<form method="post" action="<?= base_url('users/update/'.$user['id']) ?>">

<div class="mb-3">

<label>Name</label>

<input type="text"
name="name"
class="form-control"
value="<?= $user['name']; ?>">

<?php if(isset($validation)): ?>
<small class="text-danger">
<?= $validation->getError('name'); ?>
</small>
<?php endif; ?>

</div>

<div class="mb-3">

<label>Email</label>

<input type="text"
name="email"
class="form-control"
value="<?= $user['email']; ?>">

<?php if(isset($validation)): ?>
<small class="text-danger">
<?= $validation->getError('email'); ?>
</small>
<?php endif; ?>

</div>

<div class="mb-3">

<label>Phone</label>

<input type="text"
name="phone"
class="form-control"
value="<?= $user['phone']; ?>">

<?php if(isset($validation)): ?>
<small class="text-danger">
<?= $validation->getError('phone'); ?>
</small>
<?php endif; ?>

</div>

<button class="btn btn-primary">
Update User
</button>

</form>

</div>

</body>
</html>