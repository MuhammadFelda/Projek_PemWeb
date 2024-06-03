<?php
session_start();
include 'function.php';

$error = "";
$sukses = "";

try {
    $usernameSession = $_SESSION['user_id'];

    if ($usernameSession == null) {
        header('Location: login.php');
        exit;
    } else {
        $sql = "SELECT * FROM users WHERE username = '$usernameSession'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
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

// Tambah tugas (Admin)
if (isset($_POST['add_task']) && $role == 'admin') {
    $task_title = $_POST['task_title'];
    $task_description = $_POST['task_description'];
    $sql = "INSERT INTO assignments (title, description) VALUES ('$task_title', '$task_description')";
    if (mysqli_query($conn, $sql)) {
        $sukses = "Tugas berhasil ditambahkan!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Unggah tugas (User)
if (isset($_POST['upload_task']) && $role == 'user') {
    $assignment_id = $_POST['assignment_id'];
    $file = $_FILES['file'];
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $photo = $_FILES['photo'];
    $photoName = $_FILES['photo']['name'];
    $photoTmpName = $_FILES['photo']['tmp_name'];
    $photoSize = $_FILES['photo']['size'];
    $photoError = $_FILES['photo']['error'];
    $photoType = $_FILES['photo']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $photoExt = explode('.', $photoName);
    $photoActualExt = strtolower(end($photoExt));

    $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx');

    if (in_array($fileActualExt, $allowed) && in_array($photoActualExt, $allowed)) {
        if ($fileError === 0 && $photoError === 0) {
            if ($fileSize < 1000000 && $photoSize < 1000000) {
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = 'files/' . $fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);

                $photoNameNew = uniqid('', true) . "." . $photoActualExt;
                $photoDestination = 'files/' . $photoNameNew;
                move_uploaded_file($photoTmpName, $photoDestination);

                $sql = "INSERT INTO submissions (assignment_id, user_id, file_path, photo_path) VALUES ('$assignment_id', '$user_id', '$fileDestination', '$photoDestination')";
                if (mysqli_query($conn, $sql)) {
                    $sukses = "Tugas berhasil diunggah!";
                } else {
                    $error = "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            } else {
                $error = "Ukuran file terlalu besar!";
            }
        } else {
            $error = "Ada masalah saat mengunggah file!";
        }
    } else {
        $error = "Tipe file tidak diizinkan!";
    }
}

// Menilai tugas (Admin)
if (isset($_POST['grade_task']) && $role == 'admin') {
    $submission_id = $_POST['submission_id'];
    $grade = $_POST['grade'];
    $sql = "UPDATE submissions SET grade='$grade' WHERE id='$submission_id'";
    if (mysqli_query($conn, $sql)) {
        $sukses = "Tugas berhasil dinilai!";
    } else {
        $error = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
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
    <title>Learn-Tech</title>
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
                    <a href="profile.php" class="gap-1 h-100">
                        <i class="fa-solid fa-user"></i>
                        <span>
                            <?php
                            echo $usernameSession;
                            if ($role == 'admin') {
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
                    <a href="#">
                        <i class="fas fa-list-check"></i>
                        <span>Tugas</span>
                    </a>
                </div>
                <!-- Logout -->
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
            <?php if ($role == 'admin') { ?>
                <h2>Halaman Tugas</h2>

                <?php if ($sukses) : ?>
                    <div class="alert alert-success w-25" role="alert">
                        <p><?php echo $sukses ?></p>
                    </div>
                <?php endif; ?>
                <?php if ($error) : ?>
                    <div class="alert alert-danger w-25" role="alert">
                        <p><?php echo $error ?></p>
                    </div>
                <?php endif; ?>

                <!-- Button modal tambah tugas -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_task">
                    Tambah Tugas
                </button>

                <!-- Modal -->
                <div class="modal fade" id="modal_add_task" tabindex="-1" aria-labelledby="modal_task" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modal_task">Tambah Tugas</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="task_title" class="form-label">Judul Tugas</label>
                                        <input type="text" class="form-control" id="task_title" name="task_title" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="task_description" class="form-label">Deskripsi Tugas</label>
                                        <textarea class="form-control" id="task_description" name="task_description" rows="3" required></textarea>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="add_task">Tambah Tugas</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div><br><br>

                <h2>Tugas Users</h2>
                <?php
                $sql = "SELECT submissions.*, users.username, assignments.title 
                        FROM submissions 
                        JOIN users ON submissions.user_id = users.id 
                        JOIN assignments ON submissions.assignment_id = assignments.id";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <div class="tugas_container">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                echo "<p>Nama : " . $row['username'] . "</p>";
                                echo "<p>Judul Tugas : " . $row['title'] . "</p>";
                                echo "<p>File: <a href='" . $row['file_path'] . "' target='_blank'>Unduh</a></p>";
                                echo "<p>Foto: <a href='" . $row['photo_path'] . "' target='_blank'>Lihat</a></p>";
                                echo "<form method='post'>";
                                echo "<input type='hidden' name='submission_id' value='" . $row['id'] . "'>";
                                echo "<div class='mb-3'>";
                                echo "<label for='grade' class='form-label'>Nilai</label>";
                                echo "<input type='number' class='form-control' id='grade' name='grade' min='0' max='100' required>";
                                echo "</div>";
                                echo "<button type='submit' class='btn btn-primary' name='grade_task'>Nilai</button>";
                                echo "</form>";
                                ?>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>

            <?php } else if ($role == 'user') { ?>
                <h2>Tugas Mahasiswa</h2>

                <?php if ($sukses) : ?>
                    <div class="alert alert-success w-25" role="alert">
                        <p><?php echo $sukses ?></p>
                    </div>
                <?php endif; ?>
                <?php if ($error) : ?>
                    <div class="alert alert-danger w-25" role="alert">
                        <p><?php echo $error ?></p>
                    </div>
                <?php endif; ?>

                <?php
                $sql = "SELECT a.*, s.file_path, s.photo_path, s.grade 
                        FROM assignments a 
                        LEFT JOIN submissions s 
                        ON a.id = s.assignment_id AND s.user_id = '$user_id'";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <div class="tugas_container">
                        <div class="card">
                            <div class="card-header">
                                <h3 style="font-size: 20px;"><?php echo $row["title"] ?></h3>
                            </div>
                            <div class="card-body">
                                <p><?php echo $row["description"] ?></p>
                                <?php
                                if ($row['file_path']) {
                                    echo "<p>File: <a href='" . $row['file_path'] . "' target='_blank'>Unduh</a></p>";
                                    echo "<p>Foto: <a href='" . $row['photo_path'] . "' target='_blank'>Lihat</a></p>";
                                    if ($row['grade'] != 0) {
                                        echo "<p>Nilai: " . $row['grade'] . "</p>";
                                    }
                                } else {
                                    echo "<p>Anda belum mengumpulkan tugas ini.</p>";
                                }
                                ?>
                            </div>
                            <div class="card-footer">
                                <?php if (!$row['file_path']) { ?>
                                    <!-- Button modal untuk upload -->
                                    <button type="button" class="btn btn-primary text-light" data-bs-toggle="modal" data-bs-target="#modal_upload_<?php echo $row['id']; ?>">
                                        Upload
                                    </button>
                                <?php } ?>

                                <!-- Modal upload -->
                                <div class="modal fade" id="modal_upload_<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="modal_up_<?php echo $row['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="modal_up_<?php echo $row['id']; ?>">Upload Tugas</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" enctype="multipart/form-data">
                                                    <input type="hidden" name="assignment_id" value="<?php echo $row['id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="file" class="form-label">Unggah File Tugas</label>
                                                        <input type="file" class="form-control" id="file" name="file" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="photo" class="form-label">Unggah Foto Tugas</label>
                                                        <input type="file" class="form-control" id="photo" name="photo" required>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary" name="upload_task">Submit</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </main>
</body>

</html>