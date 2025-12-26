<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=rekap_nilai.xls");

include "../config/koneksi.php";

if (isset($_GET['kelas']) AND isset($_GET['tahun'])){
    $t = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik where id_tahun_akademik='$_GET[tahun]'"));
    $k = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[kelas]'"));

    echo "<h3>Rekap Nilai Siswa Tahun $_GET[tahun] - Kelas $_GET[kelas] ($k[nama_kelas])</h3>";

    $mapel_query = mysql_query("SELECT distinct b.kode_pelajaran, b.namamatapelajaran, b.kkm 
                                FROM rb_jadwal_pelajaran a 
                                JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran 
                                WHERE a.kode_kelas='$_GET[kelas]' 
                                AND a.id_tahun_akademik='$_GET[tahun]'
                                ORDER BY b.namamatapelajaran ASC");
    
    $subjects = array();
    while($row = mysql_fetch_assoc($mapel_query)){
        $subjects[] = $row;
    }

    if (count($subjects) > 0){
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th style='width:40px'>No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>";
                        foreach($subjects as $subj){
                             echo "<th style='background-color:#e3e3e3'>$subj[namamatapelajaran]</th>";
                        }
        echo "      </tr>
                </thead>
                <tbody>";
        
        $siswa = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[kelas]' ORDER BY nama ASC");
        $no = 1;
        while($s = mysql_fetch_array($siswa)){
            echo "<tr>
                    <td>$no</td>
                    <td>$s[nisn]</td>
                    <td>$s[nama]</td>";
            
            foreach($subjects as $subj){
                $jdwl = mysql_fetch_array(mysql_query("SELECT kodejdwl FROM rb_jadwal_pelajaran 
                                                         WHERE kode_kelas='$_GET[kelas]' 
                                                         AND id_tahun_akademik='$_GET[tahun]' 
                                                         AND kode_pelajaran='$subj[kode_pelajaran]' LIMIT 1"));
                
                $nilai = 0;
                if ($jdwl){
                    $n = mysql_fetch_array(mysql_query("SELECT sum((nilai1+nilai2+nilai3+nilai4+nilai5)/5)/count(nisn) as rata FROM rb_nilai_pengetahuan where kodejdwl='$jdwl[kodejdwl]' AND nisn='$s[nisn]'"));
                    $nilai = number_format($n['rata']);
                }
                
                echo "<td align='center'>$nilai</td>";
            }

            echo "</tr>";
            $no++;
        }
        echo "</tbody>
            </table>";
    }
}
?>
