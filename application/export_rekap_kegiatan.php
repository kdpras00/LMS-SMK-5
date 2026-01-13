<?php 
session_start();
error_reporting(0);
include "../config/koneksi.php";
include "../config/fungsi_indotgl.php";

if ($_SESSION['level'] == 'kepala'){
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_keaktifan_guru_".date('Y-m-d').".xls");
?>
    <h3>Laporan Keaktifan Guru</h3>
    <table border="1">
        <thead>
            <tr style="background-color:#e3e3e3">
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Guru</th>
                <th>Jenis Kegiatan</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $activities = array();
            
            // 1. Upload RPP
            $rpp_query = "SELECT 'Upload RPP' as jenis, e.nama_file as detail, c.nama_guru, 
                          DATE(NOW()) as tanggal
                          FROM rb_elearning1 e
                          JOIN rb_jadwal_pelajaran a ON e.kodejdwl=a.kodejdwl
                          JOIN rb_guru c ON a.nip=c.nip
                          ORDER BY e.id_elearning DESC";
            $rpp_result = mysql_query($rpp_query);
            while($r = mysql_fetch_array($rpp_result)){
                $activities[] = $r;
            }
            
            // 2. Input Jurnal
            $jurnal_query = "SELECT 'Input Jurnal KBM' as jenis, CONCAT(j.hari, ' - ', j.materi) as detail, c.nama_guru,
                             j.tanggal
                             FROM rb_journal_list j
                             JOIN rb_jadwal_pelajaran a ON j.kodejdwl=a.kodejdwl
                             JOIN rb_guru c ON a.nip=c.nip
                             ORDER BY j.tanggal DESC";
            $jurnal_result = mysql_query($jurnal_query);
            while($r = mysql_fetch_array($jurnal_result)){
                $activities[] = $r;
            }
            
            // 3. Input Nilai
            $nilai_query = "SELECT 'Input Nilai Tugas' as jenis, CONCAT('Nilai untuk tugas: ', e.nama_file) as detail, c.nama_guru,
                            DATE(NOW()) as tanggal
                            FROM rb_elearning_jawab ej
                            JOIN rb_elearning e ON ej.id_elearning=e.id_elearning
                            JOIN rb_jadwal_pelajaran a ON e.kodejdwl=a.kodejdwl
                            JOIN rb_guru c ON a.nip=c.nip
                            WHERE ej.nilai > 0
                            ORDER BY ej.id_elearning_jawab DESC";
            $nilai_result = mysql_query($nilai_query);
            while($r = mysql_fetch_array($nilai_result)){
                $activities[] = $r;
            }
            
            // Sort by date DESC
            usort($activities, function($a, $b) {
                return strtotime($b['tanggal']) - strtotime($a['tanggal']);
            });
            
            $no = 1;
            foreach($activities as $r){
                $tgl = tgl_indo($r['tanggal']);
                echo "<tr>
                        <td>$no</td>
                        <td>$tgl</td>
                        <td>$r[nama_guru]</td>
                        <td>$r[jenis]</td>
                        <td>$r[detail]</td>
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
