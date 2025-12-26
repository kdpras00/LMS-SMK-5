<?php 
session_start();
error_reporting(0);
include "../config/koneksi.php"; 
include "../config/fungsi_indotgl.php"; 
$frt = mysql_fetch_array(mysql_query("SELECT * FROM rb_header_print ORDER BY id_header_print DESC LIMIT 1")); 
?>
<head>
<title>Hal 4 - Raport Siswa</title>
<link rel="stylesheet" href="../bootstrap/css/printer.css">
</head>
<body onLoad="window.print()">
<?php
$t = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik where id_tahun_akademik='$_GET[tahun]'"));
$s = mysql_fetch_array(mysql_query("SELECT a.*, b.*, c.nama_guru as walikelas, c.nip FROM rb_siswa a 
                                      JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                        LEFT JOIN rb_guru c ON b.nip=c.nip where a.nisn='$_GET[id]'"));
if (substr($_GET[tahun],4,5)=='1'){ $semester = 'Ganjil'; }else{ $semester = 'Genap'; }
$iden = mysql_fetch_array(mysql_query("SELECT * FROM rb_identitas_sekolah ORDER BY id_identitas_sekolah DESC LIMIT 1"));
echo "<table width=100%>
        <tr><td width=140px>Nama Sekolah</td> <td> : $iden[nama_sekolah] </td>       <td width=140px>Kelas </td>   <td>: $s[kode_kelas]</td></tr>
        <tr><td>Alamat</td>                   <td> : $iden[alamat_sekolah] </td>     <td>Semester </td> <td>: $semester</td></tr>
        <tr><td>Nama Peserta Didik</td>       <td> : <b>$s[nama]</b> </td>           <td>Tahun Pelajaran </td> <td>: $t[keterangan]</td></tr>
        <tr><td>No Induk/NISN</td>            <td> : $s[nipd] / $s[nisn]</td>        <td>Fase </td> <td>: $s[fase]</td></tr>
      </table><br>";

echo "<table id='tablemodul1' width=100% border=1>
          <tr>
            <th width='160px' colspan='2' rowspan='2'>Mata Pelajaran</th>
            
            <th  style='text-align:center'>Nilai Akhir</th>
            <th colspan='2' style='text-align:center'>Capaian Kompetensi</th>
          </tr>
          <tr>
            
          </tr>";
      $kelompok = mysql_query("SELECT * FROM rb_kelompok_mata_pelajaran");  
      while ($k = mysql_fetch_array($kelompok)){
      echo "<tr>
            <td colspan='7'><b>$k[nama_kelompok_mata_pelajaran]</b></td>
          </tr>";
        $mapel = mysql_query("SELECT * FROM  rb_jadwal_pelajaran a JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran 
                                  where a.kode_kelas='$_GET[kelas]' AND a.id_tahun_akademik='$_GET[tahun]' AND b.id_kelompok_mata_pelajaran='$k[id_kelompok_mata_pelajaran]'");
        $no = 1;
        while ($m = mysql_fetch_array($mapel)){                                
        $rapn = mysql_fetch_array(mysql_query("SELECT sum((nilai1+nilai2+nilai3+nilai4+nilai5)/5)/count(nisn) as raport FROM rb_nilai_pengetahuan where kodejdwl='$m[kodejdwl]' AND nisn='$s[nisn]'"));
       
     $maxn = mysql_fetch_array(mysql_query("SELECT ((nilai1+nilai2+nilai3+nilai4+nilai5)/5) as rata_rata, deskripsi FROM rb_nilai_pengetahuan where kodejdwl='$m[kodejdwl]' AND nisn='$s[nisn]' ORDER BY rata_rata DESC LIMIT 1"));

      

        echo "<tr>
                <td align=center>$no</td>
                <td>$m[namamatapelajaran]</td>
               
                <td align=center>".number_format($rapn[raport])."</td>
               <td>$maxn[deskripsi]</td>
                
              
            </tr>";
        $no++;
        }
      }

        echo "</table><br/>";
        $cekpredikat1 = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[kelas]'"));
        if ($cekpredikat1 >= 1){
          $grade = mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[kelas]'");
          $gradea = mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[kelas]'");
          $total = mysql_num_rows($grade);
        }else{
          $grade = mysql_query("SELECT * FROM rb_predikat where kode_kelas='0'");
          $gradea = mysql_query("SELECT * FROM rb_predikat where kode_kelas='0'");
          $total = mysql_num_rows($grade);
        }
         
              echo "</tr>
          </table></center><br>";
?>

<table border=0 width=100%>
  <tr>
    <td width="260" align="left">Orang Tua / Wali</td>
    <td width="520"align="center">Mengetahui <br> 
    Kepala SMK Bintang Nusantara</td>
    <td width="260" align="left">Tangerang Selatan, <?php echo $t[titimangsa]; ?></b> <br> Wali Kelas</td>
  </tr>
  <tr>
    <td align="left"><br /><br /><br /><br /><br />
      ................................... <br /><br /></td>

   
    <td width="520"align="center"> <br> <br> <br> <br> <br>
      <b>Nurhadi, S.Pd.I, MM<br>
      NIP : -</b>
    </td>

    <td align="left" valign="top"><br /><br /><br /><br /><br />
      <b><?php echo $s[walikelas]; ?><br />
      NIP :-</b>
    </td>
  </tr>
</table> 
</body>