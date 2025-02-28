<?php 
$koneksi = mysqli_connect('localhost', 'root', '', 'absenkaryawan');
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
require 'fungsi/fungsi.php';
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset'])) {
    $email = isset($_POST['kontak']) ? $_POST['kontak'] : '';

    if (!empty($email)) {
        $cek_email = mysqli_query($koneksi, "SELECT * FROM tb_karyawan WHERE kontak='$email'");

        if (mysqli_num_rows($cek_email) > 0) {
            $user = mysqli_fetch_assoc($cek_email);
            $token = md5(uniqid(rand(), true)); // Token unik untuk reset password
            
            // Simpan token ke database
            $update = mysqli_query($koneksi, "UPDATE tb_karyawan SET reset_token='$token' WHERE kontak='$email'");

            if ($update) {
                $resetLink = "http://localhost/absenkaryawan-master/karyawan/reset_password.php?token=$token";

                try {
                    // Konfigurasi SMTP (Gunakan SMTP Gmail atau lainnya)
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'adesyifae1e122083@gmail.com'; // Ganti dengan email Anda
                    $mail->Password = 'unat lxsp wgsw wcst';   // Ganti dengan App Password dari Google
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Pengirim dan penerima email
                    $mail->setFrom('from@lupasandi.com', 'SiHadir');
                    $mail->addAddress($email, $user['nama']); // Gunakan nama dari database

                    // Konten email
                    $mail->isHTML(true);
                    $mail->Subject = 'Reset Kata Sandi';
                    $mail->Body    = "Klik link berikut untuk mereset kata sandi Anda: <br>
                                     <a href='$resetLink'>$resetLink</a>";

                    $mail->send();
                    $message = "<div class='message success'>Link reset telah dikirim ke email Anda!</div>";
                } catch (Exception $e) {
                    $message = "<div class='message error'>Gagal mengirim email. Error: {$mail->ErrorInfo}</div>";
                }
            } else {
                $message = "<div class='message error'>Gagal menyimpan token reset!</div>";
            }
        } else {
            $message = "<div class='message error'>Email tidak ditemukan!</div>";
        }
    } else {
        $message = "<div class='message error'>Harap masukkan email Anda!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - SiHadir</title>
    <link rel="stylesheet" href="<?=url()?>css/style.css">
</head>
<body>
    <div class="container">
        <div class="left">
            <h1>Masukkan email Anda untuk mereset kata sandi.</h1>
        </div>
        <div class="right">
            <form action="" method="POST">
                <h2>Reset Kata Sandi</h2>
                <label for="kontak">Email</label>
                <input type="email" id="kontak" name="kontak" placeholder="Masukkan email Anda" required>
                <button type="submit" name="reset">Kirim Link Reset</button>
                <a href="login.php" class="forgot_link">Kembali ke Login</a>
            </form>
            <?= $message; ?>
        </div>
    </div>
</body>
</html>
