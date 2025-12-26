<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_id = isset($_GET['id']) ? $_GET['id'] : '';

if ($act==''){ ?> 
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Predikat / Grade Nilai </h3>
                  <?php if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){ ?>
                  <a class='pull-right btn btn-primary btn-sm' href='index.php?view=predikat&act=tambah'>Tambahkan Data</a>
                  <?php } ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Status</th>
                        <th>Dari</th>
                        <th>Sampai</th>
                        <th>Grade</th>
                        <th>Keterangan</th>
                        <?php if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){ ?>
                        <th style='width:70px'>Action</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $tampil = mysql_query("SELECT a.id_predikat, a.nilai_a, a.nilai_b, a.grade, a.keterangan, a.kode_kelas as akelas,  b.nama_kelas FROM rb_predikat a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas ORDER BY a.id_predikat DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                      if (isset($r['akelas']) && $r['akelas']=='0'){
                          $kelas = 'Lainnya';
                      }else{
                          $kelas = (isset($r['akelas']) ? $r['akelas'] : '');
                      }
                    echo "<tr><td>$no</td>
                              <td>$kelas</td>
                              <td>".(isset($r['nilai_a']) ? $r['nilai_a'] : '')."</td>
                              <td>".(isset($r['nilai_b']) ? $r['nilai_b'] : '')."</td>
                              <td>".(isset($r['grade']) ? $r['grade'] : '')."</td>
                              <td>".(isset($r['keterangan']) ? $r['keterangan'] : '')."</td>";
                              if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){
                        echo "<td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='index.php?view=predikat&act=edit&id=".(isset($r['id_predikat']) ? $r['id_predikat'] : '')."'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='javascript:void(0)' onclick=\"konfirmasiHapus('index.php?view=predikat&hapus=".(isset($r['id_predikat']) ? $r['id_predikat'] : '')."')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }
                      if (isset($_GET['hapus'])){
                          mysql_query("DELETE FROM rb_predikat where id_predikat='$_GET[hapus]'");
                          echo "<script>
              setTimeout(function() {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: 'Data berhasil dihapus',
                  showConfirmButton: false,
                  timer: 1500
                }).then(function() {
                  window.location = 'index.php?view=predikat';
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
}elseif($act=='edit'){
    if (isset($_POST['update'])){
        mysql_query("UPDATE rb_predikat SET kode_kelas = '$_POST[aa]', 
                                         nilai_a = '$_POST[a]',
                                         nilai_b = '$_POST[b]',
                                         grade = '$_POST[c]',
                                         keterangan = '$_POST[d]' where id_predikat='$_POST[id]'") or die(mysql_error());
      echo "<script>
            setTimeout(function() {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil diupdate',
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                window.location = 'index.php?view=predikat';
              });
            }, 100);
          </script>";
    }
    $edit = mysql_query("SELECT * FROM rb_predikat where id_predikat='$get_id'");
    $s = mysql_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Predikat / Grade</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='".(isset($s['id_predikat']) ? $s['id_predikat'] : '')."'>
                    <tr><th scope='row'>Kelas</th> <td><select class='form-control' name='aa'>"; 
                                                          echo "<option value='0' selected>Lainnya</option>";
                                                          $kelas = mysql_query("SELECT * FROM rb_kelas");
                                                          while ($k = mysql_fetch_array($kelas)){
                                                            if (isset($s['kode_kelas']) && isset($k['kode_kelas']) && $s['kode_kelas']==$k['kode_kelas']){
                                                              echo "<option value='$k[kode_kelas]' selected>$k[kode_kelas] - $k[nama_kelas]</option>";
                                                            }else{
                                                              echo "<option value='$k[kode_kelas]'>$k[kode_kelas] - $k[nama_kelas]</option>";
                                                            }
                                                          }
                                                      echo "</select></td></tr>
                    <tr><th width='120px' scope='row'>Dari</th> <td><input type='text' class='form-control' name='a' value='".(isset($s['nilai_a']) ? $s['nilai_a'] : '')."'> </td></tr>
                    <tr><th scope='row'>Sampai</th> <td><input type='text' class='form-control' name='b' value='".(isset($s['nilai_b']) ? $s['nilai_b'] : '')."'> </td></tr>
                    <tr><th scope='row'>Grade</th> <td><input type='text' class='form-control' name='c' value='".(isset($s['grade']) ? $s['grade'] : '')."'> </td></tr>
                    <tr><th scope='row'>Keterangan</th> <td><input type='text' class='form-control' name='d' value='".(isset($s['keterangan']) ? $s['keterangan'] : '')."'> </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?view=predikat'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}elseif($act=='tambah'){
    if (isset($_POST['tambah'])){
        mysql_query("INSERT INTO rb_predikat VALUES(NULL,'$_POST[aa]','$_POST[a]','$_POST[b]','$_POST[c]','$_POST[d]')") or die(mysql_error());
        echo "<script>
            setTimeout(function() {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil ditambahkan',
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                window.location = 'index.php?view=predikat';
              });
            }, 100);
          </script>";
    }

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Predikat / Grade</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th scope='row'>Kelas</th> <td><select class='form-control' name='aa'>"; 
                                                          echo "<option value='0' selected>Lainnya</option>";
                                                          $kelas = mysql_query("SELECT * FROM rb_kelas");
                                                          while ($k = mysql_fetch_array($kelas)){
                                                              echo "<option value='".(isset($k['kode_kelas']) ? $k['kode_kelas'] : '')."'>".(isset($k['kode_kelas']) ? $k['kode_kelas'] : '')." - ".(isset($k['nama_kelas']) ? $k['nama_kelas'] : '')."</option>";
                                                          }
                                                      echo "</select></td></tr>
                    <tr><th width='120px' scope='row'>Dari</th> <td><input type='text' class='form-control' name='a'> </td></tr>
                    <tr><th scope='row'>Sampai</th> <td><input type='text' class='form-control' name='b'> </td></tr>
                    <tr><th scope='row'>Grade</th> <td><input type='text' class='form-control' name='c'> </td></tr>
                    <tr><th scope='row'>Keterangan</th> <td><input type='text' class='form-control' name='d'> </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=predikat'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}
?>