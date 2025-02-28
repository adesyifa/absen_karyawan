<?php 
require 'fungsi/fungsi.php';
require 'fungsi/proses_log.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiHadir</title>
    <link rel="stylesheet" href="<?=url()?>css/style.css">
    <link rel="icon" type="image/png" href="<?=url()?>images/logo_absensi.png">
</head>
<body>

<div class="container">
    <div class="left">
        <h1>Selamat datang di platform <br><strong>SiHadir!</strong></h1>
    </div>
    <div class="right">
        <form action="" method="POST" autocomplete="off">
            <h2>Login admin</h2>
            <label for="username">Nama Pengguna</label>
            <input type="text" id="username" name="user" placeholder="Nama Pengguna" required>

            <label for="password">Kata Sandi</label>
            <input type="password" id="password" name="pass" placeholder="Kata Sandi" required>
            
            <button type="submit" name="login">Masuk</button>
            <a href="lupa_password.php" class="forgot_link">Lupa Kata Sandi?</a>


            <?php 
            if (isset($_POST['login'])) {
                $pro->login();
            } 
            ?>
        </form>
    </div>
</div>

</body>
</html>
