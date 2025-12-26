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
  
  // Stats
  $total_mapel = 0;
  $total_tugas = 0;
  
  if ($kode_kelas) {
      $total_mapel_query = mysql_query("SELECT * FROM rb_jadwal_pelajaran WHERE kode_kelas = '$kode_kelas' AND id_tahun_akademik LIKE '".date('Y')."%'");
      $total_mapel = $total_mapel_query ? mysql_num_rows($total_mapel_query) : 0;
      
      $total_tugas_query = mysql_query("SELECT * FROM rb_elearning a JOIN rb_jadwal_pelajaran b ON a.kodejdwl=b.kodejdwl WHERE b.kode_kelas = '$kode_kelas'");
      $total_tugas = $total_tugas_query ? mysql_num_rows($total_tugas_query) : 0;
  }
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
          <p>Materi & Tugas</p>
        </div>
        <div class="icon">
          <i class="fa fa-file-text"></i>
        </div>
        <a href="index.php?view=bahantugas&act=listbahantugassiswa" class="small-box-footer">Lihat Tugas <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
</div>