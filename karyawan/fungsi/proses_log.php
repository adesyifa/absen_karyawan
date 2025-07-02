<?php 

class login_web
{
	public $koneksi;
	public function login()
	{
		$this->koneksi;
		$user = $_POST['user'];
		$pass = md5($_POST['pass']);

		// proses masuk
		$login = mysqli_query($this->koneksi, "
		    SELECT * FROM tb_karyawan 
		    WHERE (username = '$user' OR nama = '$user' OR kontak = '$user')
		    AND password = '$pass'
		");
		$cek = mysqli_num_rows($login);
		$r = mysqli_fetch_array($login);
		// jika ketemu

		if ($cek > 0) {
			
			session_start();
			$_SESSION['idabsen2'] = $r['id'];
			$_SESSION['adm'] = $r; // <-- Tambahkan baris ini!
			header("Location: awal.php");
			exit;
		}else{
			echo '<script>alert("Maaf, coba lagi")</script>';
		}
	}
}

$pro = new login_web();
$pro->koneksi = mysqli_connect('localhost', 'root', '', 'absenKaryawan');

 ?>