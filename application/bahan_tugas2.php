<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$get_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$get_jdwl = isset($_GET['jdwl']) ? $_GET['jdwl'] : '';
$get_id = isset($_GET['id']) ? $_GET['id'] : '';
$get_kd = isset($_GET['kd']) ? $_GET['kd'] : '';
$get_edit = isset($_GET['edit']) ? $_GET['edit'] : '';
$get_ide = isset($_GET['ide']) ? $_GET['ide'] : '';
$get_hapus = isset($_GET['hapus']) ? $_GET['hapus'] : '';

if ($act==''){ 
cek_session_admin();
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php if ($get_kelas != '' AND $get_tahun != ''){ echo "Perangkat Pembelajaran Guru 2025"; }else{ echo "Perangkat Pembelajaran Guru ".date('Y'); } ?></h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='rpp'>
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
                    
                    <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
                  </form>

                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Jadwal Pelajaran</th>
                        <th>Nama Guru</th>
                        <th>Nama File Diupload</th>
                        <th>File Upload</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    $tampil = null;
                    if ($get_tahun != ''){
                      $tampil = mysql_query("SELECT * FROM rb_elearning");
                    }
                    if ($tampil) {
                        $no = 1;
                        while($r=mysql_fetch_array($tampil)){
                        // Using isset for safety although table schema suggests these columns exist
                        $id_elearning = isset($r['id_elearning']) ? $r['id_elearning'] : '';
                        $nama_file = isset($r['nama_file']) ? $r['nama_file'] : '';
                        $file_upload = isset($r['file_upload']) ? $r['file_upload'] : '';
                        
                        echo "<tr><td>$no</td>
                                  <td>$id_elearning</td>
                                  <td>$nama_file</td>
                                  <td>$file_upload</td></tr>";
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

}elseif($act=='tambah'){
cek_session_guru();
if (isset($_POST['tambah'])){
      $dir_gambar = 'files/';
      $filename = basename($_FILES['c']['name']);
      $filenamee = date("YmdHis").'-'.basename($_FILES['c']['name']);
      $uploadfile = $dir_gambar . $filenamee;
      if ($filename != ''){      
        if (move_uploaded_file($_FILES['c']['tmp_name'], $uploadfile)) {
          mysql_query("INSERT INTO rb_elearning VALUES (NULL,'$_POST[a]','$get_jdwl','$_POST[b]','$filenamee','$_POST[d]','$_POST[e]','$_POST[f]')");
            echo "<script>document.location='index.php?&act=listbahantugas2&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
        }else{
          echo "<script>window.alert('Gagal Tambahkan Data Bahan dan Tugas.');
                      window.location='index.php?&act=listbahantugas2&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."'</script>";
        }
      }else{
        mysql_query("INSERT INTO rb_elearning VALUES (NULL,'$_POST[a]','$get_jdwl','$_POST[b]','','$_POST[d]','$_POST[e]','$_POST[f]')");
        echo "<script>document.location='index.php?&act=listbahantugas2&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
      }
  }

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Bahan dan Tugas</h3>
                </div>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
              <div class='box-body'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='120px' scope='row'>Kategori</th> <td><select class='form-control' name='a'> 
                             <option value='0' selected>- Pilih Kategori Tugas -</option>"; 
                              $kategori = mysql_query("SELECT * FROM rb_kategori_elearning");
                                  while($a = mysql_fetch_array($kategori)){
                                       echo "<option value='$a[id_kategori_elearning]'>$a[nama_kategori_elearning]</option>";
                                  }
                             echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Nama File</th>        <td><input type='text' class='form-control' name='b'></td></tr>
                    <tr><th scope='row'>File</th>             <td><div style='position:relative;'>
                                                                          <a class='btn btn-primary' href='javascript:;'>
                                                                            <i class='fa fa-search'></i> Cari File Bahan atau Tugas..."; ?>
                                                                            <input type='file' class='files' name='c' onchange='$("#upload-file-info").html($(this).val());'>
                                                                          <?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
                                                                        </div>
                    </td></tr>
                    <tr><th scope='row'>Waktu Mulai</th>      <td><input type='text' class='form-control' value='".date("Y-m-d H:i:s")."' name='d'></td></tr>
                    <tr><th scope='row'>Waktu Selesai</th>    <td><input type='text' class='form-control' value='".date("Y-m-d H:i:s")."' name='e'></td></tr>
                    <tr><th scope='row'>Keterangan</th>       <td><input type='text' class='form-control' name='f'></td></tr>
                    
                  </tbody>
                  </table>
                </div>
                
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?'><button class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div></div>";
}elseif($act=='edit'){
cek_session_guru();
if (isset($_POST['update'])){
      $dir_gambar = 'files/';
      $filename = basename($_FILES['c']['name']);
      $filenamee = date("YmdHis").'-'.basename($_FILES['c']['name']);
      $uploadfile = $dir_gambar . $filenamee;
      if ($filename != ''){      
        if (move_uploaded_file($_FILES['c']['tmp_name'], $uploadfile)) {
          mysql_query("UPDATE rb_elearning SET id_kategori_elearning = '$_POST[a]',
                                               kodejdwl              = '$get_jdwl',
                                               nama_file             = '$_POST[b]',
                                               file_upload           = '$filenamee',
                                               tanggal_tugas         = '$_POST[d]',
                                               tanggal_selesai       = '$_POST[e]',
                                               keterangan            = '$_POST[f]' where id_elearning='$get_edit'");
            echo "<script>document.location='index.php?&act=listbahantugas2&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
        }else{
          echo "<script>window.alert('Gagal Update Data Bahan dan Tugas.');
                      window.location='index.php?&act=listbahantugas2&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."'</script>";
        }
      }else{
        mysql_query("UPDATE rb_elearning SET id_kategori_elearning = '$_POST[a]',
                                               kodejdwl              = '$get_jdwl',
                                               nama_file             = '$_POST[b]',
                                               tanggal_tugas         = '$_POST[d]',
                                               tanggal_selesai       = '$_POST[e]',
                                               keterangan            = '$_POST[f]' where id_elearning='$get_edit'");
        echo "<script>document.location='index.php?&act=listbahantugas2&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";

      }
  }

$edit = mysql_query("SELECT * FROM rb_elearning a JOIN rb_kategori_elearning b ON a.id_kategori_elearning=b.id_kategori_elearning where a.id_elearning='$get_edit'");
    $s = mysql_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Bahan dan Tugas</h3>
                </div>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
              <div class='box-body'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='120px' scope='row'>Kategori</th> <td><select class='form-control' name='a'> 
                             <option value='0' selected>- Pilih Kategori Tugas -</option>"; 
                              $kategori = mysql_query("SELECT * FROM rb_kategori_elearning");
                                  while($a = mysql_fetch_array($kategori)){
                                    if ($s['id_kategori_elearning']==$a['id_kategori_elearning']){
                                       echo "<option value='$a[id_kategori_elearning]' selected>$a[nama_kategori_elearning]</option>";
                                    }else{
                                       echo "<option value='$a[id_kategori_elearning]'>$a[nama_kategori_elearning]</option>";
                                    }
                                  }
                             echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Nama File</th>        <td><input type='text' class='form-control' name='b' value='$s[nama_file]'></td></tr>
                    <tr><th scope='row'>Ganti File</th>             <td><div style='position:relative;'>
                                                                          <a class='btn btn-primary' href='javascript:;'>
                                                                            <i class='fa fa-search'></i> <b>Ganti File :</b> $s[file_upload]"; ?>
                                                                            <input type='file' class='files' name='c' onchange='$("#upload-file-info").html($(this).val());'>
                                                                          <?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
                                                                        </div>
                    </td></tr>
                    <tr><th scope='row'>Waktu Mulai</th>      <td><input type='text' class='form-control' value='$s[tanggal_tugas]' name='d'></td></tr>
                    <tr><th scope='row'>Waktu Selesai</th>    <td><input type='text' class='form-control' value='$s[tanggal_selesai]' name='e'></td></tr>
                    <tr><th scope='row'>Keterangan</th>       <td><input type='text' class='form-control' name='f' value='$s[keterangan]'></td></tr>
                    
                  </tbody>
                  </table>
                </div>
                
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?'><button class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div></div>";
}elseif($act=='listbahantugas2guru'){ 
cek_session_admin();
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php if ($get_tahun != ''){ echo "Bahan dan Tugas"; }else{ echo "Bahan dan Tugas Pada ".date('Y'); } ?></h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='rpp'>
                    <input type="hidden" name='act' value='listbahantugas2guru'>
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
                    <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
                  </form>

                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
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
                        <th>Total</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    // Ensure kurikulum is set
                    $kode_kurikulum = isset($kurikulum['kode_kurikulum']) ? $kurikulum['kode_kurikulum'] : '';
                    
                    if ($get_tahun != ''){
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.nip='$_SESSION[id]' 
                                                    AND a.id_tahun_akademik='$get_tahun' 
                                                      AND b.kode_kurikulum='$kode_kurikulum'
                                                        ORDER BY a.hari DESC");
                    
                    }else{
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where b.kode_kurikulum='$kode_kurikulum' AND a.nip='$_SESSION[id]' AND a.id_tahun_akademik LIKE '".date('Y')."%' ORDER BY a.hari DESC");
                    }
                    $no = 1;
                    if(isset($tampil) && $tampil){
                        while($r=mysql_fetch_array($tampil)){
                        $total = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning"));
                        $nama_file = isset($r['nama_file']) ? $r['nama_file'] : '';
                        
                        echo "<tr><td>$no</td>
                                  <td>$r[namamatapelajaran]</td>
                                  <td>$nama_file</td>
                                  <td>$r[nama_guru]</td>
                                  <td>$r[hari]</td>
                                  <td>$r[jam_mulai]</td>
                                  <td>$r[jam_selesai]</td>
                                  <td>$r[nama_ruangan]</td>
                                  <td>$r[id_tahun_akademik]</td>
                                  <td style='color:red'>$total Record</td>
                                  <td><a class='btn btn-success btn-xs' title='List Bahan dan Tugas' href='index.php?&act=listbahantugas&jdwl=$r[kodejdwl]&id=$r[kode_kelas]&kd=$r[kode_pelajaran]'><span class='glyphicon glyphicon-th'></span> Tampilkan</a></td>
                              </tr>";
                          $no++;
                          }
                    }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                </div>
            </div>
                                
<?php

}elseif($act=='jawaban'){
cek_session_guru();
    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$get_id'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$get_kd'"));
    $t = mysql_fetch_array(mysql_query("SELECT * FROM rb_elearning where id_elearning='$get_ide'"));
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>List Upload Bahan dan Tugas</b></h3>
                </div>
              <div class='box-body'>

              <div class='col-md-12'>
              <table class='table table-condensed table-hover'>
                  <tbody>
                    <input type='hidden' name='id' value='$get_id'>
                    <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                    <tr><th scope='row'>Nama Kelas</th>               <td>$d[nama_kelas]</td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>           <td>$m[namamatapelajaran]</td></tr>
                    <tr><th scope='row'>Nama Tugas</th>               <td>$t[nama_file]</td></tr>
                  </tbody>
              </table>
              </div>

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
              <input type='hidden' name='kelas' value='$get_id'>
              <input type='hidden' name='pelajaran' value='$get_kd'>
                <div class='col-md-12'>
                  <table id='example1' class='table table-condensed table-bordered table-striped'>
                      <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Pesan Siswa</th>
                        <th>Waktu Kirim</th>";
                        if ($_SESSION['level']!='kepala'){
                          echo "<th>Action</th>";
                        }
                      echo "</tr>
                    </thead>
                    <tbody>";
                    
                    $no = 1;
                    $tampil = mysql_query("SELECT * FROM rb_elearning_jawab a JOIN rb_siswa b ON a.nisn=b.nisn where a.id_elearning='$get_ide' ORDER BY a.id_elearning_jawab DESC");
                    while($r=mysql_fetch_array($tampil)){
                      echo "<tr>
                              <td>$no</td>
                              <td style='color:red'>$r[nisn]</td>
                              <td>$r[nama]</td>
                              <td>$r[keterangan]</td>
                              <td>$r[waktu] WIB</td>
                              <td>
                                <a class='btn btn-info btn-xs' title='Download Bahan dan Tugas' href='download.php?file=$r[file_tugas]'><span class='glyphicon glyphicon-download'></span> Download</a>
                              </td></tr>";
                        $no++;
                      }

                      if ($get_hapus != ''){
                        mysql_query("DELETE FROM rb_elearning where id_elearning='$get_hapus'");
                        echo "<script>document.location='index.php?&act=listbahantugas2&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
                      }

                    echo "</tbody>
                  </table>
                </div>
                </form>
              </div>
            </div></div>";
}
?>