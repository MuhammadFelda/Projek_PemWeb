<?php
session_start();
include "function.php";

if (isset($_POST['regis'])) {
    $username = $_POST["username"];
    if (registrasi($_POST)) {
        $_SESSION["user_id"] = $username;
        header("location: index.php");
        exit;
    } else {
        echo "Sign Up Failed!";
    }
}
?>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>
    <div class="card mx-auto">
        <div class="card-header bg-primary text-white">
            <h1>Sign Up</h1>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label for="id" class="form-label">Username</label>
                    <input require type="text" class="form-control" id="username" name="username" placeholder="username">
                </div>
                <div class="mb-3">
                    <label for="id" class="form-label">Email</label>
                    <input require type="email" class="form-control" id="email" name="email" placeholder="email">
                </div>
                <div class="mb-3">
                    <label for="id" class="form-label">No. Handphone</label>
                    <input require type="text" class="form-control" id="no_hp" name="no_hp" placeholder="No. Handphone">
                </div>
                <div class="mb-3">
                    <label for="id" class="form-label">Password</label>
                    <input require type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-select">
                        <option value="user" <?php if (isset($role) && $role == "user") echo "selected"; ?>>User</option>
                        <option value="admin" <?php if (isset($role) && $role == "admin") echo "selected"; ?>>Admin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <a href="login.php" class="link">Have an acount?</a>
                </div>
                <button type="submit" class="btn btn-primary" name="regis">Sign Up</button>
            </form>
        </div>
    </div>
</body>

</html>