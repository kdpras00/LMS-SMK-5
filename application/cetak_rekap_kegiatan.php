<?php 
session_start();
error_reporting(0);
include "../config/koneksi.php";
include "../config/fungsi_indotgl.php";

if (isset($_SESSION['id'])){ 
    $tahun_akademik = isset($_GET['tahun']) ? $_GET['tahun'] : '';
?>
<html>
<head>
    <title>Laporan Rekap Data Kegiatan</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body onload="window.print()">
    <div style="text-align:center; margin-bottom:20px">
        <h3>REKAPITULASI DATA KEGIATAN GURU<br>SMK NEGERI 5 KOTA BEKASI</h3>
        <b>Tahun Akademik: <?php echo $tahun_akademik; ?></b>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style='width:30px'>No</th>
                <th>Nama Guru</th>
                <th>Mata Pelajaran</th>
                <th>Kelas</th>
                <th>File RPP</th>
                <th>Jml Tugas</th>
                <th>Keaktifan (Journal)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($tahun_akademik != '') {
                $tampil = mysql_query("SELECT a.kodejdwl, a.nip, a.kode_pelajaran, a.kode_kelas, 
                                        b.nama_guru, c.namamatapelajaran, d.nama_kelas 
                                        FROM rb_jadwal_pelajaran a 
                                        JOIN rb_guru b ON a.nip=b.nip 
                                        JOIN rb_mata_pelajaran c ON a.kode_pelajaran=c.kode_pelajaran
                                        JOIN rb_kelas d ON a.kode_kelas=d.kode_kelas
                                        WHERE a.id_tahun_akademik='$tahun_akademik' 
                                        ORDER BY b.nama_guru ASC");
                $no = 1;
                while ($r = mysql_fetch_array($tampil)) {
                    $cek_rpp = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning WHERE kodejdwl='$r[kodejdwl]' AND id_kategori_elearning='1'"));
                    $status_rpp = ($cek_rpp > 0) ? "Ada" : "Tidak Ada";
                    $jml_tugas = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning WHERE kodejdwl='$r[kodejdwl]' AND id_kategori_elearning='2'"));
                    $jml_journal = mysql_num_rows(mysql_query("SELECT * FROM rb_journal_list WHERE kodejdwl='$r[kodejdwl]'"));
                    
                    $target_absen = 16; 
                    if ($target_absen > 0) {
                        $persentase = ($jml_journal / $target_absen) * 100;
                    } else {
                        $persentase = 0;
                    }
                    if ($persentase > 100) $persentase = 100;

                    echo "<tr>
                            <td align='center'>$no</td>
                            <td>$r[nama_guru]</td>
                            <td>$r[namamatapelajaran]</td>
                            <td>$r[nama_kelas]</td>
                            <td align='center'>$status_rpp</td>
                            <td align='center'>$jml_tugas</td>
                            <td align='center'>".number_format($persentase,0)."%</td>
                          </tr>";
                    $no++;
                }
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
