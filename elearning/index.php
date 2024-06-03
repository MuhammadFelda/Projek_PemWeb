<?php
session_start();
include 'function.php';

try {
    $usernameSession = $_SESSION['user_id'];

    if ($usernameSession == null) {
        header('Location: login.php');
        exit;
    } else {
        // Periksa peran pengguna dari database
        $sql = "SELECT * FROM users WHERE username = '$usernameSession'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $role = $row["role"];
            $user_id = $row["id"];
        } else {
            header('Location: login.php');
            exit;
        }
    }
} catch (Throwable $th) {
    echo 'Message: ' . $th->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>
    <header>
        <nav class="navbar bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                    <img src="image/logo.png" alt="Logo">
                    <h1 class="text-light">Learn-Tech</h1>
                </a>
            </div>
        </nav>
    </header>

    <main class="d-flex gap-4">
        <!-- side bar -->
        <div class="wrapper">
            <div class="sidebar">
                <!-- Profile -->
                <div>
                    <a href="profile.php" class="gap-1">
                        <i class="fa-solid fa-user"></i>
                        <span>
                            <?php 
                            echo $usernameSession; 
                            if($role == 'admin'){
                                echo" (admin)";
                            }
                            ?>
                        </span>
                    </a>
                </div>
                <!-- fitur -->
                <div>
                    <a href="#">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </div>
                <div>
                    <a href="tugas.php">
                        <i class="fas fa-list-check"></i>
                        <span>Tugas</span>
                    </a>
                </div>
                <div>
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <h1>Selamat Datang di Learn-Tech</h1>
        </div>
    </main>
</body>

</html>
