<?php 
  // Get Student Info
  $siswa_query = mysql_query("SELECT * FROM rb_siswa WHERE nisn = '$_SESSION[id]'");
  $siswa = ($siswa_query && mysql_num_rows($siswa_query) > 0) ? mysql_fetch_array($siswa_query) : null;
  
  $kelas_nama = "";
  $kode_kelas = "";
  
  if ($siswa) {
      $kode_kelas = $siswa['kode_kelas'];
      $kelas_query = mysql_query("SELECT * FROM rb_kelas WHERE kode_kelas = '$siswa[kode_kelas]'");
      $kelas = ($kelas_query && mysql_num_rows($kelas_query) > 0) ? mysql_fetch_array($kelas_query) : null;
      if ($kelas) {
          $kelas_nama = $kelas['nama_kelas'];
      }
  }
  
  // Get active academic year
  $sem = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik WHERE aktif='Ya'"));
  
  // Stats
  $total_mapel = 0;
  $total_tugas = 0;
  $total_hadir = 0;
  $total_sakit = 0;
  $total_izin = 0;
  $total_alpha = 0;
  
  if ($kode_kelas && $sem) {
      $total_mapel_query = mysql_query("SELECT * FROM rb_jadwal_pelajaran WHERE kode_kelas = '$kode_kelas' AND id_tahun_akademik='$sem[id_tahun_akademik]'");
      $total_mapel = $total_mapel_query ? mysql_num_rows($total_mapel_query) : 0;
      
      $total_tugas_query = mysql_query("SELECT * FROM rb_elearning a JOIN rb_jadwal_pelajaran b ON a.kodejdwl=b.kodejdwl WHERE b.kode_kelas = '$kode_kelas' AND a.id_kategori_elearning='2'");
      $total_tugas = $total_tugas_query ? mysql_num_rows($total_tugas_query) : 0;
      
      // Attendance stats
      $total_hadir = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_siswa WHERE nisn='$_SESSION[id]' AND kode_kehadiran='H'"));
      $total_sakit = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_siswa WHERE nisn='$_SESSION[id]' AND kode_kehadiran='S'"));
      $total_izin = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_siswa WHERE nisn='$_SESSION[id]' AND kode_kehadiran='I'"));
      $total_alpha = mysql_num_rows(mysql_query("SELECT * FROM rb_absensi_siswa WHERE nisn='$_SESSION[id]' AND kode_kehadiran='A'"));
  }
  
  // Today's schedule
  $hari_ini = date('w');
  $nama_hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
  $hari_now = $nama_hari[$hari_ini];
?>
<div class="col-md-12">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Selamat Datang, <b><?php echo $siswa ? $siswa['nama'] : 'Siswa'; ?></b>!</h3>
        </div>
        <div class="box-body">
            <p>Anda login sebagai Siswa di Kelas <b><?php echo $kelas_nama; ?></b>. <br>
            Silahkan akses menu Akademik untuk melihat Jadwal dan Materi/Tugas.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo $total_mapel; ?></h3>
          <p>Mata Pelajaran</p>
        </div>
        <div class="icon">
          <i class="fa fa-book"></i>
        </div>
        <a href="index.php?view=jadwalsiswa" class="small-box-footer">Lihat Jadwal <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_tugas; ?></h3>
          <p>Tugas</p>
        </div>
        <div class="icon">
          <i class="fa fa-file-text"></i>
        </div>
        <a href="index.php?view=bahantugas&act=listbahantugassiswa" class="small-box-footer">Lihat Tugas <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3><?php echo $total_hadir; ?></h3>
          <p>Kehadiran</p>
        </div>
        <div class="icon">
          <i class="fa fa-check"></i>
        </div>
        <a href="index.php?view=absensisiswa" class="small-box-footer">Lihat Absensi <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-red">
        <div class="inner">
          <h3><?php echo $total_alpha; ?></h3>
          <p>Alpha</p>
        </div>
        <div class="icon">
          <i class="fa fa-times"></i>
        </div>
        <a href="index.php?view=absensisiswa" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
</div>

<!-- Today's Schedule -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Jadwal Pelajaran Hari Ini (<?php echo $hari_now; ?>)</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style='width:30px'>No</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Jam</th>
                            <th>Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($kode_kelas && $sem) {
                            $jadwal_hari_ini = mysql_query("SELECT a.*, b.namamatapelajaran, c.nama_guru, d.nama_ruangan
                                                            FROM rb_jadwal_pelajaran a
                                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                                            JOIN rb_guru c ON a.nip=c.nip
                                                            JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                            WHERE a.kode_kelas='$kode_kelas'
                                                            AND a.id_tahun_akademik='$sem[id_tahun_akademik]'
                                                            AND a.hari='$hari_now'
                                                            ORDER BY a.jam_mulai ASC");
                            $no = 1;
                            if (mysql_num_rows($jadwal_hari_ini) > 0) {
                                while ($j = mysql_fetch_array($jadwal_hari_ini)) {
                                    echo "<tr>
                                            <td>$no</td>
                                            <td>$j[namamatapelajaran]</td>
                                            <td>$j[nama_guru]</td>
                                            <td>$j[jam_mulai] - $j[jam_selesai]</td>
                                            <td>$j[nama_ruangan]</td>
                                          </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center text-muted'>Tidak ada jadwal pelajaran hari ini</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center text-muted'>Data kelas tidak ditemukan</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>