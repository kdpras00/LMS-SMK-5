<?php 
session_start();
error_reporting(0);
include "../config/koneksi.php";

if ($_SESSION['level'] == 'kepala'){
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_akademik_siswa_".date('Y-m-d').".xls");

    // Get Active Year
    $sem = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik WHERE aktif='Ya'"));
?>
    <h3>Laporan Akademik Siswa (Ranking & Rata-rata)</h3>
    <p>Tahun Akademik: <?php echo $sem['nama_tahun']; ?></p>

    <table border="1">
        <thead>
            <tr style="background-color:#e3e3e3">
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Rata-rata Nilai Pengetahuan</th>
                <th>Rata-rata Nilai Keterampilan</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // We need to calculate averages for all students.
            // This might be intensive, so we limit to active students in active year logic.
            
            $query_siswa = mysql_query("SELECT s.nisn, s.nama, k.nama_kelas 
                                        FROM rb_siswa s 
                                        JOIN rb_kelas k ON s.kode_kelas=k.kode_kelas 
                                        ORDER BY k.nama_kelas ASC, s.nama ASC");
            
            $no = 1;
            while($s = mysql_fetch_array($query_siswa)){
                // Average Pengetahuan
                $query_nilai_p = mysql_query("SELECT AVG((n.nilai1+n.nilai2)/2) as rata_p 
                                              FROM rb_nilai_pengetahuan n
                                              JOIN rb_jadwal_pelajaran j ON n.kodejdwl=j.kodejdwl
                                              WHERE n.nisn='$s[nisn]' AND j.id_tahun_akademik='$sem[id_tahun_akademik]'");
                $np = mysql_fetch_array($query_nilai_p);
                $rata_p = number_format($np['rata_p'], 2);
                
                // Average Keterampilan
                $query_nilai_k = mysql_query("SELECT AVG((n.nilai1+n.nilai2)/2) as rata_k 
                                              FROM rb_nilai_keterampilan n
                                              JOIN rb_jadwal_pelajaran j ON n.kodejdwl=j.kodejdwl
                                              WHERE n.nisn='$s[nisn]' AND j.id_tahun_akademik='$sem[id_tahun_akademik]'");
                $nk = mysql_fetch_array($query_nilai_k);
                $rata_k = number_format($nk['rata_k'], 2);

                echo "<tr>
                        <td>$no</td>
                        <td>'$s[nisn]</td>
                        <td>$s[nama]</td>
                        <td>$s[nama_kelas]</td>
                        <td>$rata_p</td>
                        <td>$rata_k</td>
                      </tr>";
                $no++;
            }
        ?>
        </tbody>
    </table>
<?php
} else {
    echo "Anda tidak memiliki akses.";
}
?>
