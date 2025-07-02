<?php
session_start();
if (!isset($_SESSION['adm'])) {
    // Redirect ke halaman login jika belum login
    header("Location: ../login.php");
    exit;
}
$adm = $_SESSION['adm'];
?>
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
    echo "<script>window.location='awal.php';</script>";
    exit;
}
if (isset($_POST['simpan_ket'])) {
    simpan_keterangan();
    echo "<script>window.location='awal.php';</script>";
    exit;
}
if (isset($_POST['simpan_pulang'])) {
    simpan_absen_pulang();
    echo "<script>window.location='awal.php';</script>";
    exit;
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
                                <form action="" method="POST" enctype="multipart/form-data" id="formAbsen">
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
                                        <td>Foto Selfie (Wajib):</td>
                                        <td>
                                            <input type="file" name="foto" accept="image/*" capture="user" class="form-control" id="fotoAbsen">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            // Cek apakah sudah ada keterangan hari ini
                                            $nip = $adm['nip'];
                                            $tanggalSekarang = date("d-m-Y");
                                            $cek_ket = mysqli_query($koneksi, "SELECT * FROM tb_keterangan WHERE nip='$nip' AND tanggal='$tanggalSekarang'");
                                            $cek_absen = mysqli_query($koneksi, "SELECT * FROM tb_absen WHERE nip='$nip' AND tanggal='$tanggalSekarang' AND jam IS NOT NULL AND jam != '00:00:00'");
                                            $disableAbsen = (mysqli_num_rows($cek_ket) > 0); // disable absen masuk & pulang jika sudah ada keterangan
                                            $disableKeterangan = (mysqli_num_rows($cek_absen) > 0); // disable cuti jika sudah absen masuk
                                            ?>
                                            <button type="submit" name="simpan" class="btn btn-success" onclick="return confirm('Ingin absen masuk?')" <?= $disableAbsen ? 'disabled' : '' ?>>Absen Masuk</button>
                                            <button type="submit" name="simpan_pulang" class="btn btn-danger" onclick="return confirm('Ingin absen pulang?')" <?= $disableAbsen ? 'disabled' : '' ?>>Absen Pulang</button>
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal" <?= $disableKeterangan || $disableAbsen ? 'disabled' : '' ?>>Cuti / Tidak Hadir</button>
                                        </td>
                                    </tr>
                                </form>
                            </table>
                        </div><br>
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
                        <br>

                        <h4 class="mt-4 mb-3" style="font-weight:bold;">Riwayat Absen bulan ini</h4>
                         <?php
                        // Rekap Kehadiran Bulan Ini (khusus bulan ini, otomatis reset jika bulan berganti)
                        $nip = $adm['nip'];
                        $bulan_ini = date('m');
                        $tahun_ini = date('Y');

                        // Query hanya data bulan & tahun ini
                        $q_masuk = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tb_absen WHERE nip='$nip' AND MONTH(STR_TO_DATE(tanggal, '%d-%m-%Y'))='$bulan_ini' AND YEAR(STR_TO_DATE(tanggal, '%d-%m-%Y'))='$tahun_ini' AND jam IS NOT NULL AND jam != '00:00:00' AND kehadiran='Hadir'");
                        $masuk = mysqli_fetch_assoc($q_masuk)['jml'];

                        $q_pulang = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tb_absen WHERE nip='$nip' AND MONTH(STR_TO_DATE(tanggal, '%d-%m-%Y'))='$bulan_ini' AND YEAR(STR_TO_DATE(tanggal, '%d-%m-%Y'))='$tahun_ini' AND jam2 IS NOT NULL AND jam2 != '00:00:00'");
                        $pulang = mysqli_fetch_assoc($q_pulang)['jml'];

                        // Ambil jumlah Cuti/Izin/Sakit/Dinas dari tb_keterangan
                        $q_cuti = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tb_keterangan WHERE nip='$nip' AND MONTH(STR_TO_DATE(tanggal, '%d-%m-%Y'))='$bulan_ini' AND YEAR(STR_TO_DATE(tanggal, '%d-%m-%Y'))='$tahun_ini'");
                        $cuti = mysqli_fetch_assoc($q_cuti)['jml'];
                        ?>
                        <!-- Rekap Kehadiran Bulan Ini dalam bentuk Card -->
                        <div class="row mb-2" style="margin-top:0;">
                            <div class="col-12 col-sm-4 mb-2">
                                <div class="card text-white bg-success shadow-sm h-100 custom-card-small">
                                    <div class="card-body text-center py-3">
                                        <div style="font-size:1.6em;"><i class="fa fa-sign-in"></i></div>
                                        <div style="font-size:1em;font-weight:bold;">Masuk</div>
                                        <div style="font-size:1.4em;font-weight:bold;"><?= $masuk ?></div>
                                        <div style="font-size:0.95em;">Kali Bulan Ini</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 mb-2">
                                <div class="card text-white bg-primary shadow-sm h-100 custom-card-small">
                                    <div class="card-body text-center py-3">
                                        <div style="font-size:1.6em;"><i class="fa fa-sign-out"></i></div>
                                        <div style="font-size:1em;font-weight:bold;">Pulang</div>
                                        <div style="font-size:1.4em;font-weight:bold;"><?= $pulang ?></div>
                                        <div style="font-size:0.95em;">Kali Bulan Ini</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 mb-2">
                                <div class="card text-dark bg-warning shadow-sm h-100 custom-card-small">
                                    <div class="card-body text-center py-3">
                                        <div style="font-size:1.6em;"><i class="fa fa-calendar-times-o"></i></div>
                                        <div style="font-size:1em;font-weight:bold;">Cuti/Izin/Sakit/Dinas</div>
                                        <div style="font-size:1.4em;font-weight:bold;"><?= $cuti ?></div>
                                        <div style="font-size:0.95em;">Kali Bulan Ini</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Card Rekap Kehadiran -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" style="background:#fff;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                                <thead class="thead-dark" style="background:#333;color:#fff;">
                                    <tr style="text-align:center;">
                                        <th style="vertical-align:middle;">Tanggal</th>
                                        <th style="vertical-align:middle;">Jam Masuk</th>
                                        <th style="vertical-align:middle;">Jam Pulang</th>
                                        <th style="vertical-align:middle;">Kehadiran</th>
                                        <th style="vertical-align:middle;">Keterangan</th>
                                        <th style="vertical-align:middle;">Alasan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $nip = $adm['nip'];
                                    $q_absen = mysqli_query($koneksi, "
                                        SELECT tanggal, jam, jam2, kehadiran, 'absen' as sumber FROM tb_absen WHERE nip='$nip'
                                        UNION 
                                        SELECT tanggal, jam, NULL as jam2, NULL as kehadiran, 'keterangan' as sumber FROM tb_keterangan WHERE nip='$nip'
                                        ORDER BY STR_TO_DATE(tanggal, '%d-%m-%Y') DESC LIMIT 30
                                    ");

                                    while($d = mysqli_fetch_array($q_absen)) {
                                        $tanggal = $d['tanggal'];
                                        $jam_masuk = $d['jam'];
                                        $jam_pulang = $d['jam2'];
                                        $status_kehadiran = '-';
                                        $keterangan = '-';
                                        $alasan = '-';
                                        // Ambil keterangan & alasan dari tb_keterangan untuk tanggal ini
                                        $q_ket = mysqli_query($koneksi, "SELECT keterangan, alasan FROM tb_keterangan WHERE nip='$nip' AND tanggal='$tanggal' LIMIT 1");
                                        if ($row_ket = mysqli_fetch_assoc($q_ket)) {
                                            $keterangan = htmlspecialchars($row_ket['keterangan']);
                                            $alasan = htmlspecialchars($row_ket['alasan']);
                                        }

                                        if ($d['sumber'] == 'absen') {
                                            // Data dari tb_absen
                                            $jam_masuk_fmt = ($jam_masuk && $jam_masuk != 'null' && $jam_masuk != '00:00:00') ? strtolower(date('h:i a', strtotime($jam_masuk))) : '-';
                                            // Tampilkan "Belum Absen Pulang" jika jam_pulang kosong/null/00:00:00
                                            if ($jam_pulang && $jam_pulang != 'null' && $jam_pulang != '00:00:00') {
                                                $jam_pulang_fmt = strtolower(date('h:i a', strtotime($jam_pulang)));
                                            } else {
                                                $jam_pulang_fmt = '<span class="text-danger font-italic">Belum Absen Pulang</span>';
                                            }
                                            $tepat_waktu = ($jam_masuk && $jam_masuk != 'null' && $jam_masuk != '00:00:00') ? (strtotime($jam_masuk) <= strtotime('08:00:00')) : false;
                                            $status_kehadiran = ($jam_masuk && $jam_masuk != 'null' && $jam_masuk != '00:00:00')
                                                ? ($tepat_waktu ? '<span class="badge badge-success" style="font-size:1em;padding:7px 14px;">Tepat Waktu</span>' : '<span class="badge badge-danger" style="font-size:1em;padding:7px 14px;">Terlambat</span>')
                                                : '-';
                                        } else {
                                            // Data dari tb_keterangan (Cuti/Izin/Sakit/Dinas)
                                            $jam_masuk_fmt = '-';
                                            $jam_pulang_fmt = '-';
                                            $status_kehadiran = '<span class="badge badge-warning" style="font-size:1em;padding:7px 14px;">' . htmlspecialchars($keterangan) . '</span>';
                                        }
                                    ?>
                                    <tr style="text-align:center;vertical-align:middle;">
                                        <td><?= $tanggal ?></td>
                                        <td><?= $jam_masuk_fmt ?></td>
                                        <td><?= $jam_pulang_fmt ?></td>
                                        <td><?= $status_kehadiran ?></td>
                                        <td><?= $keterangan ?></td>
                                        <td><?= $alasan ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Akhir Riwayat Absen -->

                        <!-- Tambahkan CSS jika ingin badge lebih menarik -->
                        <style>
                        .badge-success {
                            background: #28a745 !important;
                            color: #fff !important;
                        }
                        .badge-danger {
                            background: #dc3545 !important;
                            color: #fff !important;
                        }
                        .badge-primary {
                            background: #007bff !important;
                            color: #fff !important;
                        }
                        .badge-warning {
                            background: #ffc107 !important;
                            color: #212529 !important;
                        }
                        </style>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
document.getElementById('formAbsen').addEventListener('submit', function(e) {
    var foto = document.getElementById('fotoAbsen');
    // Cek tombol yang ditekan
    var simpan = document.activeElement && document.activeElement.name === 'simpan';
    // Hanya validasi upload foto jika absen masuk
    if (simpan && (!foto.value || foto.value === "")) {
        alert('Foto wajib diupload untuk absen masuk!');
        e.preventDefault();
    }
    // Tidak ada validasi foto untuk absen pulang
});
</script>
<?php include 'comp/footer.php'; ?>
