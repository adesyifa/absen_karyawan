<?php include 'comp/header.php'; ?>
<?php 

date_default_timezone_set("Asia/makassar");
$tanggalSekarang = date("d-m-Y");
$jam2 = date("hi");
$jamSekarang = date("h:i a");
$bulan = date("m");
$tahun = date("Y");
if (isset($_POST['simpan'])) {
    simpan_absen();
}
if (isset($_POST['simpan_ket'])) {
    simpan_keterangan();
}
if (isset($_POST['simpan_pulang'])) {
    simpan_absen_pulang();
}
?>
<div class="main-content">
    <div class="section__content section__content--p30"></div>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="col-sm-10">Silahkan Absen, <?php echo $adm['nama']; ?>!</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Absen</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12"><br>
                        <div class="table-responsive">
                            <table class="table-responsive table-borderless table-earning" border="10">
                                <form action="" method="POST">
                                    <tr>
                                        <td>NIP : </td>
                                        <td><?=$adm['nip'];?>
                                            <input type="hidden" name="nip" value="<?=$adm['nip'];?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nama : </td>
                                        <td><?=$adm['nama'];?>
                                            <input type="hidden" name="nama" value="<?=$adm['nama'];?>">
                                            <input type="hidden" name="tanggal" value="<?=$tanggalSekarang;?>">
                                            <input type="hidden" name="jam" value="<?=$jamSekarang;?>">
                                            <input type="hidden" name="bulan" value="<?=$bulan;?>">
                                            <input type="hidden" name="tahun" value="<?=$tahun;?>">
                                            <input type="hidden" name="jam2" value="<?=$jam2;?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button type="submit" name="simpan" class="btn btn-success" onclick="return confirm('Ingin absen masuk?')">Absen Masuk</button>
                                            <button type="submit" name="simpan_pulang" class="btn btn-danger" onclick="return confirm('Ingin absen pulang?')">Absen Pulang</button>
                                        </td>
                                    </tr>
                                </form>
                            </table>
                        </div><br>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#exampleModal">Klik tombol ini jika tidak hadir / absen / cuti </button>
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Masukkan Keterangan Anda</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label><b>NIP : </b></label><br>
                                                <?=$adm['nip'];?>
                                                <input type="hidden" class="form-control" value="<?=$adm['nip'];?>" name="nip">
                                            </div>
                                            <div class="form-group">
                                                <label><b>Nama : </b></label><br>
                                                <?=$adm['nama'];?>
                                                <input type="hidden" class="form-control" value="<?=$adm['nama'];?>" name="nama">
                                            </div>
                                            <div class="form-group">
                                                <label>Keterangan</label>
                                                <select name="keterangan" class="form-control">
                                                    <option>Izin</option>
                                                    <option>Sakit</option>
                                                    <option>Cuti</option>
                                                    <option>Dinas</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Alasan</label>
                                                <textarea name="alasan" class="form-control"></textarea>
                                            </div>
                                            <input type="hidden" name="tanggal" value="<?=$tanggalSekarang;?>">
                                            <input type="hidden" name="jam" value="<?=$jamSekarang;?>">
                                            <input type="hidden" name="bulan" value="<?=$bulan;?>">
                                            <input type="hidden" name="tahun" value="<?=$tahun;?>">
                                            <input type="hidden" value="<?=$jam2;?>" name="jam2">
                                            <div class="form-group">
                                                <label>Foto Bukti / Surat Keterangan</label>
                                                <input type="file" class="form-control" name="foto">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="simpan_ket" class="btn btn-primary">Simpan</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php include 'comp/footer.php'; ?>
