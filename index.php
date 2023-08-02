<!DOCTYPE html>
<html>
<head>
<title>PHP Assignment 2</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>
<h1>CRUD Application</h1>
</header>

<main>
<section>
<h2>Create</h2>
<form action="create.php" method="post">
<input type="text" name="name" placeholder="Name">
<input type="text" name="email" placeholder="Email">
<input type="file" name="image">
<input type="submit" value="Create">
</form>
</section>

<section>
<h2>Read</h2>
<table>
<thead>
<tr>
<th>Name</th>
<th>Email</th>
<th>Image</th>
</tr>
</thead>
<tbody>
<?php
include "read.php";
?>
</tbody>
</table>
</section>

<section>
<h2>Update</h2>
<form action="update.php" method="post">
<input type="hidden" name="id" value="1">
<input type="text" name="name" placeholder="Name">
<input type="text" name="email" placeholder="Email">
<input type="file" name="image">
<input type="submit" value="Update">
</form>
</section>

<section>
<h2>Delete</h2>
<form action="delete.php" method="post">
<input type="hidden" name="id" value="1">
<input type="submit" value="Delete">
</form>
</section>
</main>

<footer>
<p>Copyright &copy; 2023</p>
</footer>

</body>
</html>


