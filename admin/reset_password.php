<?php 
require 'fungsi/fungsi.php';

$koneksi = mysqli_connect('localhost', 'root', '', 'absenkaryawan');
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$message = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $cek_token = mysqli_query($koneksi, "SELECT * FROM tb_karyawan WHERE reset_token='$token'");

    if (mysqli_num_rows($cek_token) > 0) {
        $user = mysqli_fetch_assoc($cek_token);
        
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ubah'])) {
            $new_password = md5($_POST['password']); // Enkripsi password dengan MD5 (bisa diganti dengan bcrypt)
            $update = mysqli_query($koneksi, "UPDATE tb_karyawan SET password='$new_password', reset_token=NULL WHERE reset_token='$token'");
            
            if ($update) {
                $message = "<p style='color: green;'>Kata sandi berhasil diubah! Silakan <a href='login.php'>login</a>.</p>";
            } else {
                $message = "<p style='color: red;'>Gagal mengubah kata sandi!</p>";
            }
        }
    } else {
        $message = "<p style='color: red;'>Token tidak valid atau sudah digunakan!</p>";
    }
} else {
    $message = "<p style='color: red;'>Token tidak ditemukan!</p>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Kata Sandi - SiHadir</title>
    <link rel="stylesheet" href="<?=url()?>css/style.css">
</head>
<body>
    <div class="container">
        <div class="left">
            <h1>Silakan masukkan kata sandi baru Anda.</h1>
        </div>
        <div class="right">
            <form method="POST">
                <h2>Ubah Kata Sandi</h2>
                <label for="password">Kata Sandi Baru</label>
                <input type="password" name="password" id="password" required>
                <button type="submit" name="ubah">Ubah Kata Sandi</button>
                <a href="login.php" class="forgot_link">Kembali ke Login</a>
            </form>
            <?= $message; ?>
        </div>
    </div>
</body>
</html>
