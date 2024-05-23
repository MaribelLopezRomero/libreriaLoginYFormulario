<?php


// Por ejemplo, en una vista de registro de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController();
    $controller->register($_POST['username'], $_POST['password'], $_POST['email']);
}
?>
<!-- Formulario de registro de usuario -->
<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Register</button>
</form>
