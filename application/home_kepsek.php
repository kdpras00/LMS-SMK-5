<?php
  // Statistics info boxes (Same as Admin)
?>
<div class='row'>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
      <div class="info-box-content">
      <?php $siswa = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM rb_siswa")); ?>
        <span class="info-box-text">Siswa</span>
        <span class="info-box-number"><?php echo $siswa['total']; ?></span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><i class="fa fa-user"></i></span>
      <div class="info-box-content">
      <?php $guru = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM rb_guru")); ?>
        <span class="info-box-text">Guru</span>
        <span class="info-box-number"><?php echo $guru['total']; ?></span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>
      <div class="info-box-content">
      <?php $upload = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM rb_elearning")); ?>
        <span class="info-box-text">Bahan Ajar</span>
        <span class="info-box-number"><?php echo $upload['total']; ?></span>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-red"><i class="fa fa-list"></i></span>
      <div class="info-box-content">
      <?php $jurnal = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM rb_journal_list")); ?>
        <span class="info-box-text">Total Jurnal</span>
        <span class="info-box-number"><?php echo $jurnal['total']; ?></span>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Latest Journal Activities -->
  <div class="col-md-6">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Aktifitas Jurnal KBM Terbaru</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table no-margin table-striped">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Kelas</th>
                <th>Guru</th>
                <th>Materi</th>
              </tr>
            </thead>
            <tbody>
            <?php 
              $tampil = mysql_query("SELECT a.*, c.nama_guru, d.nama_kelas, e.namamatapelajaran 
                                     FROM rb_journal_list a 
                                     JOIN rb_jadwal_pelajaran b ON a.kodejdwl=b.kodejdwl 
                                     JOIN rb_guru c ON b.nip=c.nip 
                                     JOIN rb_kelas d ON b.kode_kelas=d.kode_kelas 
                                     JOIN rb_mata_pelajaran e ON b.kode_pelajaran=e.kode_pelajaran 
                                     ORDER BY a.tanggal DESC, a.jam_ke DESC LIMIT 5");
              while($r=mysql_fetch_array($tampil)){
                echo "<tr>
                        <td>".tgl_indo($r['tanggal'])."</td>
                        <td>$r[nama_kelas] <br> <small class='text-muted'>$r[namamatapelajaran]</small></td>
                        <td>$r[nama_guru]</td>
                        <td>$r[materi]</td>
                      </tr>";
              }
            ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="box-footer clearfix">
        <a href="index.php?view=journalkbm" class="btn btn-sm btn-default btn-flat pull-right">Lihat Semua Jurnal</a>
      </div>
    </div>
  </div>

  <!-- Latest Uploads -->
  <div class="col-md-6">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">Upload Bahan Ajar Terbaru</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table no-margin table-striped">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>File</th>
                <th>Kelas</th>
                <th>Guru</th>
              </tr>
            </thead>
            <tbody>
            <?php 
              $tampil_upload = mysql_query("SELECT a.*, c.nama_guru, d.nama_kelas, e.namamatapelajaran 
                                     FROM rb_elearning a 
                                     JOIN rb_jadwal_pelajaran b ON a.kodejdwl=b.kodejdwl 
                                     JOIN rb_guru c ON b.nip=c.nip 
                                     JOIN rb_kelas d ON b.kode_kelas=d.kode_kelas 
                                     JOIN rb_mata_pelajaran e ON b.kode_pelajaran=e.kode_pelajaran 
                                     ORDER BY a.tanggal_tugas DESC LIMIT 5");
              while($r=mysql_fetch_array($tampil_upload)){
                echo "<tr>
                        <td>".tgl_indo($r['tanggal_tugas'])."</td>
                        <td>$r[nama_file] <br> <a href='download.php?file=$r[file_upload]' class='btn btn-xs btn-primary'><i class='fa fa-download'></i> Download</a></td>
                        <td>$r[nama_kelas] <br> <small class='text-muted'>$r[namamatapelajaran]</small></td>
                        <td>$r[nama_guru]</td>
                      </tr>";
              }
            ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="box-footer clearfix">
        <a href="index.php?view=bahantugas" class="btn btn-sm btn-default btn-flat pull-right">Lihat Semua Bahan</a>
      </div>
    </div>
  </div>
</div>
