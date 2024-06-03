<?php
include "config.php";

function registrasi($data){
    global $conn;
    
    // tambah fitur cek duplikasi nama, email, no. hp

    $username = mysqli_real_escape_string($conn, $data["username"]);
    $email = mysqli_real_escape_string($conn, $data["email"]);
    $no_hp = mysqli_real_escape_string($conn, $data["no_hp"]);
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $role = mysqli_real_escape_string($conn, $data["role"]);

    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, no_hp, password, role) 
            VALUES ('$username', '$email', '$no_hp', '$password', '$role')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        return true;
    } else {
        echo "Sign Up Failed: " . mysqli_error($conn);
        return false;
    }
}