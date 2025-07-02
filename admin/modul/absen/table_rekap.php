<div class="row">
    <a href="?m=absen&s=excel&bulan=<?=$bulan;?>&tahun=<?=$tahun;?>" class="btn btn-success mb-2 ml-2 mt-2">Convert to Excel</a>
     <a href="?m=absen&s=word&bulan=<?=$bulan;?>&tahun=<?=$tahun;?>" class="btn btn-primary mb-2 ml-2 mt-2">Convert to Word</a>

 <a href="?m=absen&s=print_rekap&bulan=<?=$bulan;?>&tahun=<?=$tahun;?>" class="btn btn-warning mb-2 ml-2 mt-2">Print</a>

 <div class="table-responsive table--no-card m-b-30 ml-3 mr-3">
 	<table id="example" class="display" style="width:100%">
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
$no = 1;
// 1. Tampilkan data dari tb_absen
foreach ($query as $key): ?>
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
<?php endforeach; ?>

<?php
// 2. Tampilkan data tb_keterangan yang belum ada di tb_absen
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