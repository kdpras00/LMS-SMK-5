<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$get_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$get_id = isset($_GET['id']) ? $_GET['id'] : '';
$get_kd = isset($_GET['kd']) ? $_GET['kd'] : '';
$get_jdwl = isset($_GET['jdwl']) ? $_GET['jdwl'] : '';
$get_ide = isset($_GET['ide']) ? $_GET['ide'] : '';
$get_edit = isset($_GET['edit']) ? $_GET['edit'] : '';
$get_hapus = isset($_GET['hapus']) ? $_GET['hapus'] : '';
$get_idj = isset($_GET['idj']) ? $_GET['idj'] : '';

if (empty($act)){ 
    cek_session_admin();
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Materi dan Tugas - Semua Jadwal</h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='bahantugas'>
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
                        <th>Total</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    // Build filter conditions
                    $filter_conditions = "1=1"; // Always true
                    
                    if ($get_kelas != ''){
                        $filter_conditions .= " AND a.kode_kelas='$get_kelas'";
                    }
                    
                    if ($get_tahun != ''){
                        $filter_conditions .= " AND a.id_tahun_akademik='$get_tahun'";
                    }
                    
                    $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                          JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                            JOIN rb_guru c ON a.nip=c.nip 
                                              JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                where $filter_conditions 
                                                  ORDER BY a.id_tahun_akademik DESC, a.hari DESC");
                    
                    if (isset($tampil) && $tampil) {
                        $no = 1;
                        while($r=mysql_fetch_array($tampil)){
                        $total = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning where kodejdwl='$r[kodejdwl]'"));
                        echo "<tr><td>$no</td>
                                  <td>$r[namamatapelajaran]</td>
                                  <td>$r[nama_kelas]</td>
                                  <td>$r[nama_guru]</td>
                                  <td>$r[hari]</td>
                                  <td>$r[jam_mulai]</td>
                                  <td>$r[jam_selesai]</td>
                                  <td>$r[nama_ruangan]</td>
                                  <td style='color:red'>$total Record</td>";
                                  echo "<td style='width:70px !important'><center>
                                          <a class='btn btn-success btn-xs' title='List Bahan dan Tugas' href='index.php?view=bahantugas&act=listbahantugas&jdwl=$r[kodejdwl]&kd=$r[kode_pelajaran]&id=$r[kode_kelas]'><span class='glyphicon glyphicon-th'></span> List Bahan dan Tugas</a>
                                        </center></td>";
                                echo "</tr>";
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
}elseif($act=='listbahantugas'){
    cek_session_siswa();
    // Logic for Edit Nilai (Save)
    if (isset($_POST['updatenilai'])){
        mysql_query("UPDATE rb_elearning_jawab SET nilai='$_POST[nilai]' WHERE id_elearning_jawab='$_POST[idj]'");
        echo "<script>document.location='index.php?view=bahantugas&act=jawaban&id=".$get_id."&kd=".$get_kd."&jdwl=".$get_jdwl."&ide=".$get_ide."';</script>";
    }

    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$get_id'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$get_kd'"));
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>List Upload Bahan dan Tugas</b></h3>";
                  if (isset($_SESSION['level']) && $_SESSION['level']=='guru'){
                    echo "<a style='margin-left:4px' class='btn btn-danger btn-sm pull-right' href='index.php?view=bahantugas&act=listbahantugasguru'>Kembali</a>";
                  }elseif(isset($_SESSION['level']) && $_SESSION['level']=='siswa'){
                    echo "<a style='margin-left:4px' class='btn btn-danger btn-sm pull-right' href='index.php?view=bahantugas&act=listbahantugassiswa'>Kembali</a>";
                  }else{
                    echo "<a style='margin-left:4px' class='btn btn-danger btn-sm pull-right' href='index.php?view=bahantugas'>Kembali</a>";
                  }

                  if (isset($_SESSION['level']) && $_SESSION['level']=='guru'){
                    echo "<a class='pull-right btn btn-primary btn-sm' href='index.php?view=bahantugas&act=tambah&jdwl=$get_jdwl&id=$get_id&kd=$get_kd'>Tambahkan Data</a>";
                  }
                echo "</div>
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
              <input type='hidden' name='kelas' value='$get_id'>
              <input type='hidden' name='pelajaran' value='$get_kd'>
                <div class='col-md-12'>
                  <table id='example1' class='table table-condensed table-bordered table-striped'>
                      <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Judul Tugas/Materi</th>
                        <th>Tipe</th>
                        <th>Kelas</th>
                        <th>Tgl Upload</th>
                        <th>File</th>
                        <th>Batas Waktu</th>";
                        if ($_SESSION['level']!='kepala'){
                          echo "<th>Action</th>";
                        }
                      echo "</tr>
                    </thead>
                    <tbody>";
                    
                    $no = 1;
                    $tampil = mysql_query("SELECT * FROM rb_elearning a JOIN rb_kategori_elearning b ON a.id_kategori_elearning=b.id_kategori_elearning where kodejdwl='$get_jdwl' ORDER BY a.id_elearning");
                    while($r=mysql_fetch_array($tampil)){
                      echo "<tr>
                              <td>$no</td>
                              <td>$r[nama_file]</td>
                              <td>$r[nama_kategori_elearning]</td>
                              <td>$d[nama_kelas]</td>
                              <td>$r[tanggal_tugas] WIB</td>
                              <td><a class='btn btn-info btn-xs' href='download.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Unduh</a></td>
                              <td>$r[tanggal_selesai] WIB</td>";
                          if ($_SESSION['level']=='superuser'){
                              echo "<td>";
                                if ($r['id_kategori_elearning']=='1'){
                                  echo "<a style='margin-right:5px; width:106px' class='btn btn-info btn-xs' title='Download Bahan dan Tugas' href='download.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Download </a>";
                                }else{
                                  echo "<a style='margin-right:5px; width:106px' class='btn btn-success btn-xs' title='Jawaban Bahan dan Tugas' href='index.php?view=bahantugas&act=kirimjawaban&jdwl=$get_jdwl&id=$get_id&kd=$get_kd&ide=$r[id_elearning]'><span class='glyphicon glyphicon-upload'></span> Jawaban </a>";
                                }
                              echo "</td>
                            </tr>";
                          }elseif ($_SESSION['level']=='siswa'){
                              $sekarangwaktu = date("YmdHis");
                              $bataswaktu1 = str_replace('-','',$r['tanggal_selesai']);
                              $bataswaktu2 = str_replace(':','',$bataswaktu1);
                              $bataswaktu3 = str_replace(' ','',$bataswaktu2);
                              
                              if ($r['id_kategori_elearning']=='1'){
                                  echo "<td><a style='width:167px' class='btn btn-info btn-xs' title='Download Bahan dan Tugas' href='download.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Download</a></td></tr>";
                              }elseif ($sekarangwaktu < $bataswaktu3 OR $bataswaktu3 == '00000000000000'){
                                  echo "<td><a class='btn btn-info btn-xs' title='Download Bahan dan Tugas' href='download.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span> Download</a>
                                            <a class='btn btn-success btn-xs' title='Kirim Bahan dan Tugas' href='index.php?view=bahantugas&act=kirim&jdwl=$get_jdwl&id=$get_id&kd=$get_kd&ide=$r[id_elearning]'><span class='glyphicon glyphicon-upload'></span> Kirim Tugas</a></td></tr>";
                              }else{
                                  echo "<td><a style='width:167px' class='btn btn-danger btn-xs' title='Waktu Habis' href=''><span class='glyphicon glyphicon-remove'></span> Waktu Habis</a></td></tr>";
                              }
                          }elseif ($_SESSION['level']=='guru'){
                              if ($r['id_kategori_elearning']=='1'){
                                  echo "<td>";
                              }else{
                                  echo "<td>
                                            <a class='btn btn-success btn-xs' title='Kirim Bahan dan Tugas' href='index.php?view=bahantugas&act=jawaban&jdwl=$get_jdwl&id=$get_id&kd=$get_kd&ide=$r[id_elearning]'><span class='glyphicon glyphicon-upload'></span> Koreksi</a>";
                              }
                                  echo "<a style='margin-left:3px' class='btn btn-warning btn-xs' title='Edit $r[nama_kategori_elearning]' href='index.php?view=bahantugas&act=edit&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."&edit=$r[id_elearning]'><span class='glyphicon glyphicon-edit'></span></a>
                                        <a class='btn btn-danger btn-xs' title='Delete $r[nama_kategori_elearning]' href='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."&hapus=$r[id_elearning]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a></td></tr>";
                          }
                        $no++;
                      }

                      if ($get_hapus != ''){
                        mysql_query("DELETE FROM rb_elearning where id_elearning='$get_hapus'");
                        echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
                      }

                    echo "</tbody>
                  </table>
                </div>
              </div>
              </form>
            </div>";

}elseif($act=='tambah'){
    cek_session_guru();
    if (isset($_POST['tambah'])){
          // Validasi input
          if (!isset($_POST['a']) || $_POST['a'] == '0' || $_POST['a'] == ''){
              echo "<script>window.alert('Silakan pilih kategori terlebih dahulu.');
                          window.location='index.php?view=bahantugas&act=tambah&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."'</script>";
              exit;
          }
          
          if (!isset($_POST['b']) || trim($_POST['b']) == ''){
              echo "<script>window.alert('Nama file tidak boleh kosong.');
                          window.location='index.php?view=bahantugas&act=tambah&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."'</script>";
              exit;
          }
          
          $dir_gambar = 'files/';
          $filename = basename($_FILES['c']['name']);
          $filenamee = date("YmdHis").'-'.basename($_FILES['c']['name']);
          $uploadfile = $dir_gambar . $filenamee;
          
          $kategori = mysql_real_escape_string($_POST['a']);
          $nama_file = mysql_real_escape_string($_POST['b']);
          $waktu_mulai = mysql_real_escape_string($_POST['d']);
          $waktu_selesai = mysql_real_escape_string($_POST['e']);
          $keterangan = mysql_real_escape_string($_POST['f']);
          
          if ($filename != ''){      
            if (move_uploaded_file($_FILES['c']['tmp_name'], $uploadfile)) {
              $query = "INSERT INTO rb_elearning VALUES (NULL,'$kategori','$get_jdwl','$nama_file','$filenamee','$waktu_mulai','$waktu_selesai','$keterangan')";
              $result = mysql_query($query);
              
              if ($result){
                echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
              } else {
                echo "<script>window.alert('Gagal menyimpan data: " . mysql_error() . "');
                            window.location='index.php?view=bahantugas&act=tambah&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."'</script>";
              }
            }else{
              echo "<script>window.alert('Gagal upload file. Pastikan folder files/ memiliki permission yang benar.');
                          window.location='index.php?view=bahantugas&act=tambah&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."'</script>";
            }
          }else{
            $query = "INSERT INTO rb_elearning VALUES (NULL,'$kategori','$get_jdwl','$nama_file','','$waktu_mulai','$waktu_selesai','$keterangan')";
            $result = mysql_query($query);
            
            if ($result){
              echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
            } else {
              echo "<script>window.alert('Gagal menyimpan data: " . mysql_error() . "');
                          window.location='index.php?view=bahantugas&act=tambah&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."'</script>";
            }
          }
      }

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Bahan dan Tugas</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
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
                    <tr><th scope='row'>File</th>             <td><div style='position:relative;''>
                                                                          <a class='btn btn-primary' href='javascript:;'>
                                                                            <i class='fa fa-search'></i> Cari File Bahan atau Tugas...
                                                                            <input type='file' class='files' name='c' onchange='$(\"#upload-file-info\").html($(this).val());'>
                                                                          </a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
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
                    <a href='index.php?view=bahantugas'><button class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
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
                echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
            }else{
              echo "<script>window.alert('Gagal Update Data Bahan dan Tugas.');
                          window.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."'</script>";
            }
          }else{
            mysql_query("UPDATE rb_elearning SET id_kategori_elearning = '$_POST[a]',
                                                   kodejdwl              = '$get_jdwl',
                                                   nama_file             = '$_POST[b]',
                                                   tanggal_tugas         = '$_POST[d]',
                                                   tanggal_selesai       = '$_POST[e]',
                                                   keterangan            = '$_POST[f]' where id_elearning='$get_edit'");
            echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";

          }
      }

    $edit = mysql_query("SELECT * FROM rb_elearning a JOIN rb_kategori_elearning b ON a.id_kategori_elearning=b.id_kategori_elearning where a.id_elearning='$get_edit'");
    $s = mysql_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Bahan dan Tugas</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
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
                    <tr><th scope='row'>Ganti File</th>             <td><div style='position:relative;''>
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
                    <a href='index.php?view=bahantugas'><button class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}elseif($act=='listbahantugasguru'){ 
    cek_session_guru();
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php if ($get_tahun != ''){ echo "Bahan dan Tugas"; }else{ echo "Bahan dan Tugas Pada ".date('Y'); } ?></h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='bahantugas'>
                    <input type="hidden" name='act' value='listbahantugasguru'>
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
                    // Safety check for $kurikulum
                    $kode_kurikulum = isset($kurikulum['kode_kurikulum']) ? $kurikulum['kode_kurikulum'] : '';
                    $session_id = $_SESSION['id'];

                    if ($get_tahun != ''){
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.nip='$session_id' 
                                                    AND a.id_tahun_akademik='$get_tahun' 
                                                      AND b.kode_kurikulum='$kode_kurikulum'
                                                        ORDER BY a.hari DESC");
                    
                    }else{
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where b.kode_kurikulum='$kode_kurikulum' AND a.nip='$session_id' AND a.id_tahun_akademik LIKE '".date('Y')."%' ORDER BY a.hari DESC");
                    }
                    $no = 1;
                    if ($tampil) {
                        while($r=mysql_fetch_array($tampil)){
                        $total = mysql_num_rows(mysql_query("SELECT * FROM rb_elearning where kodejdwl='$r[kodejdwl]'"));
                        echo "<tr><td>$no</td>
                                  <td>$r[namamatapelajaran]</td>
                                  <td>$r[nama_kelas]</td>
                                  <td>$r[nama_guru]</td>
                                  <td>$r[hari]</td>
                                  <td>$r[jam_mulai]</td>
                                  <td>$r[jam_selesai]</td>
                                  <td>$r[nama_ruangan]</td>
                                  <td>$r[id_tahun_akademik]</td>
                                  <td style='color:red'>$total Record</td>
                                  <td><a class='btn btn-success btn-xs' title='List Bahan dan Tugas' href='index.php?view=bahantugas&act=listbahantugas&jdwl=$r[kodejdwl]&id=$r[kode_kelas]&kd=$r[kode_pelajaran]'><span class='glyphicon glyphicon-th'></span> Tampilkan</a></td>
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
}elseif($act=='listbahantugassiswa'){ 
    cek_session_siswa();
?>
             <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php if (isset($_GET['kelas']) AND isset($_GET['tahun'])){ echo "Materi dan Tugas"; }else{ echo "Materi dan Tugas ".date('Y'); } ?></h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='bahantugas'>
                    <input type="hidden" name='act' value='listbahantugassiswa'>
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
                        <th>Mata Pelajaran</th>
                        <th>Judul Materi/Tugas</th>
                        <th>Tipe</th>
                        <th>Batas Waktu</th>
                        <th>Status Pengumpulan</th>
                        <th>Nilai</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    // Filter Tahun
                    if ($get_tahun != ''){
                        $tahun_filter = "AND b.id_tahun_akademik='$get_tahun'";
                    }else{
                        $tahun_filter = "AND b.id_tahun_akademik LIKE '".date('Y')."%'";
                    }

                    $session_kode_kelas = $_SESSION['kode_kelas'];
                    
                    // Consolidated Query
                    $tampil = mysql_query("SELECT a.*, c.namamatapelajaran, d.nama_kategori_elearning, b.kode_kelas, c.kode_pelajaran, b.kodejdwl 
                                            FROM rb_elearning a 
                                            JOIN rb_jadwal_pelajaran b ON a.kodejdwl=b.kodejdwl 
                                            JOIN rb_mata_pelajaran c ON b.kode_pelajaran=c.kode_pelajaran 
                                            JOIN rb_kategori_elearning d ON a.id_kategori_elearning=d.id_kategori_elearning
                                            WHERE b.kode_kelas='$session_kode_kelas' 
                                            $tahun_filter
                                            ORDER BY a.tanggal_tugas DESC");

                    $no = 1;
                    $session_id = $_SESSION['id'];
                    while($r=mysql_fetch_array($tampil)){
                        // Check Status Pengumpulan & Nilai
                        $jawab = mysql_fetch_array(mysql_query("SELECT * FROM rb_elearning_jawab WHERE id_elearning='$r[id_elearning]' AND nisn='$session_id'"));
                        
                        // Cek apakah ini Materi (Bahan) atau Tugas
                        if ($r['id_kategori_elearning'] == '1'){ // Materi/Bahan
                            $status_pengumpulan = "-";
                            $nilai = "-";
                            $style_status = "color:black";
                        } else { // Tugas
                            $status_pengumpulan = "Belum Mengumpulkan";
                            $nilai = "-";
                            $style_status = "color:red";

                            if (isset($jawab['id_elearning_jawab']) && $jawab['id_elearning_jawab'] != ''){
                                $status_pengumpulan = "Sudah Mengumpulkan <br> <small>($jawab[waktu] WIB)</small>";
                                $style_status = "color:green";
                                
                                // Check if graded - nilai could be 0, so check if it's set and not empty string
                                if (isset($jawab['nilai']) && $jawab['nilai'] !== '' && $jawab['nilai'] !== null){
                                    $nilai = $jawab['nilai'];
                                } else {
                                    $nilai = "<span style='color:orange'>Belum Dinilai</span>";
                                }
                            }
                        }

                        // Determine Action
                        $sekarangwaktu = date("YmdHis");
                        $bataswaktu1 = str_replace('-','',$r['tanggal_selesai']);
                        $bataswaktu2 = str_replace(':','',$bataswaktu1);
                        $bataswaktu3 = str_replace(' ','',$bataswaktu2);
                        
                        $action = "";
                        // Download always available
                        $action .= "<a class='btn btn-info btn-xs' style='margin-right:2px' title='Download' href='download.php?file=$r[file_upload]'><span class='glyphicon glyphicon-download'></span></a>";

                        if ($r['id_kategori_elearning'] != '1'){ // If not just 'Materi' (assuming 1 is Materi)
                             if ($sekarangwaktu < $bataswaktu3 OR $bataswaktu3 == '00000000000000'){
                                if(!isset($jawab['id_elearning_jawab']) || $jawab['id_elearning_jawab'] == ''){
                                   $action .= "<a class='btn btn-success btn-xs' title='Kirim Tugas' href='index.php?view=bahantugas&act=kirim&jdwl=$r[kodejdwl]&id=$r[kode_kelas]&kd=$r[kode_pelajaran]&ide=$r[id_elearning]'><span class='glyphicon glyphicon-upload'></span></a>";
                                }
                             }
                        }

                    echo "<tr><td>$no</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[nama_file]</td>
                              <td>$r[nama_kategori_elearning]</td>
                              <td>$r[tanggal_selesai] WIB</td>
                              <td style='$style_status'>$status_pengumpulan</td>
                              <td>$nilai</td>
                              <td>$action</td>
                          </tr>";
                      $no++;
                      }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                </div>
            </div>
                                
<?php
}elseif($act=='kirim'){
    cek_session_siswa();
    $session_id = $_SESSION['id'];
    $cek = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM rb_elearning_jawab where id_elearning='$get_ide' AND nisn='$session_id'"));
  if ($cek['total'] >= 1){
      echo "<script>window.alert('Maaf, Anda Sudah Mengirimkan Tugas ini Sebelumnya.');
                window.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."'</script>";
  }else{
    if (isset($_POST['kirimkan'])){
          $dir_gambar = 'files/';
          $filename = basename($_FILES['c']['name']);
          $filenamee = date("YmdHis").'-'.basename($_FILES['c']['name']);
          $uploadfile = $dir_gambar . $filenamee;
          if ($filename != ''){    
            $waktuu = date("Y-m-d H:i:s");  
            if (move_uploaded_file($_FILES['c']['tmp_name'], $uploadfile)) {
              mysql_query("INSERT INTO rb_elearning_jawab VALUES (NULL,'$get_ide','$session_id','$_POST[a]','$filenamee','$waktuu','0')");
                echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
            }else{
              echo "<script>window.alert('Gagal Kirimkan Data Tugas.');
                          window.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."'</script>";
            }
          }else{
            echo "<script>window.alert('Gagal Kirimkan Data Tugas.');
                          window.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."'</script>";

          }
      }

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Kirimkan Tugas</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width=120px scope='row'>Nama File</th>             <td><div style='position:relative;''>
                                                                          <a class='btn btn-primary' href='javascript:;'>
                                                                            <span class='glyphicon glyphicon-search'></span> Cari File Tugas yang akan dikirim..."; ?>
                                                                            <input type='file' class='files' name='c' onchange='$("#upload-file-info").html($(this).val());'>
                                                                          <?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
                                                                        </div>
                    </td></tr>
                    <tr><th scope='row'>Keterangan</th>       <td><textarea rows='5' class='form-control' name='a'></textarea></td></tr>
                    
                  </tbody>
                  </table>
                  <i><b style='color:red'>Catatan</b> : Tugas Hanya Bisa dikirimkan 1 (satu) kali saja.</i>
                </div>
                
              </div>
              <div class='box-footer'>
                    <button type='submit' name='kirimkan' class='btn btn-info'>Kirimkan Tugas</button>
                    <a href='index.php?view=bahantugas'><button class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
  }
}elseif($act=='kirimjawaban'){
    cek_session_guru();
    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$get_id'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$get_kd'"));
     echo "<div class='col-xs-12'>  
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>Daftar Siswa yang Mengrimkan Jawaban Tugas </h3>
                  <a class='btn btn-danger btn-sm pull-right' href='index.php?view=bahantugas&act=listbahantugas&jdwl=$get_jdwl&kd=$get_kd&id=$get_id'>Kembali</a>
                </div>

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

                <div class='box-body'>
                <table class='table table-bordered table-striped'>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Keterangan</th>
                        <th>Waktu Kirim</th>
                        <th>Action</th>
                      </tr>";
                    $tampil = mysql_query("SELECT * FROM rb_elearning_jawab a JOIN rb_siswa b ON a.nisn=b.nisn ORDER BY a.id_elearning_jawab DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[nisn]</td>
                              <td>$r[nama]</td>
                              <td>$r[keterangan]</td>
                              <td>$r[waktu] WIB</td>
                              <td style='width:70px !important'><center>
                                <a class='btn btn-success btn-xs' title='Download Tugas' href='download.php?file=$r[file_tugas]'><span class='glyphicon glyphicon-download'></span> Download</a>
                              </center></td>";
                            echo "</tr>";
                      $no++;
                      }

                  echo "</table>
                    </div>
                  </div>";   
      
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
                    <input type='hidden' name='id' value='$d[kode_kelas]'>
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
                        <th>Nama Siswa</th>
                        <th>Pesan Siswa</th>
                        <th>Waktu Kirim</th>
                        <th>File Jawaban</th>
                        <th>Nilai</th>";
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
                              <td>$r[nama]</td>
                              <td>$r[keterangan]</td>
                              <td>$r[waktu] WIB</td>
                              <td><a class='btn btn-info btn-xs' href='download.php?file=$r[file_tugas]'><span class='glyphicon glyphicon-download'></span> Unduh</a></td>
                              <td>$r[nilai]</td>
                              <td>
                                <a class='btn btn-warning btn-xs' title='Edit Nilai' href='index.php?view=bahantugas&act=editnilai&id=".$get_id."&kd=".$get_kd."&jdwl=".$get_jdwl."&ide=".$get_ide."&idj=".$r['id_elearning_jawab']."'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Hapus Jawaban' href='index.php?view=bahantugas&act=hapusjawaban&id=".$get_id."&kd=".$get_kd."&jdwl=".$get_jdwl."&ide=".$get_ide."&idj=".$r['id_elearning_jawab']."' onclick=\"return confirm('Yakin hapus?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </td></tr>";
                        $no++;
                      }

                      if ($get_hapus != ''){
                        mysql_query("DELETE FROM rb_elearning where id_elearning='$get_hapus'");
                        echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
                      }

                    echo "</tbody>
                  </table>
                </div>
              </div>
              </form>
            </div>";
} elseif ($act=='editnilai'){
    if (isset($_POST['updatenilai'])){
        mysql_query("UPDATE rb_elearning_jawab SET nilai='$_POST[nilai]' WHERE id_elearning_jawab='$_POST[idj]'");
        echo "<script>document.location='index.php?view=bahantugas&act=jawaban&id=".$get_id."&kd=".$get_kd."&jdwl=".$get_jdwl."&ide=".$get_ide."';</script>";
    }
    $j = mysql_fetch_array(mysql_query("SELECT * FROM rb_elearning_jawab WHERE id_elearning_jawab='$get_idj'"));
    echo "<div class='col-md-12'><div class='box box-primary'><div class='box-header'><h3 class='box-title'>Edit Nilai</h3></div>
    <div class='box-body'>
    <form method='POST' action=''>
        <input type='hidden' name='idj' value='$get_idj'>
        <div class='form-group'>
            <label>Nilai</label>
            <input type='number' class='form-control' name='nilai' value='$j[nilai]' required>
        </div>
        <button type='submit' name='updatenilai' class='btn btn-primary'>Simpan Nilai</button>
        <a href='index.php?view=bahantugas&act=jawaban&id=".$get_id."&kd=".$get_kd."&jdwl=".$get_jdwl."&ide=".$get_ide."' class='btn btn-default'>Batal</a>
    </form>
    </div></div></div>";
} elseif ($act=='hapusjawaban'){
    mysql_query("DELETE FROM rb_elearning_jawab WHERE id_elearning_jawab='$get_idj'");
    echo "<script>document.location='index.php?view=bahantugas&act=jawaban&id=".$get_id."&kd=".$get_kd."&jdwl=".$get_jdwl."&ide=".$get_ide."';</script>";
} elseif ($act=='detail'){
    cek_session_siswa();
    $detail = mysql_fetch_array(mysql_query("SELECT a.*, b.nama_kategori_elearning, c.kode_kelas, c.kode_pelajaran, d.namamatapelajaran, e.nama_kelas
                                             FROM rb_elearning a
                                             JOIN rb_kategori_elearning b ON a.id_kategori_elearning=b.id_kategori_elearning
                                             JOIN rb_jadwal_pelajaran c ON a.kodejdwl=c.kodejdwl
                                             JOIN rb_mata_pelajaran d ON c.kode_pelajaran=d.kode_pelajaran
                                             JOIN rb_kelas e ON c.kode_kelas=e.kode_kelas
                                             WHERE a.id_elearning='$get_ide'"));
    echo "<div class='col-md-12'>
            <div class='box box-info'>
                <div class='box-header with-border'>
                    <h3 class='box-title'>Detail Materi / Tugas</h3>
                </div>
                <div class='box-body'>
                    <table class='table table-condensed table-bordered'>
                        <tbody>
                            <tr><th width='180px'>Judul</th><td>$detail[nama_file]</td></tr>
                            <tr><th>Tipe</th><td>$detail[nama_kategori_elearning]</td></tr>
                            <tr><th>Mata Pelajaran</th><td>$detail[namamatapelajaran]</td></tr>
                            <tr><th>Kelas</th><td>$detail[nama_kelas]</td></tr>
                            <tr><th>Tanggal Upload</th><td>$detail[tanggal_tugas] WIB</td></tr>
                            <tr><th>Batas Waktu</th><td>$detail[tanggal_selesai] WIB</td></tr>
                            <tr><th>Keterangan</th><td>$detail[keterangan]</td></tr>
                            <tr><th>File</th><td>";
                            if ($detail['file_upload'] != ''){
                                echo "<a href='download.php?file=$detail[file_upload]' class='btn btn-success btn-sm'><i class='fa fa-download'></i> Download File</a>";
                            } else {
                                echo "<span class='text-muted'>Tidak ada file</span>";
                            }
                    echo "</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class='box-footer'>
                    <a href='index.php?view=bahantugas&act=listbahantugassiswa'><button class='btn btn-default'>Kembali</button></a>
                </div>
            </div>
          </div>";
}
?>