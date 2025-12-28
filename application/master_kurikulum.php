<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_id = isset($_GET['id']) ? $_GET['id'] : '';

if ($act==''){ ?> 
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Kurikulum </h3>
                  <?php if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){ ?>
                  <a class='pull-right btn btn-primary btn-sm' href='index.php?view=kurikulum&act=tambah'>Tambahkan Data</a>
                  <?php } ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Nama Kurikulum</th>
                        <th>Status Aktif</th>
                        <?php if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){ ?>
                        <th style='width:70px'>Action</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $tampil = mysql_query("SELECT * FROM rb_kurikulum ORDER BY kode_kurikulum DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>".(isset($r['nama_kurikulum']) ? $r['nama_kurikulum'] : '')."</td>
                              <td>".(isset($r['status_kurikulum']) ? $r['status_kurikulum'] : '')."</td>";
                              if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){
                        echo "<td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='index.php?view=kurikulum&act=edit&id=".(isset($r['kode_kurikulum']) ? $r['kode_kurikulum'] : '')."'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='javascript:void(0)' onclick=\"konfirmasiHapus('index.php?view=kurikulum&hapus=".(isset($r['kode_kurikulum']) ? $r['kode_kurikulum'] : '')."')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }
                      if (isset($_GET['hapus'])){
                          mysql_query("DELETE FROM rb_kurikulum where kode_kurikulum='$_GET[hapus]'");
                          echo "<script>
              setTimeout(function() {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: 'Data berhasil dihapus',
                  showConfirmButton: false,
                  timer: 1500
                }).then(function() {
                  window.location = 'index.php?view=kurikulum';
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
        // Validasi input
        $nama_kurikulum = isset($_POST['a']) ? trim($_POST['a']) : '';
        $status_kurikulum = isset($_POST['b']) ? $_POST['b'] : '';
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        
        if (empty($nama_kurikulum) || empty($status_kurikulum)){
            echo "<script>
                setTimeout(function() {
                  Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Semua field harus diisi!',
                    showConfirmButton: true
                  });
                }, 100);
              </script>";
        } else {
            mysql_query("UPDATE rb_kurikulum SET nama_kurikulum = '$nama_kurikulum',
                                             status_kurikulum = '$status_kurikulum' where kode_kurikulum='$id'") or die(mysql_error());
            echo "<script>
                setTimeout(function() {
                  Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil diupdate',
                    showConfirmButton: false,
                    timer: 1500
                  }).then(function() {
                    window.location = 'index.php?view=kurikulum';
                  });
                }, 100);
              </script>";
        }
    }
    $edit = mysql_query("SELECT * FROM rb_kurikulum where kode_kurikulum='$get_id'");
    $s = mysql_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Kurikulum</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='".(isset($s['kode_kurikulum']) ? $s['kode_kurikulum'] : '')."'>
                    <tr><th width='120px' scope='row'>Nama Kurikulum</th> <td><input type='text' class='form-control' name='a' value='".(isset($s['nama_kurikulum']) ? $s['nama_kurikulum'] : '')."' required> </td></tr>
                    <tr><th scope='row'>Status Aktif</th>     <td>";
                                                                  if (isset($s['status_kurikulum']) && $s['status_kurikulum']=='Ya'){
                                                                      echo "<input type='radio' name='b' value='Ya' checked required> Ya
                                                                             <input type='radio' name='b' value='Tidak' required> Tidak";
                                                                  }else{
                                                                      echo "<input type='radio' name='b' value='Ya' required> Ya
                                                                             <input type='radio' name='b' value='Tidak' checked required> Tidak";
                                                                  }
                  echo "</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?view=kurikulum'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}elseif($act=='tambah'){
    if (isset($_POST['tambah'])){
        // Validasi input
        $nama_kurikulum = isset($_POST['a']) ? trim($_POST['a']) : '';
        $status_kurikulum = isset($_POST['b']) ? $_POST['b'] : '';
        
        if (empty($nama_kurikulum) || empty($status_kurikulum)){
            echo "<script>
                setTimeout(function() {
                  Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Semua field harus diisi!',
                    showConfirmButton: true
                  });
                }, 100);
              </script>";
        } else {
            mysql_query("INSERT INTO rb_kurikulum (nama_kurikulum, status_kurikulum) VALUES('$nama_kurikulum','$status_kurikulum')") or die(mysql_error());
            echo "<script>
                setTimeout(function() {
                  Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil ditambahkan',
                    showConfirmButton: false,
                    timer: 1500
                  }).then(function() {
                    window.location = 'index.php?view=kurikulum';
                  });
                }, 100);
              </script>";
        }
    }

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Kurikulum</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='120px' scope='row'>Nama Kurikulum</th> <td><input type='text' class='form-control' name='a' required> </td></tr>
                    <tr><th scope='row'>Status Aktif</th>     <td><input type='radio' name='b' value='Ya' required> Ya
                                                                  <input type='radio' name='b' value='Tidak' required> Tidak</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=kurikulum'><button class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}
?>