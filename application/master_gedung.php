<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_id = isset($_GET['id']) ? $_GET['id'] : '';

if ($act==''){ ?> 
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Gedung </h3>
                  <?php if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){ ?>
                  <a class='pull-right btn btn-primary btn-sm' href='index.php?view=gedung&act=tambah'>Tambahkan Data</a>
                  <?php } ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Kode Gedung</th>
                        <th>Nama Gedung</th>
                        <th>Jumlah Lantai</th>
                        <th>Panjang</th>
                        <th>Tinggi</th>
                        <th>Lebar</th>
                        <th>Keterangan</th>
                        <th>Aktif</th>
                        <?php if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){ ?>
                        <th style='width:70px'>Action</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $tampil = mysql_query("SELECT * FROM rb_gedung ORDER BY kode_gedung DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>".(isset($r['kode_gedung']) ? $r['kode_gedung'] : '')."</td>
                              <td>".(isset($r['nama_gedung']) ? $r['nama_gedung'] : '')."</td>
                              <td>".(isset($r['jumlah_lantai']) ? $r['jumlah_lantai'] : '')." Lantai</td>
                              <td>".(isset($r['panjang']) ? $r['panjang'] : '')." Meter</td>
                              <td>".(isset($r['tinggi']) ? $r['tinggi'] : '')." Meter</td>
                              <td>".(isset($r['lebar']) ? $r['lebar'] : '')." Meter</td>
                              <td>".(isset($r['keterangan']) ? $r['keterangan'] : '')."</td>
                              <td>".(isset($r['aktif']) ? $r['aktif'] : '')."</td>";
                              if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){
                        echo "<td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='index.php?view=gedung&act=edit&id=".(isset($r['kode_gedung']) ? $r['kode_gedung'] : '')."'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='javascript:void(0)' onclick=\"konfirmasiHapus('index.php?view=gedung&hapus=".(isset($r['kode_gedung']) ? $r['kode_gedung'] : '')."')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }
                      if (isset($_GET['hapus'])){
                          mysql_query("DELETE FROM rb_gedung where kode_gedung='$_GET[hapus]'");
                          echo "<script>
              setTimeout(function() {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: 'Data berhasil dihapus',
                  showConfirmButton: false,
                  timer: 1500
                }).then(function() {
                  window.location = 'index.php?view=gedung';
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
        mysql_query("UPDATE rb_gedung SET kode_gedung = '$_POST[a]',
                                         nama_gedung = '$_POST[b]',
                                         jumlah_lantai = '$_POST[c]',
                                         panjang = '$_POST[d]',
                                         tinggi = '$_POST[e]',
                                         lebar = '$_POST[f]',
                                         keterangan = '$_POST[g]',
                                         aktif = '$_POST[h]' where kode_gedung='$_POST[id]'") or die(mysql_error());
      echo "<script>
            setTimeout(function() {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil diupdate',
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                window.location = 'index.php?view=gedung';
              });
            }, 100);
          </script>";
    }
    $edit = mysql_query("SELECT * FROM rb_gedung where kode_gedung='$get_id'");
    $s = mysql_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Gedung</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='".(isset($s['kode_gedung']) ? $s['kode_gedung'] : '')."'>
                    <tr><th width='120px' scope='row'>Kode Gedung</th> <td><input type='text' class='form-control' name='a' value='". (isset($s['id_gedung']) ? $s['id_gedung'] : (isset($s['kode_gedung']) ? $s['kode_gedung'] : '')) ."'> </td></tr>
                    <tr><th scope='row'>Nama Gedung</th>          <td><input type='text' class='form-control' name='b' value='".(isset($s['nama_gedung']) ? $s['nama_gedung'] : '')."'></td></tr>
                    <tr><th scope='row'>Jumlah Lantai</th>        <td><input type='text' class='form-control' name='c' value='".(isset($s['jumlah_lantai']) ? $s['jumlah_lantai'] : '')."'></td></tr>
                    <tr><th scope='row'>Panjang</th>              <td><input type='text' class='form-control' name='d' value='".(isset($s['panjang']) ? $s['panjang'] : '')."'></td></tr>
                    <tr><th scope='row'>Tinggi</th>               <td><input type='text' class='form-control' name='e' value='".(isset($s['tinggi']) ? $s['tinggi'] : '')."'></td></tr>
                    <tr><th scope='row'>Lebar</th>                <td><input type='text' class='form-control' name='f' value='".(isset($s['lebar']) ? $s['lebar'] : '')."'></td></tr>
                    <tr><th scope='row'>Keterangan</th>           <td><input type='text' class='form-control' name='g' value='".(isset($s['keterangan']) ? $s['keterangan'] : '')."'></td></tr>
                    <tr><th scope='row'>Aktif</th>                <td>";
                                                                  if (isset($s['aktif']) && $s['aktif']=='Ya'){
                                                                      echo "<input type='radio' name='h' value='Ya' checked> Ya
                                                                             <input type='radio' name='h' value='Tidak'> Tidak";
                                                                  }else{
                                                                      echo "<input type='radio' name='h' value='Ya'> Ya
                                                                             <input type='radio' name='h' value='Tidak' checked> Tidak";
                                                                  }
                  echo "</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?view=gedung'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}elseif($act=='tambah'){
    if (isset($_POST['tambah'])){
        mysql_query("INSERT INTO rb_gedung VALUES('$_POST[a]','$_POST[b]','$_POST[c]','$_POST[d]','$_POST[e]','$_POST[f]','$_POST[g]','$_POST[h]')") or die(mysql_error());
        echo "<script>
            setTimeout(function() {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil ditambahkan',
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                window.location = 'index.php?view=gedung';
              });
            }, 100);
          </script>";
    }

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Gedung</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='120px' scope='row'>Kode Gedung</th> <td><input type='text' class='form-control' name='a'> </td></tr>
                    <tr><th scope='row'>Nama Gedung</th>          <td><input type='text' class='form-control' name='b'></td></tr>
                    <tr><th scope='row'>Jumlah Lantai</th>        <td><input type='text' class='form-control' name='c'></td></tr>
                    <tr><th scope='row'>Panjang</th>              <td><input type='text' class='form-control' name='d'></td></tr>
                    <tr><th scope='row'>Tinggi</th>               <td><input type='text' class='form-control' name='e'></td></tr>
                    <tr><th scope='row'>Lebar</th>                <td><input type='text' class='form-control' name='f'></td></tr>
                    <tr><th scope='row'>Keterangan</th>           <td><input type='text' class='form-control' name='g'></td></tr>
                    <tr><th scope='row'>Aktif</th>                <td><input type='radio' name='h' value='Ya'> Ya
                                                                             <input type='radio' name='h' value='Tidak'> Tidak
                    </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=gedung'><button class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}
?>