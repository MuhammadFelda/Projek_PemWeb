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
        $sql = "SELECT role FROM users WHERE username = '$usernameSession'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $role = $row["role"];
        } else {
            header('Location: login.php');
            exit;
        }
    }
} catch (Throwable $th) {
    echo 'Message: ' . $th->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $np_hp = $_POST['no_hp'];

    $sql = "UPDATE users SET username='$username', email='$email', no_hp='$np_hp' WHERE username='$usernameSession'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['user_id'] = $username;
        $usernameSession = $username;
        $message = "Data updated successfully!";
    } else {
        $message = "Error updating data: " . mysqli_error($conn);
    }
}

$sql = "SELECT * FROM users WHERE username = '$usernameSession'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);
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
                <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
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
                    <a href="#" class="gap-1">
                        <i class="fa-solid fa-user"></i>
                        <span>
                            <?php 
                            echo htmlspecialchars($data["username"]); 
                            
                            if($role == 'admin'){
                                echo " (admin)";
                            }
                            ?>
                        </span>
                    </a>
                </div>
                <div>
                    <a href="index.php">
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
            <div class="card">
                <div class="card-body">
                    <h2>Profile</h2>
                    <br>
                    <?php if (!empty($message)) : ?>
                        <div class="alert alert-success">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($data['no_hp']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
