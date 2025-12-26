<?php 
if (empty($_GET['act'])){ 
?> 
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Semua Data Guru </h3>
                  <?php if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){ ?>
                  <a class='pull-right btn btn-primary btn-sm' href='index.php?view=guru&act=tambahguru'>Tambahkan Data Guru</a>
                  <?php } ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>No Telpon</th>
                        <th>Status Pegawai</th>
                        <th>Jenis PTK</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $tampil = mysql_query("SELECT * FROM rb_guru a 
                                          LEFT JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin 
                                            LEFT JOIN rb_status_kepegawaian c ON a.id_status_kepegawaian=c.id_status_kepegawaian 
                                              LEFT JOIN rb_jenis_ptk d ON a.id_jenis_ptk=d.id_jenis_ptk
                                              ORDER BY a.nip DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    $tanggal = tgl_indo($r['tgl_posting']);
                    echo "<tr><td>$no</td>
                              <td>".(isset($r['nip']) ? $r['nip'] : '')."</td>
                              <td>".(isset($r['nama_guru']) ? $r['nama_guru'] : '')."</td>
                              <td>".(isset($r['jenis_kelamin']) ? $r['jenis_kelamin'] : '')."</td>
                              <td>".(isset($r['hp']) ? $r['hp'] : '')."</td>
                              <td>".(isset($r['status_kepegawaian']) ? $r['status_kepegawaian'] : '')."</td>
                              <td>".(isset($r['jenis_ptk']) ? $r['jenis_ptk'] : '')."</td>";
                              if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){
                        echo "<td><center>
                                <a class='btn btn-info btn-xs' title='Lihat Detail' href='?view=guru&act=detailguru&id=".(isset($r['nip']) ? $r['nip'] : '')."'><span class='glyphicon glyphicon-search'></span></a>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?view=guru&act=editguru&id=".(isset($r['nip']) ? $r['nip'] : '')."'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='javascript:void(0)' onclick=\"konfirmasiHapus('index.php?view=guru&hapus=".(isset($r['nip']) ? $r['nip'] : '')."')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
                              }else{
                                echo "<td><center>
                                <a class='btn btn-info btn-xs' title='Lihat Detail' href='?view=guru&act=detailguru&id=".(isset($r['nip']) ? $r['nip'] : '')."'><span class='glyphicon glyphicon-search'></span></a>
                              </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }
                      if (isset($_GET['hapus'])){
                          mysql_query("DELETE FROM rb_guru where nip='$_GET[hapus]'");
                          echo "<script>
              setTimeout(function() {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: 'Data berhasil dihapus',
                  showConfirmButton: false,
                  timer: 1500
                }).then(function() {
                  window.location = 'index.php?view=guru';
                });
              }, 100);
            </script>";
                      }

                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
<?php 
}elseif(isset($_GET['act']) && $_GET['act']=='tambahguru'){
  if (isset($_POST['tambah'])){
      $rtrw = explode('/', isset($_POST['al']) ? $_POST['al'] : '');
      $rt = isset($rtrw[0]) ? $rtrw[0] : '';
      $rw = isset($rtrw[1]) ? $rtrw[1] : '';
      $dir_gambar = 'foto_pegawai/';
      $filename = basename($_FILES['ax']['name']);
      $filenamee = date("YmdHis").'-'.basename($_FILES['ax']['name']);
      $uploadfile = $dir_gambar . $filenamee;
      if ($filename != ''){      
        if (move_uploaded_file($_FILES['ax']['tmp_name'], $uploadfile)) {
          mysql_query("INSERT INTO rb_guru VALUES('$_POST[aa]','$_POST[ab]','$_POST[ac]','$_POST[af]','',
                           '','','','','$_POST[au]','$_POST[as]','', 
                           '','','','','','','','',
                           '','$_POST[ah]','$_POST[aj]','','','','', 
                           '','','','','','','',
                           '','','','','','','',
                           '','','','','','','$filenamee')") or die(mysql_error());
        }
      }else{
          mysql_query("INSERT INTO rb_guru VALUES('$_POST[aa]','$_POST[ab]','$_POST[ac]','$_POST[af]','',
                           '','','','','$_POST[au]','$_POST[as]','', 
                           '','','','','','','','',
                           '','$_POST[ah]','$_POST[aj]','','','','', 
                           '','','','','','','',
                           '','','','','','','',
                           '','','','','','','')") or die(mysql_error());
      }
      echo "<script>
            setTimeout(function() {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil ditambahkan',
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                window.location = 'index.php?view=guru&act=detailguru&id=" . $_POST['aa'] . "';
              });
            }, 100);
          </script>";
  }

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Guru</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-6'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value=''>
                    <tr><th width='120px' scope='row'>Nip</th>      <td><input type='text' class='form-control' name='aa'></td></tr>
                    <tr><th scope='row'>Password</th>               <td><input type='text' class='form-control' name='ab'></td></tr>
                    <tr><th scope='row'>Nama Lengkap</th>           <td><input type='text' class='form-control' name='ac'></td></tr>
                    <tr><th scope='row'>Jenis Kelamin</th>          <td><select class='form-control' name='af'> 
                                                                          <option value='0' selected>- Pilih Jenis Kelamin -</option>"; 
                                                                            $jk = mysql_query("SELECT * FROM rb_jenis_kelamin");
                                                                            while($a = mysql_fetch_array($jk)){
                                                                                echo "<option value='$a[id_jenis_kelamin]'>$a[jenis_kelamin]</option>";
                                                                            }
                                                                            echo "</select></td></tr>
                    <tr><th scope='row'>No Telpon</th>                  <td><input type='text' class='form-control' name='ah'></td></tr>
                    <tr><th scope='row'>Alamat Email</th>           <td><input type='text' class='form-control' name='aj'></td></tr>
                    <tr><th scope='row'>Jenis PTK</th>              <td><select class='form-control' name='as'> 
                                                                          <option value='0' selected>- Pilih Jenis PTK -</option>"; 
                                                                            $ptk = mysql_query("SELECT * FROM rb_jenis_ptk");
                                                                            while($a = mysql_fetch_array($ptk)){
                                                                                echo "<option value='$a[id_jenis_ptk]'>$a[jenis_ptk]</option>";
                                                                            }
                                                                  echo "</select></td></tr>
                    <tr><th scope='row'>Status Pegawai</th>         <td><select class='form-control' name='au'> 
                                                                          <option value='0' selected>- Pilih Status Kepegawaian -</option>"; 
                                                                            $status_kepegawaian = mysql_query("SELECT * FROM rb_status_kepegawaian");
                                                                            while($a = mysql_fetch_array($status_kepegawaian)){
                                                                                echo "<option value='$a[id_status_kepegawaian]'>$a[status_kepegawaian]</option>";
                                                                            }
                                                                  echo "</select></td></tr>
                    <tr><th scope='row'>Foto</th>             <td><div style='position:relative;''>
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
                          <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                          <a href='index.php?view=guru'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                        </div> 
              </div>
            </form>
            </div>";
}elseif(isset($_GET['act']) && $_GET['act']=='editguru'){
  if (isset($_POST['update1'])){
      $rtrw = explode('/', isset($_POST['al']) ? $_POST['al'] : '');
      $rt = isset($rtrw[0]) ? $rtrw[0] : '';
      $rw = isset($rtrw[1]) ? $rtrw[1] : '';
      $dir_gambar = 'foto_pegawai/';
      $filename = basename($_FILES['ax']['name']);
      $filenamee = date("YmdHis").'-'.basename($_FILES['ax']['name']);
      $uploadfile = $dir_gambar . $filenamee;
      if ($filename != ''){      
        if (move_uploaded_file($_FILES['ax']['tmp_name'], $uploadfile)) {
          mysql_query("UPDATE rb_guru SET 
                           nip          = '$_POST[aa]',
                           password     = '$_POST[ab]',
                           nama_guru         = '$_POST[ac]',
                           id_jenis_kelamin       = '$_POST[af]',
                           hp         = '$_POST[ah]',
                           email        = '$_POST[aj]',
                           id_jenis_ptk = '$_POST[as]',
                           id_status_kepegawaian = '$_POST[au]',
                           foto = '$filenamee' where nip='$_POST[id]'") or die(mysql_error());
        }
      }else{
          mysql_query("UPDATE rb_guru SET 
                           nip          = '$_POST[aa]',
                           password     = '$_POST[ab]',
                           nama_guru         = '$_POST[ac]',
                           id_jenis_kelamin       = '$_POST[af]',
                           hp         = '$_POST[ah]',
                           email        = '$_POST[aj]',
                           id_jenis_ptk = '$_POST[as]',
                           id_status_kepegawaian = '$_POST[au]' where nip='$_POST[id]'") or die(mysql_error());
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
                window.location = 'index.php?view=guru&act=detailguru&id=" . $_POST['id'] . "';
              });
            }, 100);
          </script>";
  }

    $detail = mysql_query("SELECT * FROM rb_guru where nip='$_GET[id]'");
    $s = mysql_fetch_array($detail);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Guru</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-7'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[nip]'>
                    <tr><th style='background-color:#E7EAEC' width='160px' rowspan='25'>";
                        if (trim($s['foto'])==''){
                          echo "<img class='img-thumbnail' style='width:155px' src='foto_siswa/no-image.jpg'>";
                        }else{
                          echo "<img class='img-thumbnail' style='width:155px' src='foto_pegawai/$s[foto]'>";
                        }
                        echo "</th>
                    </tr>
                    <input type='hidden' name='id' value='$s[nip]'>
                    <tr><th width='120px' scope='row'>Nip</th>      <td><input type='text' class='form-control' value='$s[nip]' name='aa'></td></tr>
                    <tr><th scope='row'>Password</th>               <td><input type='text' class='form-control' value='$s[password]' name='ab'></td></tr>
                    <tr><th scope='row'>Nama Lengkap</th>           <td><input type='text' class='form-control' value='$s[nama_guru]' name='ac'></td></tr>
                    <tr><th scope='row'>Jenis Kelamin</th>          <td><select class='form-control' name='af'> 
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
                    <tr><th scope='row'>No Telpon</th>                  <td><input type='text' class='form-control' value='$s[hp]' name='ah'></td></tr>
                    <tr><th scope='row'>Alamat Email</th>           <td><input type='text' class='form-control' value='$s[email]' name='aj'></td></tr>
                    <tr><th scope='row'>Jenis PTK</th>              <td><select class='form-control' name='as'> 
                                                                          <option value='0' selected>- Pilih Jenis PTK -</option>"; 
                                                                            $ptk = mysql_query("SELECT * FROM rb_jenis_ptk");
                                                                            while($a = mysql_fetch_array($ptk)){
                                                                              if ($a['id_jenis_ptk'] == $s['id_jenis_ptk']){
                                                                                echo "<option value='$a[id_jenis_ptk]' selected>$a[jenis_ptk]</option>";
                                                                              }else{
                                                                                echo "<option value='$a[id_jenis_ptk]'>$a[jenis_ptk]</option>";
                                                                              }
                                                                            }
                                                                  echo "</select></td></tr>
                    <tr><th scope='row'>Status Pegawai</th>         <td><select class='form-control' name='au'> 
                                                                          <option value='0' selected>- Pilih Status Kepegawaian -</option>"; 
                                                                            $status_kepegawaian = mysql_query("SELECT * FROM rb_status_kepegawaian");
                                                                            while($a = mysql_fetch_array($status_kepegawaian)){
                                                                              if ($a['id_status_kepegawaian'] == $s['id_status_kepegawaian']){
                                                                                echo "<option value='$a[id_status_kepegawaian]' selected>$a[status_kepegawaian]</option>";
                                                                              }else{
                                                                                echo "<option value='$a[id_status_kepegawaian]'>$a[status_kepegawaian]</option>";
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
                          <a href='index.php?view=guru'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                        </div> 
              </div>
            </form>
            </div>";
}elseif(isset($_GET['act']) && $_GET['act']=='detailguru'){
    $detail = mysql_query("SELECT a.*, b.jenis_kelamin, c.status_kepegawaian, d.jenis_ptk, e.nama_agama, f.nama_status_keaktifan, g.nama_golongan, h.status_pernikahan 
                                FROM rb_guru a LEFT JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin 
                                  LEFT JOIN rb_status_kepegawaian c ON a.id_status_kepegawaian=c.id_status_kepegawaian 
                                    LEFT JOIN rb_jenis_ptk d ON a.id_jenis_ptk=d.id_jenis_ptk 
                                      LEFT JOIN rb_agama e ON a.id_agama=e.id_agama 
                                        LEFT JOIN rb_status_keaktifan f ON a.id_status_keaktifan=f.id_status_keaktifan 
                                          LEFT JOIN rb_golongan g ON a.id_golongan=g.id_golongan
                                            LEFT JOIN rb_status_pernikahan h ON a.id_status_pernikahan=h.id_status_pernikahan
                                              where a.nip='$_GET[id]'");
    $s = mysql_fetch_array($detail);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Detail Data Guru</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-7'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='".(isset($s['nip']) ? $s['nip'] : '')."'>
                    <tr><th style='background-color:#E7EAEC' width='160px' rowspan='25'>";
                        if (trim(isset($s['foto']) ? $s['foto'] : '')==''){
                          echo "<img class='img-thumbnail' style='width:155px' src='foto_siswa/no-image.jpg'>";
                        }else{
                          echo "<img class='img-thumbnail' style='width:155px' src='foto_pegawai/$s[foto]'>";
                        }
                      if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){
                        echo "<a href='index.php?view=guru&act=editguru&id=$_GET[id]' class='btn btn-success btn-block'>Edit Profile</a>";
                      }
                        echo "</th>
                    </tr>
                    <tr><th width='120px' scope='row'>Nip</th>      <td>".(isset($s['nip']) ? $s['nip'] : '')."</td></tr>
                    <tr><th scope='row'>Password</th>               <td>".(isset($s['password']) ? $s['password'] : '')."</td></tr>
                    <tr><th scope='row'>Nama Lengkap</th>           <td>".(isset($s['nama_guru']) ? $s['nama_guru'] : '')."</td></tr>
                    <tr><th scope='row'>Jenis Kelamin</th>          <td>".(isset($s['jenis_kelamin']) ? $s['jenis_kelamin'] : '')."</td></tr>
                    <tr><th scope='row'>No Telpon</th>                  <td>".(isset($s['hp']) ? $s['hp'] : '')."</td></tr>
                    <tr><th scope='row'>Alamat Email</th>           <td>".(isset($s['email']) ? $s['email'] : '')."</td></tr>
                    <tr><th scope='row'>Jenis PTK</th>              <td>".(isset($s['jenis_ptk']) ? $s['jenis_ptk'] : '')."</td></tr>
                    <tr><th scope='row'>Status Pegawai</th>         <td>".(isset($s['status_kepegawaian']) ? $s['status_kepegawaian'] : '')."</td></tr>
                  </tbody>
                  </table>
                </div>

              </div>
            </form>
            </div>";
}  
?>