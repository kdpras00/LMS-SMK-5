<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$get_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$get_id = isset($_GET['id']) ? $_GET['id'] : '';
$get_kd = isset($_GET['kd']) ? $_GET['kd'] : '';
$get_jdwl = isset($_GET['jdwl']) ? $_GET['jdwl'] : '';

if ($act==''){ ?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php if ($get_kelas != '' AND $get_tahun != ''){ echo "Rekap Absensi siswa"; }else{ echo "Rekap Absensi Siswa Pada Tahun ".date('Y'); } ?></h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='rekapabsensiswa'>
                    <select name='tahun' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Tahun Akademik -</option>";
                            $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
                            while ($k = mysql_fetch_array($tahun)){
                              if ($get_tahun==$k['id_tahun_akademik']){
                                echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
                              }else{
                                echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
                              }
                            }
                        ?>
                    </select>
                    <select name='kelas' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Kelas -</option>";
                            $kelas = mysql_query("SELECT * FROM rb_kelas");
                            while ($k = mysql_fetch_array($kelas)){
                              if ($get_kelas==$k['kode_kelas']){
                                echo "<option value='$k[kode_kelas]' selected>$k[kode_kelas] - $k[nama_kelas]</option>";
                              }else{
                                echo "<option value='$k[kode_kelas]'>$k[kode_kelas] - $k[nama_kelas]</option>";
                              }
                            }
                        ?>
                    </select>
                    <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
                  </form>

                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Jadwal Pelajaran</th>
                        <th>Kelas</th>
                        <th>Guru</th>
                        <th>Hari</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Ruangan</th>
                        <th>Semester</th>
                        <?php if($_SESSION['level']!='kepala'){ ?>
                        <th>Action</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    $tampil = null;
                    if ($get_kelas != '' AND $get_tahun != ''){
                      // Ensure kurikulum is set
                      $kode_kurikulum = isset($kurikulum['kode_kurikulum']) ? $kurikulum['kode_kurikulum'] : '';
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.kode_kelas='$get_kelas' 
                                                    AND a.id_tahun_akademik='$get_tahun' 
                                                      AND b.kode_kurikulum='$kode_kurikulum' ORDER BY a.hari DESC");
                    
                    }
                    if ($tampil) {
                        $no = 1;
                        while($r=mysql_fetch_array($tampil)){
                        echo "<tr><td>$no</td>
                                  <td>$r[namamatapelajaran]</td>
                                  <td>$r[nama_kelas]</td>
                                  <td>$r[nama_guru]</td>
                                  <td>$r[hari]</td>
                                  <td>$r[jam_mulai]</td>
                                  <td>$r[jam_selesai]</td>
                                  <td>$r[nama_ruangan]</td>
                                  <td>$r[id_tahun_akademik]</td>";
                                  if($_SESSION['level']!='kepala'){
                            echo "<td style='width:70px !important'><center>
                                    <a class='btn btn-success btn-xs' title='Tampil List Absensi' href='index.php?view=rekapabsensiswa&act=tampilabsen&id=$r[kode_kelas]&kd=$r[kode_pelajaran]&jdwl=$r[kodejdwl]&tahun=$get_tahun'><span class='glyphicon glyphicon-th'></span> Tampilkan</a>
                                  </center></td>";
                                  }
                                echo "</tr>";
                          $no++;
                          }
                    }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <?php 
                    if ($get_kelas == '' AND $get_tahun == ''){
                        echo "<center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik dan Kelas Terlebih dahulu...</center>";
                    }
                ?>
                </div>
            </div>
<?php 
}elseif($act=='tampilabsen'){
    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$get_id'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$get_kd'"));
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Rekap Data Absensi Siswa Pada $get_tahun</b></h3>
                </div>
              <div class='box-body'>

              <div class='col-md-12'>
              <table class='table table-condensed table-hover'>
                  <tbody>
                    <input type='hidden' name='id' value='$d[kode_kelas]'>
                    <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                    <tr><th scope='row'>Nama Kelas</th>               <td>$d[nama_kelas]</td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>           <td>$m[namamatapelajaran]</td></tr>
                  </tbody>
              </table>
              </div>

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered table-striped'>
                      <thead>
                      <tr>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Jenis Kelamin</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Hadir</th>
                        <th><center>% Kehadiran</center></th>
                      </tr>
                    </thead>
                    <tbody>";
                    
                    $no = 1;
                    $tampil = mysql_query("SELECT * FROM rb_siswa a JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin where a.kode_kelas='$get_id' ORDER BY a.id_siswa");
                    while($r=mysql_fetch_array($tampil)){
                    $total = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$get_jdwl' GROUP BY tanggal"));
                    $hadir = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$get_jdwl' AND nisn='$r[nisn]' AND kode_kehadiran='H'"));
                    $sakit = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$get_jdwl' AND nisn='$r[nisn]' AND kode_kehadiran='S'"));
                    $izin = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$get_jdwl' AND nisn='$r[nisn]' AND kode_kehadiran='I'"));
                    $alpa = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$get_jdwl' AND nisn='$r[nisn]' AND kode_kehadiran='A'"));
                    
                    if ($total > 0) {
                        $persen = $hadir/($total)*100;
                    } else {
                        $persen = 0;
                    }

                    $warna = ($no % 2 == 1) ? '#ffffff' : '#f0f0f0';
                    echo "<tr bgcolor=$warna>
                            <td>$r[nisn]</td>
                            <td>$r[nama]</td>
                            <td>$r[jenis_kelamin]</td>
                            <td align=center>$izin</td>
                            <td align=center>$sakit</td>
                            <td align=center>$hadir</td>
                            <td align=right>".number_format($persen, 2)." %</td>";
                    echo "</tr>";
                      $no++;
                      }

                    echo "</tbody>
                  </table>
                </div>
              </div>
            </div>";

}
?>