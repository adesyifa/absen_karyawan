<?php include 'fungsi/fungsi.php'; ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Rekap Absen</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  </head>
  <body>
    <center>
      <h1>Rekap Absen Bulan <?= $_GET['bulan'];?></h1>
    </center>
    <div class="row mt-2 ml-2">
      <div class="col-md-6">
        <p>Bulan : <?= $_GET['bulan'];?></p>
        <p>Tahun : <?= $_GET['tahun'];?></p>
      </div>
    </div>
    <div class="row mt-3 ml-3 mr-3 ">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>NIP</th>
              <th>Nama</th>
              <th>Tanggal</th>
              <th>Bulan</th>
              <th>Kehadiran</th>
              <th>alasan</th>
              <th>keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
            $no = 1;
            // Data absen
            $query = mysqli_query($koneksi, "SELECT * FROM tb_absen WHERE bulan='$bulan' AND tahun='$tahun'");
            while ($key = mysqli_fetch_assoc($query)): ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $key['nip']; ?></td>
              <td><?= $key['nama']; ?></td>
              <td><?= $key['tanggal']; ?></td>
              <td><?= $key['bulan']; ?></td>
              <td>
                <?php
                if (!$key['jam'] || $key['jam'] == '00:00:00') {
                  echo '-';
                } else if (strtotime($key['jam']) > strtotime('08:00:00')) {
                  echo '<b style="color: red;">Telat</b>';
                } else {
                  echo '<b style="color: green;">Tepat Waktu</b>';
                }
                ?>
              </td>
              <td>
                <?php
                // Ambil alasan dan keterangan dari tb_keterangan berdasarkan nip & tanggal
                $nip = $key['nip'];
                $tanggal = $key['tanggal'];
                $q_ket = mysqli_query($koneksi, "SELECT alasan, keterangan FROM tb_keterangan WHERE nip='$nip' AND tanggal='$tanggal' LIMIT 1");
                $alasan = '-';
                $keterangan = '-';
                if ($row_ket = mysqli_fetch_assoc($q_ket)) {
                  $alasan = $row_ket['alasan'];
                  $keterangan = $row_ket['keterangan'];
                }
                echo $alasan;
                ?>
              </td>
              <td>
                <?php echo $keterangan; ?>
              </td>
            </tr>
            <?php endwhile; ?>

            <?php
            // Data tb_keterangan yang belum ada di tb_absen
            $q_ket_only = mysqli_query($koneksi, "
              SELECT * FROM tb_keterangan 
              WHERE bulan='$bulan' 
                AND (nip, tanggal) NOT IN (
                  SELECT nip, tanggal FROM tb_absen WHERE bulan='$bulan' AND tahun='$tahun'
                )
            ");
            while ($key = mysqli_fetch_assoc($q_ket_only)): ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $key['nip']; ?></td>
              <td><?= $key['nama']; ?></td>
              <td><?= $key['tanggal']; ?></td>
              <td><?= $key['bulan']; ?></td>
              <td>-</td>
              <td><?= $key['alasan'] ? $key['alasan'] : '-'; ?></td>
              <td><?= $key['keterangan'] ? $key['keterangan'] : '-'; ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
    <script>
      window.print()
    </script>
  </body>
</html>