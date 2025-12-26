<?php 
session_start();
error_reporting(0);
include "../config/koneksi.php";
include "../config/fungsi_indotgl.php";

if (isset($_SESSION['id'])){ 
    $tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

    function nama_hari_print($date){
        $day = date('D', strtotime($date));
        $dayList = array(
            'Sun' => 'Minggu',
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat',
            'Sat' => 'Sabtu'
        );
        return $dayList[$day];
    }
    
    $hari_ini = nama_hari_print($tanggal);
?>
<html>
<head>
    <title>Laporan Jurnal KBM</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body onload="window.print()">
    <div style="text-align:center; margin-bottom:20px">
        <h3>JURNAL KEGIATAN BELAJAR MENGAJAR (KBM)<br>SMK NEGERI 5 KOTA BEKASI</h3>
        <b>Hari/Tanggal: <?php echo $hari_ini.", ".tgl_indo($tanggal); ?></b>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style='width:30px'>No</th>
                <th>Mata Pelajaran</th>
                <th>Kelas</th>
                <th>Guru Pengampu</th>
                <th>Jam</th>
                <th>Materi</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Filter by Day Name derived from Date
            $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, c.nama_guru, 
                                  j.materi, j.keterangan, j.id_journal
                                  FROM rb_jadwal_pelajaran a 
                                  JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                  JOIN rb_guru c ON a.nip=c.nip 
                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas
                                  LEFT JOIN rb_journal_list j ON a.kodejdwl=j.kodejdwl AND j.tanggal='$tanggal'
                                  WHERE a.hari='$hari_ini' 
                                  ORDER BY a.hari DESC, a.jam_mulai ASC");
            
            $no = 1;
            while ($r = mysql_fetch_array($tampil)) {
                $status = ($r['id_journal'] != '') ? "Sudah Isi" : "Belum Isi";
                $materi = ($r['materi'] != '') ? $r['materi'] : '-';
                $keterangan = ($r['keterangan'] != '') ? $r['keterangan'] : '-';

                echo "<tr>
                        <td align='center'>$no</td>
                        <td>$r[namamatapelajaran]</td>
                        <td>$r[nama_kelas]</td>
                        <td>$r[nama_guru]</td>
                        <td align='center'>$r[jam_mulai] - $r[jam_selesai]</td>
                        <td>$materi</td>
                        <td align='center'>$status</td>
                        <td>$keterangan</td>
                      </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
    
    <table width="100%">
        <tr>
            <td width="70%"></td>
            <td align="center">
                Bekasi, <?php echo tgl_indo(date("Y-m-d")); ?> <br>
                Kepala Sekolah,
                <br><br><br><br>
                <b>(_____________________)</b>
            </td>
        </tr>
    </table>

</body>
</html>
<?php 
} else {
    echo "<script>window.location='../index.php';</script>";
}
?>
