<?php 
if (empty($_GET['act'])){ 
  cek_session_admin();
    if (isset($_POST['pindahkelas'])){
      $angkatan_filter = isset($_POST['angkatan']) ? $_POST['angkatan'] : '';
      $kelas_filter = isset($_POST['kelas']) ? $_POST['kelas'] : '';

      if ($angkatan_filter!='' AND $kelas_filter != ''){
        $jml = mysql_fetch_array(mysql_query("SELECT count(*) as jmlp FROM rb_siswa where kode_kelas='$kelas_filter' AND angkatan='$angkatan_filter'"));
      }elseif ($angkatan_filter=='' AND $kelas_filter != ''){
        $jml = mysql_fetch_array(mysql_query("SELECT count(*) as jmlp FROM rb_siswa where kode_kelas='$kelas_filter'"));
      }elseif ($angkatan_filter!='' AND $kelas_filter == ''){
        $jml = mysql_fetch_array(mysql_query("SELECT count(*) as jmlp FROM rb_siswa where angkatan='$angkatan_filter'"));
      }

       $n = $jml['jmlp'];
       $kelas_pindah = $_POST['kelaspindah'];
       $angkatan_pindah = $_POST['angkatanpindah'];
       for ($i=0; $i<=$n; $i++){
         if (isset($_POST['pilih'.$i])){
           $nisn = $_POST['pilih'.$i];
           if ($angkatan_pindah != '' AND $kelas_pindah != ''){
              mysql_query("UPDATE rb_siswa SET angkatan='$angkatan_pindah', kode_kelas='$kelas_pindah' where nisn='$nisn'");
           }elseif ($angkatan_pindah == '' AND $kelas_pindah != ''){
              mysql_query("UPDATE rb_siswa SET kode_kelas='$kelas_pindah' where nisn='$nisn'");
           }elseif ($angkatan_pindah != '' AND $kelas_pindah == ''){
              mysql_query("UPDATE rb_siswa SET angkatan='$angkatan_pindah' where nisn='$nisn'");
           }
         }
       }
       echo "<script>
              setTimeout(function() {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: 'Data Siswa Berhasil Dipindahkan',
                  showConfirmButton: false,
                  timer: 1500
                }).then(function() {
                  window.location = 'index.php?view=siswa&angkatan=" . $angkatan_filter . "&kelas=" . $kelas_filter . "';
                });
              }, 100);
            </script>";
    }
?> 
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Semua Data Siswa </h3>
                   <?php if($_SESSION['level']!='kepala'){ ?>
                  <a class='pull-right btn btn-success btn-sm' target='_BLANK' href='print-siswa.php?kelas=<?php echo (isset($_GET['kelas']) ? $_GET['kelas'] : ''); ?>&angkatan=<?php echo (isset($_GET['angkatan']) ? $_GET['angkatan'] : ''); ?>'>Print Siswa</a>
                  <a style='margin-right:5px' class='pull-right btn btn-primary btn-sm' href='index.php?view=siswa&act=tambahsiswa'>Tambahkan Data Siswa</a>
                  <?php } ?>

                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='index.php' method='GET'>
                    <input type="hidden" name='view' value='siswa'>
                    <input type="number" name='angkatan' style='padding:3px' placeholder='Angkatan' value='<?php echo (isset($_GET['angkatan']) ? $_GET['angkatan'] : ''); ?>'>
                    <select name='kelas' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Filter Kelas -</option>";
                            $kelas = mysql_query("SELECT * FROM rb_kelas");
                            while ($k = mysql_fetch_array($kelas)){
                              $selected = (isset($_GET['kelas']) AND $_GET['kelas']==$k['kode_kelas']) ? 'selected' : '';
                              echo "<option value='$k[kode_kelas]' $selected>$k[kode_kelas] - $k[nama_kelas]</option>";
                            }
                        ?>
                    </select>
                    <input type="submit" style='margin-top:-4px' class='btn btn-info btn-sm' value='Lihat'>
                  </form>
                </div><!-- /.box-header -->
                <div class="box-body">
                <form action='' method='POST'>
                <input type="hidden" name='angkatan' value='<?php echo (isset($_GET['angkatan']) ? $_GET['angkatan'] : ''); ?>'>
                <input type="hidden" name='kelas' value='<?php echo (isset($_GET['kelas']) ? $_GET['kelas'] : ''); ?>'>
                <?php 
                  if (isset($_GET['kelas'])){
                    echo "<table id='myTable' class='table table-bordered table-striped'>
                            <thead>
                            <tr><th>Pilih</th>";
                  }else{
                    echo "<table id='example1' class='table table-bordered table-striped'>
                            <thead>
                              <tr>";
                  }
                  echo "<th>No</th>
                        <th>NIPD</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Angkatan</th>
                        <th>Jurusan</th>
                        <th>Kelas</th>
                        <th>Jenis Kelamin</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";

                  if (isset($_GET['kelas']) AND $_GET['kelas'] != '' AND isset($_GET['angkatan']) AND $_GET['angkatan'] != ''){
                    $tampil = mysql_query("SELECT * FROM rb_siswa a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                              LEFT JOIN rb_jenis_kelamin c ON a.id_jenis_kelamin=c.id_jenis_kelamin 
                                                LEFT JOIN rb_jurusan d ON b.kode_jurusan=d.kode_jurusan 
                                                  where a.kode_kelas='$_GET[kelas]' AND a.angkatan='$_GET[angkatan]' ORDER BY a.id_siswa");
                  }elseif (isset($_GET['kelas']) AND $_GET['kelas'] != '' AND (!isset($_GET['angkatan']) OR $_GET['angkatan'] == '')){
                    $tampil = mysql_query("SELECT * FROM rb_siswa a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                              LEFT JOIN rb_jenis_kelamin c ON a.id_jenis_kelamin=c.id_jenis_kelamin 
                                                LEFT JOIN rb_jurusan d ON b.kode_jurusan=d.kode_jurusan 
                                                  where a.kode_kelas='$_GET[kelas]' ORDER BY a.id_siswa");
                  }elseif ((!isset($_GET['kelas']) OR $_GET['kelas'] == '') AND isset($_GET['angkatan']) AND $_GET['angkatan'] != ''){
                    $tampil = mysql_query("SELECT * FROM rb_siswa a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                              LEFT JOIN rb_jenis_kelamin c ON a.id_jenis_kelamin=c.id_jenis_kelamin 
                                                LEFT JOIN rb_jurusan d ON b.kode_jurusan=d.kode_jurusan 
                                                  where a.angkatan='$_GET[angkatan]' ORDER BY a.id_siswa");
                  }else{
                    // Show all students if no filter
                    $tampil = mysql_query("SELECT * FROM rb_siswa a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                              LEFT JOIN rb_jenis_kelamin c ON a.id_jenis_kelamin=c.id_jenis_kelamin 
                                                LEFT JOIN rb_jurusan d ON b.kode_jurusan=d.kode_jurusan 
                                                  ORDER BY a.id_siswa");
                  }
                  
                  // Logic to display empty table if no filter provided? 
                  // Original: if class='' and angkatan='', no query logic was shown in LOOP, but later displayed "Silahkan Input...".
                  // However, let's keep it safe.
                  if (isset($tampil) && $tampil) {
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr>";
                            if (isset($_GET['kelas'])){
                                echo "<td><input type='checkbox' name='pilih".$no."' value='$r[nisn]'/></td>";
                            }
                              echo "<td>$no</td>
                              <td>$r[nipd]</td>
                              <td>$r[nisn]</td>
                              <td>$r[nama]</td>
                              <td>$r[angkatan]</td>
                              <td>$r[nama_jurusan]</td>
                              <td>$r[nama_kelas]</td>
                              <td>$r[jenis_kelamin]</td>";
                              if($_SESSION['level']!='kepala'){
                                echo "<td><center>
                                  <a class='btn btn-default btn-xs' title='Lihat Detail' href='?view=siswa&act=detailsiswa&id=$r[nisn]'><span class='glyphicon glyphicon-search'></span></a>
                                  <a class='btn btn-info btn-xs' title='Edit Siswa' href='?view=siswa&act=editsiswa&id=$r[nisn]'><span class='glyphicon glyphicon-edit'></span></a>
                                  <a class='btn btn-danger btn-xs' title='Delete Siswa' href='javascript:void(0)' onclick=\"konfirmasiHapus('index.php?view=siswa&hapus=$r[nisn]')\"><span class='glyphicon glyphicon-remove'></span></a>
                                </center></td>";
                              }else{
                                  echo "<td><center>
                                  <a class='btn btn-default btn-xs' title='Lihat Detail' href='?view=siswa&act=detailsiswa&id=$r[nisn]'><span class='glyphicon glyphicon-search'></span></a>
                                </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }
                  }
                      if (isset($_GET['hapus'])){
                          mysql_query("DELETE FROM rb_siswa where nisn='$_GET[hapus]'");
                          echo "<script>
              setTimeout(function() {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: 'Data berhasil dihapus',
                  showConfirmButton: false,
                  timer: 1500
                }).then(function() {
                  window.location = 'index.php?view=siswa';
                });
              }, 100);
            </script>";
                      }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->

              </div><!-- /.box -->
              <?php if($_SESSION['level']!='kepala'){
                    if (isset($_GET['kelas'])){ ?>
              <div class='box-footer'>
                  Pindah Ke : 
                  <input type="number" name='angkatanpindah' style='padding:3px' placeholder='Angkatan' value='<?php echo (isset($_GET['angkatan']) ? $_GET['angkatan'] : ''); ?>'>
                  <select name='kelaspindah' style='padding:4px' required>
                        <?php 
                            echo "<option value=''>- Pilih Kelas -</option>";
                            $kelas = mysql_query("SELECT * FROM rb_kelas");
                            while ($k = mysql_fetch_array($kelas)){
                                echo "<option value='$k[kode_kelas]'>$k[kode_kelas] - $k[nama_kelas]</option>";
                            }
                        ?>
                    </select>
                  <button style='margin-top:-5px' type='submit' name='pindahkelas' class='btn btn-sm btn-info'>Proses</button>
                  <a href='index.php?view=siswa'><button type='button' class='btn btn-sm  btn-default pull-right'>Cancel</button></a>
              </div>
              <?php }} ?>

              </form>
            </div>
<?php 
}elseif($_GET['act']=='tambahsiswa'){
  cek_session_admin();
  if (isset($_POST['tambah'])){
      // Insert data siswa - tempat_lahir adalah field required (NOT NULL)
      mysql_query("INSERT INTO rb_siswa (nipd, password, nama, id_jenis_kelamin, nisn, tempat_lahir, angkatan, kode_kelas, kode_jurusan) 
                   VALUES('$_POST[aa]', '$_POST[ac]', '$_POST[ad]', '$_POST[bd]', '$_POST[ab]', '', '$_POST[af]', '$_POST[ae]', '$_POST[ag]')") or die(mysql_error());
      
      echo "<script>
              setTimeout(function() {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: 'Data berhasil ditambahkan',
                  showConfirmButton: false,
                  timer: 1500
                }).then(function() {
                  window.location = 'index.php?view=siswa';
                });
              }, 100);
            </script>";
  }

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Siswa</h3>
                </div>
                <div class='box-body'>
                  <form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
                  <div class='col-md-6'>
                    <table class='table table-condensed table-bordered'>
                    <tbody>
                      <tr><th width='130px' scope='row'>NIPD</th> <td><input type='text' class='form-control' name='aa' required></td></tr>
                      <tr><th scope='row'>NISN</th> <td><input type='text' class='form-control' name='ab' required></td></tr>
                      <tr><th scope='row'>Password</th> <td><input type='text' class='form-control' name='ac' required></td></tr>
                      <tr><th scope='row'>Nama Siswa</th> <td><input type='text' class='form-control' name='ad' required></td></tr>
                      <tr><th scope='row'>Kelas</th> <td><select class='form-control' name='ae' required> 
                                                                    <option value='0' selected>- Pilih Kelas -</option>"; 
                                                                      $kelas = mysql_query("SELECT * FROM rb_kelas");
                                                                      while($a = mysql_fetch_array($kelas)){
                                                                          echo "<option value='$a[kode_kelas]'>$a[nama_kelas]</option>";
                                                                      }
                                                            echo "</select></td></tr>
                      <tr><th scope='row'>Angkatan</th> <td><input type='text' class='form-control' name='af' required></td></tr>
                      <tr><th scope='row'>Jurusan</th> <td><select class='form-control' name='ag' required> 
                                                                    <option value='0' selected>- Pilih Jurusan -</option>"; 
                                                                      $jurusan = mysql_query("SELECT * FROM rb_jurusan");
                                                                      while($a = mysql_fetch_array($jurusan)){
                                                                          echo "<option value='$a[kode_jurusan]'>$a[nama_jurusan]</option>";
                                                                      }
                                                            echo "</select></td></tr>
                       <tr><th scope='row'>Jenis Kelamin</th> <td><select class='form-control' name='bd' required> 
                                                                    <option value='0' selected>- Pilih Jenis Kelamin -</option>"; 
                                                                      $jk = mysql_query("SELECT * FROM rb_jenis_kelamin");
                                                                      while($a = mysql_fetch_array($jk)){
                                                                          echo "<option value='$a[id_jenis_kelamin]'>$a[jenis_kelamin]</option>";
                                                                      }
                                                            echo "</select></td></tr>
                    </tbody>
                    </table>
                  </div>
                  
                  <div style='clear:both'></div>
                  <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=siswa'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                  </div> 
                  </form>
                </div>
            </div>
        </div>";
}elseif($_GET['act']=='editsiswa'){
  cek_session_siswa();
  if (isset($_POST['update1'])){
      $rtrw = explode('/',$_POST['ai']);
      $rt = $rtrw[0];
      $rw = $rtrw[1];
      $dir_gambar = 'foto_siswa/';
      $filename = basename($_FILES['ax']['name']);
      $filenamee = date("YmdHis").'-'.basename($_FILES['ax']['name']);
      $uploadfile = $dir_gambar . $filenamee;
      if ($filename != ''){      
        if (move_uploaded_file($_FILES['ax']['tmp_name'], $uploadfile)){
           mysql_query("UPDATE rb_siswa SET 
                                nipd        = '$_POST[aa]',
                                nisn   = '$_POST[ab]',
                                password         = '$_POST[ac]',
                                nama       = '$_POST[ad]',
                                kode_kelas    = '$_POST[ae]',
                                angkatan   = '$_POST[af]',
                                kode_jurusan   = '$_POST[ag]',
                                id_jenis_kelamin = '$_POST[bd]',
                                foto = '$filenamee' where nipd='$_POST[id]'") or die(mysql_error());
        }
      }else{
            mysql_query("UPDATE rb_siswa SET 
                                nipd        = '$_POST[aa]',
                                nisn   = '$_POST[ab]',
                                password         = '$_POST[ac]',
                                nama       = '$_POST[ad]',
                                kode_kelas    = '$_POST[ae]',
                                angkatan   = '$_POST[af]',
                                kode_jurusan   = '$_POST[ag]',
                                id_jenis_kelamin = '$_POST[bd]' where nipd='$_POST[id]'") or die(mysql_error());
      }
          echo "<script>
            setTimeout(function() {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil diupdate',
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                window.location = 'index.php?view=siswa';
              });
            }, 100);
          </script>";
  }

    if ($_SESSION['level'] == 'siswa'){
        $nisn = $_SESSION['id'];
        $close = 'readonly=on';
    }else{
        $nisn = $_GET['id'];
        $close = '';
    }
    $edit = mysql_query("SELECT * FROM rb_siswa a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                              LEFT JOIN rb_jenis_kelamin c ON a.id_jenis_kelamin=c.id_jenis_kelamin 
                                  LEFT JOIN rb_jurusan d ON b.kode_jurusan=d.kode_jurusan
                                    LEFT JOIN rb_agama e ON a.id_agama=e.id_agama 
                                      where a.nisn='$nisn'");
    $s = mysql_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Siswa</h3>
                </div>
                <div class='box-body'>";
                
                  if ($_SESSION['level'] == 'siswa'){
                    echo "<div class='alert alert-warning alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Perhatian!</strong> - Semua Data-data yang ada dibawah ini akan digunakan untuk keperluan pihak sekolah, jadi tolong di isi dengan data sebenarnya dan jika kedapatan data yang diisikan tidak seuai dengan yang sebenarnya, maka pihak sekolah akan memberikan sanksi tegas !!!
                    </div>";
                  }

                  echo "<form action='' method='POST' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='col-md-7'>
                          <table class='table table-condensed table-bordered'>
                          <tbody>
                            <tr><th style='background-color:#E7EAEC' width='160px' rowspan='17'>";
                                if (trim($s['foto'])==''){
                                  echo "<img class='img-thumbnail' style='width:155px' src='foto_siswa/no-image.jpg'>";
                                }else{
                                  echo "<img class='img-thumbnail' style='width:155px' src='foto_siswa/$s[foto]'>";
                                }
                            echo "</th></tr>
                            <input type='hidden' value='$s[nipd]' name='id'>
                            <tr><th width='120px' scope='row'>NIPD</th> <td><input type='text' class='form-control' value='$s[nipd]' name='aa' required $close></td></tr>
                            <tr><th scope='row'>NISN</th> <td><input type='text' class='form-control' value='$s[nisn]' name='ab' required $close></td></tr>
                            <tr><th scope='row'>Password</th> <td><input type='text' class='form-control' value='$s[password]' name='ac' required></td></tr>
                            <tr><th scope='row'>Nama Siswa</th> <td><input type='text' class='form-control' value='$s[nama]' name='ad' required></td></tr>
                            <tr><th scope='row'>Kelas</th> <td><select class='form-control' name='ae' required $close> 
                                                                          <option value='0' selected>- Pilih Kelas -</option>"; 
                                                                            $kelas = mysql_query("SELECT * FROM rb_kelas");
                                                                            while($a = mysql_fetch_array($kelas)){
                                                                              if ($_SESSION['level'] == 'siswa'){
                                                                                if ($a['kode_kelas'] == $s['kode_kelas']){
                                                                                  echo "<option value='$a[kode_kelas]' selected>$a[nama_kelas]</option>";
                                                                                }
                                                                              }else{
                                                                                if ($a['kode_kelas'] == $s['kode_kelas']){
                                                                                  echo "<option value='$a[kode_kelas]' selected>$a[nama_kelas]</option>";
                                                                                }else{
                                                                                  echo "<option value='$a[kode_kelas]'>$a[nama_kelas]</option>";
                                                                                }
                                                                              }
                                                                            }
                                                                  echo "</select></td></tr>
                            <tr><th scope='row'>Angkatan</th> <td><input type='text' class='form-control' value='$s[angkatan]' name='af' required $close></td></tr>
                            <tr><th scope='row'>Jurusan</th> <td><select class='form-control' name='ag' required $close> 
                                                                          <option value='0' selected>- Pilih Jurusan -</option>"; 
                                                                            $jurusan = mysql_query("SELECT * FROM rb_jurusan");
                                                                            while($a = mysql_fetch_array($jurusan)){
                                                                              if ($_SESSION['level'] == 'siswa'){
                                                                                if ($a['kode_jurusan'] == $s['kode_jurusan']){
                                                                                  echo "<option value='$a[kode_jurusan]' selected>$a[nama_jurusan]</option>";
                                                                                }
                                                                              }else{
                                                                                if ($a['kode_jurusan'] == $s['kode_jurusan']){
                                                                                  echo "<option value='$a[kode_jurusan]' selected>$a[nama_jurusan]</option>";
                                                                                }else{
                                                                                  echo "<option value='$a[kode_jurusan]'>$a[nama_jurusan]</option>";
                                                                                }
                                                                              }
                                                                            }
                                                                  echo "</select></td></tr>
                             <tr><th scope='row'>Jenis Kelamin</th> <td><select class='form-control' name='bd' required> 
                                                                          <option value='0' selected>- Pilih Jenis Kelamin -</option>"; 
                                                                            $jk = mysql_query("SELECT * FROM rb_jenis_kelamin");
                                                                            while($a = mysql_fetch_array($jk)){
                                                                              if ($a['id_jenis_kelamin'] == $s['id_jenis_kelamin']){
                                                                                echo "<option value='$a[id_jenis_kelamin]' selected>$a[jenis_kelamin]</option>";
                                                                              }else{
                                                                                echo "<option value='$a[id_jenis_kelamin]'>$a[jenis_kelamin]</option>";
                                                                              }
                                                                            }
                                                                  echo "</select></td></tr>
                            <tr><th scope='row'>Ganti Foto</th>             <td><div style='position:relative;''>
                                                                          <a class='btn btn-primary' href='javascript:;'>
                                                                            <span class='glyphicon glyphicon-search'></span> Browse..."; ?>
                                                                            <input type='file' class='files' name='ax' onchange='$("#upload-file-info").html($(this).val());'>
                                                                          <?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
                                                                        </div>
                            </td></tr>
                          </tbody>
                          </table>
                        </div>
                        
                        <div style='clear:both'></div>
                        <div class='box-footer'>
                          <button type='submit' name='update1' class='btn btn-info'>Update</button>
                          <a href='index.php?view=siswa'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                        </div> 

                        </form>
                </div>
            </div>";

}elseif($_GET['act']=='detailsiswa'){
  cek_session_siswa();
    if ($_SESSION['level'] == 'siswa'){
        $nisn = $_SESSION['id'];
    }else{
        $nisn = $_GET['id'];
    }
    $detail = mysql_query("SELECT * FROM rb_siswa a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                              LEFT JOIN rb_jenis_kelamin c ON a.id_jenis_kelamin=c.id_jenis_kelamin 
                                  LEFT JOIN rb_jurusan d ON b.kode_jurusan=d.kode_jurusan
                                      where a.nisn='$nisn'");
    $s = mysql_fetch_array($detail);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Detail Data Siswa</h3>
                </div>
                <div class='box-body'>
                        <form class='form-horizontal'>
                        <div class='col-md-7'>
                          <table class='table table-condensed table-bordered'>
                          <tbody>
                            <tr><th style='background-color:#E7EAEC' width='160px' rowspan='10'>";
                                if (trim($s['foto'])==''){
                                  echo "<img class='img-thumbnail' style='width:155px' src='foto_siswa/no-image.jpg'>";
                                }else{
                                  echo "<img class='img-thumbnail' style='width:155px' src='foto_siswa/$s[foto]'>";
                                }
                              if($_SESSION['level']!='kepala'){
                                echo "<a href='index.php?view=siswa&act=editsiswa&id=$_GET[id]' class='btn btn-success btn-block'>Edit Profile</a>";
                              }
                                echo "</th>
                            </tr>
                            <tr><th width='120px' scope='row'>NIPD</th> <td>$s[nipd]</td></tr>
                            <tr><th scope='row'>NISN</th> <td>$s[nisn]</td></tr>
                            <tr><th scope='row'>Password</th> <td>$s[password]</td></tr>
                            <tr><th scope='row'>Nama Siswa</th> <td>$s[nama]</td></tr>
                            <tr><th scope='row'>Kelas</th> <td>$s[nama_kelas]</td></tr>
                            <tr><th scope='row'>Angkatan</th> <td>$s[angkatan]</td></tr>
                            <tr><th scope='row'>Jurusan</th> <td>$s[nama_jurusan]</td></tr>
                            <tr><th scope='row'>Jenis Kelamin</th> <td>$s[jenis_kelamin]</td></tr>
                          </tbody>
                          </table>
                        </div>
                        </form>
                </div>
            </div>";
}elseif($_GET['act']=='penilaiandiri'){
            $t = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa a JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas where a.nisn='$_GET[id]'"));
            echo "<div class='col-xs-12'>  
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>Data Pertanyaan dan Jawaban Penilaian Diri </h3>
                </div>
                <div class='box-body'>

                        <div class='col-md-12'>
                            <table class='table table-condensed table-hover'>
                                <tbody>
                                  <tr><th width='120px' scope='row'>NISN</th> <td>$t[nisn]</td></tr>
                                  <tr><th scope='row'>Nama Siswa</th>           <td>$t[nama]</td></tr>
                                  <tr><th scope='row'>Kelas</th>           <td>$t[nama_kelas]</td></tr>
                                </tbody>
                            </table>
                        </div>

                  <table id='example2' class='table table-bordered table-striped'>
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Pertanyaan</th>
                      </tr>
                    </thead>
                    <tbody>";

                    $tampil = mysql_query("SELECT * FROM rb_pertanyaan_penilaian where status='diri' ORDER BY id_pertanyaan_penilaian DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    $jwb = mysql_fetch_array(mysql_query("SELECT * FROM rb_pertanyaan_penilaian_jawab where nisn='$_GET[id]' AND id_pertanyaan_penilaian='$r[id_pertanyaan_penilaian]' AND status='diri' AND kode_kelas='$t[kode_kelas]'"));
                    if (trim($jwb['jawaban'])==''){
                      $jawab = "<i style='color:red'>Belum Ada Jawaban...</i>";
                    }else{
                      $jawab = "<i>$jwb[jawaban]</i>";
                    }
                    echo "<tr><td>$no</td>
                              <td>$r[pertanyaan] <br> <strong>Jawaban :</strong> <br>$jawab</td>
                          </tr>";
                      $no++;
                      }
                    echo "</tbody>
                  </table>
                </div>
              </div>
            </div>";
}elseif($_GET['act']=='penilaianteman'){
          echo "<div class='col-xs-12'>  
              <div class='box'>
              <form action='' method='POST'>
                <div class='box-header'>
                  <h3 class='box-title'>Semua Data Teman Kelas anda </h3>
                </div>
                <div class='box-body'>
                  <table class='table table-bordered table-striped'>
                    <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>NIPD</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Angkatan</th>
                        <th>Jurusan</th>
                        <th>Kelas</th>
                        <th width='135px'></th>
                      </tr>
                    </thead>
                    <tbody>";

                    $cs = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa where nisn='$_GET[id]'"));
                    $tampil = mysql_query("SELECT * FROM rb_siswa a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                              LEFT JOIN rb_jenis_kelamin c ON a.id_jenis_kelamin=c.id_jenis_kelamin 
                                                LEFT JOIN rb_jurusan d ON b.kode_jurusan=d.kode_jurusan 
                                                  where a.kode_kelas='$cs[kode_kelas]' AND a.angkatan='$cs[angkatan]' AND nisn!='$_GET[id]' ORDER BY a.id_siswa");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[nipd]</td>
                              <td>$r[nisn]</td>
                              <td>$r[nama]</td>
                              <td>$r[angkatan]</td>
                              <td>$r[nama_jurusan]</td>
                              <td>$r[nama_kelas]</td>
                              <td align=center><a class='btn btn-success btn-xs' title='Lihat Penilaian' href='index.php?view=siswa&act=pertanyaan&nisn=$r[nisn]&id=$_GET[id]'><span class='glyphicon glyphicon-search'></span> Lihat Penilaian</a></td>
                          </tr>";
                      $no++;
                      }
                    echo "</tbody>
                  </table>
                </div>
              </form>
              </div>
            </div>";
}elseif($_GET['act']=='pertanyaan'){ ?>
            <div class="col-xs-12">  
              <div class="box">
              <form action='' method='POST'>
                <div class="box-header">
                  <h3 class="box-title">Data Pertanyan dan Jawaban Penilaian Teman </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <?php
                      echo "<input type='hidden' value='$_GET[nisn]' name='nisnteman'>";
                      $t = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa where nisn='$_GET[nisn]'"));
                      $tt = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa where nisn='$_GET[id]'"));
                      echo "<div class='col-md-12'>
                            <table class='table table-condensed table-hover'>
                                <tbody>
                                  <tr><th scope='row'>NISN Penilai</th>           <td>$tt[nisn]</td></tr>
                                  <tr><th scope='row'>Nama Penilai</th>           <td>$tt[nama]</td></tr>

                                  <tr bgcolor=#f4f4f4><th width='120px' scope='row'>NISN Teman</th> <td style='color:blue'>$t[nisn]</td></tr>
                                  <tr bgcolor=#f4f4f4><th scope='row'>Nama Teman</th>           <td style='color:blue'>$t[nama]</td></tr>
                                </tbody>
                            </table>
                            </div>";
                  ?>
                  <table id="example3" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Pertanyaan</th>
                      </tr>
                    </thead>
                    <tbody>

                  <?php 
                    $tampil = mysql_query("SELECT * FROM rb_pertanyaan_penilaian where status='teman' ORDER BY id_pertanyaan_penilaian DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    $jwb = mysql_fetch_array(mysql_query("SELECT * FROM rb_pertanyaan_penilaian_jawab where nisn='$_GET[id]' AND nisn_teman='$_GET[nisn]' AND id_pertanyaan_penilaian='$r[id_pertanyaan_penilaian]' AND status='teman' AND kode_kelas='$tt[kode_kelas]'"));
                    if (trim($jwb['jawaban'])==''){
                      $jawab = "<i style='color:red'>Belum Ada Jawaban...</i>";
                    }else{
                      $jawab = "<i>$jwb[jawaban]</i>";
                    }
                    echo "<tr><td>$no</td>
                              <td>$r[pertanyaan] <br> <strong>Jawaban :</strong> <br>$jawab</td>
                          </tr>";
                      $no++;
                      }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
<?php
}
?>