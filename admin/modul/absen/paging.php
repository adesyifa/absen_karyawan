<?php 
$koneksi = mysqli_connect('localhost', 'root', '', 'absenkaryawan');

    $batas = 5;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;

$previous = $halaman - 1;
$next = $halaman + 1;

$data = mysqli_query($koneksi, "SELECT * FROM tb_absen");
$jumlah_data = mysqli_num_rows($data);
$total_halaman = ceil($jumlah_data / $batas);


$nomor = $halaman_awal+1;


// cari
if (isset($_POST['go'])) {
  $cari = $_POST['cari'];
  $karyawan = mysqli_query($koneksi, "SELECT * FROM tb_absen WHERE nama LIKE '%".$cari."%'");
}else{
  $karyawan = mysqli_query($koneksi, "SELECT * FROM tb_absen LIMIT $halaman_awal, $batas");
}


foreach ($karyawan as $pro):
?>
<tr>
    <td><?= $i++;  ?></td>
    <td><?=  $pro['nip'];?></td>
    <td><?=  $pro['nama'];?></td>
    <td><?= $pro['tanggal'];?></td>
    <td>
        <?= ($pro['jam'] && $pro['jam'] != '00:00:00') ? date('h:i a', strtotime($pro['jam'])) : '-' ?>
    </td>
    <td>
        <?= ($pro['jam2'] && $pro['jam2'] != '00:00:00') ? date('h:i a', strtotime($pro['jam2'])) : '<span style="color:gray;">Belum Absen Pulang</span>' ?>
    </td>
    <td>
        <?php 
        $select_jam = mysqli_query($koneksi, "SELECT * FROM jam_masuk");
        $jam_masuk = mysqli_fetch_array($select_jam);
         if (!$pro['jam2'] || $pro['jam2'] == '00:00:00') {
        echo '<b style="color: gray;">Belum Absen Pulang</b>';
    } else if (
        strtotime($pro['jam']) >= strtotime('04:00:00') && 
        strtotime($pro['jam']) <= strtotime('08:00:00')
    ) {
        echo '<b style="color: green;">Tepat Waktu</b>';
    } else if (strtotime($pro['jam']) > strtotime('08:00:00')) {
        echo '<b style="color: red;">Telat</b>';
    } else {
        echo '<b style="color: gray;">Diluar Jam Kerja</b>';
    }
        ?>
    </td>
    <td>
        <img src="img/karyawan/<?=$pro['foto'];?>" data-target="#view_image<?=$pro['foto']?>" data-toggle="modal">
        <div class="modal fade" id="view_image<?=$pro['foto']?>" tabindex="-1" role="dialog" aria-labelledby="view_image" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <center><img src="img/karyawan/<?= $pro['foto'];?>" width="1080"></center>
            </div>
        </div>
    </td>
    <td>
        <button class="btn btn-danger" data-toggle="modal" data-target="#hapus_karyawan<?=$pro['id'];?>">hapus</button>
        <div class="modal fade" id="hapus_karyawan<?=$pro['id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" hidden name="id" value="<?=$pro['id'];?>" >
                            <label>NIP : </label>
                            <b><?=$pro['nip'];?></b><br>
                            <label>Nama : </label>
                            <b><?=$pro['nama'];?></b><br>
                            <label>Tanggal : </label>
                            <b><?=$pro['tanggal'];?></b><br>
                            <label>Jam : </label>
                            <b><?=$pro['jam'];?></b><br>
                            <div class="modal-footer">
                                <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
<?php endforeach; ?>
