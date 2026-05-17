<!DOCTYPE html>
<html>
<head>
<title>Add User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

<h2>Add User</h2>

<form method="post" action="<?= base_url('users/store') ?>">

<div class="mb-3">
<label>Name</label>

<input type="text" name="name" class="form-control">

<?php if(isset($validation)): ?>
<small class="text-danger">
<?= $validation->getError('name'); ?>
</small>
<?php endif; ?>

</div>

<div class="mb-3">
<label>Email</label>

<input type="text" name="email" class="form-control">

<?php if(isset($validation)): ?>
<small class="text-danger">
<?= $validation->getError('email'); ?>
</small>
<?php endif; ?>

</div>

<div class="mb-3">
<label>Phone</label>

<input type="text" name="phone" class="form-control">

<?php if(isset($validation)): ?>
<small class="text-danger">
<?= $validation->getError('phone'); ?>
</small>
<?php endif; ?>

</div>

<button class="btn btn-success">
Save User
</button>

</form>

</div>

</body>
</html>