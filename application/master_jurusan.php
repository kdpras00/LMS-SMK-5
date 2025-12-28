<?php 
if (isset($_GET['hapus'])){
    mysql_query("DELETE FROM rb_jurusan where kode_jurusan='$_GET[hapus]'");
    $_SESSION['notif'] = "Data berhasil dihapus";
    echo "<script>document.location='index.php?view=jurusan';</script>";
    exit;
}

if (isset($_SESSION['notif']) && empty($_GET['act'])){
    echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '$_SESSION[notif]',
                showConfirmButton: false,
                timer: 1500
            });
          </script>";
    unset($_SESSION['notif']);
}

if (empty($_GET['act'])){ ?> 
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Jurusan </h3>
                  <?php if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){ ?>
                  <a class='pull-right btn btn-primary btn-sm' href='index.php?view=jurusan&act=tambah'>Tambahkan Data</a>
                  <?php } ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Kode Jurusan</th>
                        <th>Nama Jurusan</th>
                         <th>Fase</th>
                        <th>Bidang Keahlian</th>
                        <th>Kompetensi Umum</th>
                        <th>Kompetensi Khusus</th>
                        <?php if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){ ?>
                        <th style='width:70px'>Action</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $tampil = mysql_query("SELECT * FROM rb_jurusan ORDER BY kode_jurusan DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>".(isset($r['kode_jurusan']) ? $r['kode_jurusan'] : '')."</td>
                              <td>".(isset($r['nama_jurusan']) ? $r['nama_jurusan'] : '')."</td>
                              <td>".(isset($r['fase']) ? $r['fase'] : '')."</td>
                              <td>".(isset($r['bidang_keahlian']) ? $r['bidang_keahlian'] : '')."</td>
                              <td>".(isset($r['kompetensi_umum']) ? $r['kompetensi_umum'] : '')."</td>
                              <td>".(isset($r['kompetensi_khusus']) ? $r['kompetensi_khusus'] : '')."</td>";
                              if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){
                        echo "<td><center>
                                <a class='btn btn-primary btn-xs' title='Edit Data' href='?view=jurusan&act=detail&id=".(isset($r['kode_jurusan']) ? $r['kode_jurusan'] : '')."'><span class='glyphicon glyphicon-search'></span></a>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?view=jurusan&act=edit&id=".(isset($r['kode_jurusan']) ? $r['kode_jurusan'] : '')."'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='javascript:void(0)' onclick=\"konfirmasiHapus('index.php?view=jurusan&hapus=".(isset($r['kode_jurusan']) ? $r['kode_jurusan'] : '')."')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }



                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
<?php 
}elseif(isset($_GET['act']) && $_GET['act']=='detail'){
    $edit = mysql_query("SELECT * FROM rb_jurusan where kode_jurusan='$_GET[id]'");
    $s = mysql_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Detail Data Jurusan</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='".(isset($s['kode_jurusan']) ? $s['kode_jurusan'] : '')."'>
                    <tr><th width='140px' scope='row'>Kode Jurusan</th> <td>".(isset($s['kode_jurusan']) ? $s['kode_jurusan'] : '')."</td></tr>
                    <tr><th scope='row'>Nama Jurusan</th>       <td>".(isset($s['nama_jurusan']) ? $s['nama_jurusan'] : '')."</td></tr>
                    <tr><th scope='row'>Bidang Keahlian</th>    <td>".(isset($s['bidang_keahlian']) ? $s['bidang_keahlian'] : '')."</td></tr>
                    <tr><th scope='row'>Kompetensi Umum</th>    <td>".(isset($s['kompetensi_umum']) ? $s['kompetensi_umum'] : '')."</td></tr>
                    <tr><th scope='row'>Kompetensi Khusus</th>  <td>".(isset($s['kompetensi_khusus']) ? $s['kompetensi_khusus'] : '')."</td></tr>
                    <tr><th scope='row'>Aktif</th>              <td>".(isset($s['aktif']) ? $s['aktif'] : '')."</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <a href='index.php?view=jurusan'><button type='button' class='btn btn-default pull-right'>Kembali</button></a>
              </div>
              </form>
            </div>";
}elseif(isset($_GET['act']) && $_GET['act']=='edit'){
    if (isset($_POST['update'])){
        // Validasi input
        $has_error = false;
        foreach($_POST as $key => $value) {
            if($key != 'update' && $key != 'id' && is_string($value) && trim($value) == '') {
                // Skip field opsional
                if(!in_array($key, array('f', 'g', 'keterangan'))) {
                    $has_error = true;
                    break;
                }
            }
        }
        
        if($has_error){
            echo "<script>
                setTimeout(function() {
                  Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Semua field wajib harus diisi!',
                    showConfirmButton: true
                  });
                }, 100);
              </script>";
        } else {
        
        mysql_query("UPDATE rb_jurusan SET kode_jurusan = '$_POST[a]',
                                         nama_jurusan = '$_POST[b]',
                                         bidang_keahlian = '$_POST[d]',
                                         kompetensi_umum = '$_POST[e]',
                                         kompetensi_khusus = '$_POST[f]',
                                         aktif = '$_POST[j]' where kode_jurusan='$_POST[id]'") or die(mysql_error());
      $_SESSION['notif'] = "Data berhasil diupdate";
      echo "<script>document.location='index.php?view=jurusan';</script>";
      exit;
        }
    }
    $edit = mysql_query("SELECT * FROM rb_jurusan where kode_jurusan='$_GET[id]'");
    $s = mysql_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Jurusan</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='".(isset($s['kode_jurusan']) ? $s['kode_jurusan'] : '')."'>
                    <tr><th width='140px' scope='row'>Kode Jurusan</th> <td><input type='text' class='form-control' name='a' required value='".(isset($s['kode_jurusan']) ? $s['kode_jurusan'] : '')."'> </td></tr>
                    <tr><th scope='row'>Nama Jurusan</th>       <td><input type='text' class='form-control' name='b' required value='".(isset($s['nama_jurusan']) ? $s['nama_jurusan'] : '')."'></td></tr>
                    <tr><th scope='row'>Bidang Keahlian</th>    <td><input type='text' class='form-control' name='d' required value='".(isset($s['bidang_keahlian']) ? $s['bidang_keahlian'] : '')."'></td></tr>
                    <tr><th scope='row'>Kompetensi Umum</th>    <td><input type='text' class='form-control' name='e' required value='".(isset($s['kompetensi_umum']) ? $s['kompetensi_umum'] : '')."'></td></tr>
                    <tr><th scope='row'>Kompetensi Khusus</th>  <td><input type='text' class='form-control' name='f' required value='".(isset($s['kompetensi_khusus']) ? $s['kompetensi_khusus'] : '')."'></td></tr>
                    <tr><th scope='row'>Aktif</th>                <td>";
                                                                  if (isset($s['aktif']) && $s['aktif']=='Ya'){
                                                                      echo "<input type='radio' name='j' required value='Ya' checked> Ya
                                                                             <input type='radio' name='j' required value='Tidak'> Tidak";
                                                                  }else{
                                                                      echo "<input type='radio' name='j' required value='Ya'> Ya
                                                                             <input type='radio' name='j' required value='Tidak' checked> Tidak";
                                                                  }
                  echo "</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?view=jurusan'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}elseif(isset($_GET['act']) && $_GET['act']=='tambah'){
    if (isset($_POST['tambah'])){
        // Validasi input
        $has_error = false;
        foreach($_POST as $key => $value) {
            if($key != 'tambah' && is_string($value) && trim($value) == '') {
                // Skip field opsional
                if(!in_array($key, array('f', 'g', 'keterangan'))) {
                    $has_error = true;
                    break;
                }
            }
        }
        
        if($has_error){
            echo "<script>
                setTimeout(function() {
                  Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Semua field wajib harus diisi!',
                    showConfirmButton: true
                  });
                }, 100);
              </script>";
        } else {
        
        mysql_query("INSERT INTO rb_jurusan VALUES('$_POST[a]','$_POST[b]','$_POST[c]','$_POST[d]','$_POST[e]','$_POST[f]','$_POST[g]','$_POST[h]','$_POST[i]','$_POST[j]')") or die(mysql_error());
        $_SESSION['notif'] = "Data berhasil ditambahkan";
        echo "<script>document.location='index.php?view=jurusan';</script>";
        exit;
        }
    }

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Jurusan</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value=''>
                    <tr><th width='140px' scope='row'>Kode Jurusan</th> <td><input type='text' class='form-control' name='a' required> </td></tr>
                    <tr><th scope='row'>Nama Jurusan</th>       <td><input type='text' class='form-control' name='b' required></td></tr>
                    <tr><th scope='row'>Nama Jurusan En</th>    <td><input type='text' class='form-control' name='c' required></td></tr>
                    <tr><th scope='row'>Bidang Keahlian</th>    <td><input type='text' class='form-control' name='d' required></td></tr>
                    <tr><th scope='row'>Kompetensi Umum</th>    <td><input type='text' class='form-control' name='e' required></td></tr>
                    <tr><th scope='row'>Kompetensi Khusus</th>  <td><input type='text' class='form-control' name='f' required></td></tr>
                    <tr><th scope='row'>Pejabat</th>            <td><input type='text' class='form-control' name='g'></td></tr>
                    <tr><th scope='row'>Jabataan</th>           <td><input type='text' class='form-control' name='h' required></td></tr>
                    <tr><th scope='row'>Keterangan</th>           <td><input type='text' class='form-control' name='i' required></td></tr>
                    <tr><th scope='row'>Aktif</th>                <td><input type='radio' name='j' required value='Ya'> Ya
                                                                      <input type='radio' name='j' required value='Tidak'> Tidak </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=jurusan'><button class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}
?>