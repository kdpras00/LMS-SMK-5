<?php if (empty($_GET['act'])){ ?> 
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Mata Pelajaran </h3>
                  <?php if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){ ?>
                  <a class='pull-right btn btn-primary btn-sm' href='index.php?view=matapelajaran&act=tambah'>Tambahkan Data</a>
                  <?php } ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:30px'>No</th>
                        <th>Kode Mapel</th>
                        <th>Nama Mapel</th>
                        <th>Jurusan</th>
                        <th>Tingkat</th>
                        <th>Guru Pengampu</th>

                        <?php if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){ ?>
                        <th style='width:70px'>Action</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $kurikulum_kode = isset($kurikulum['kode_kurikulum']) ? $kurikulum['kode_kurikulum'] : '';
                    if ($kurikulum_kode) {
                        $tampil = mysql_query("SELECT * FROM rb_mata_pelajaran a 
                                                  LEFT JOIN rb_kelompok_mata_pelajaran b ON a.id_kelompok_mata_pelajaran=b.id_kelompok_mata_pelajaran
                                                    LEFT JOIN rb_guru c ON a.nip=c.nip 
                                                      LEFT JOIN rb_jurusan d ON a.kode_jurusan=d.kode_jurusan
                                                        where a.kode_kurikulum='$kurikulum_kode'
                                                          ORDER BY a.urutan ASC");
                        $no = 1;
                        while($r=mysql_fetch_array($tampil)){
                        echo "<tr><td>$no</td>
                                  <td>".(isset($r['kode_pelajaran']) ? $r['kode_pelajaran'] : '')."</td>
                                  <td>".(isset($r['namamatapelajaran']) ? $r['namamatapelajaran'] : '')."</td>
                                  <td>".(isset($r['nama_jurusan']) ? $r['nama_jurusan'] : '')."</td>
                                  <td>".(isset($r['tingkat']) ? $r['tingkat'] : '')."</td>
                                  <td>".(isset($r['nama_guru']) ? $r['nama_guru'] : '')."</td>
    ";
                                  if(isset($_SESSION['level']) && $_SESSION['level']!='kepala'){
                            echo "<td><center>
                                    <a class='btn btn-primary btn-xs' title='Detail Data' href='?view=matapelajaran&act=detail&id=".(isset($r['kode_pelajaran']) ? $r['kode_pelajaran'] : '')."'><span class='glyphicon glyphicon-search'></span></a>
                                    <a class='btn btn-success btn-xs' title='Edit Data' href='?view=matapelajaran&act=edit&id=".(isset($r['kode_pelajaran']) ? $r['kode_pelajaran'] : '')."'><span class='glyphicon glyphicon-edit'></span></a>
                                    <a class='btn btn-danger btn-xs' title='Delete Data' href='javascript:void(0)' onclick=\"konfirmasiHapus('index.php?view=matapelajaran&hapus=".(isset($r['kode_pelajaran']) ? $r['kode_pelajaran'] : '')."')\"><span class='glyphicon glyphicon-remove'></span></a>
                                  </center></td>";
                                  }
                                echo "</tr>";
                          $no++;
                          }
                    }
                      if (isset($_GET['hapus'])){
                          mysql_query("DELETE FROM rb_mata_pelajaran where kode_pelajaran='$_GET[hapus]'");
                          echo "<script>
              setTimeout(function() {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: 'Data berhasil dihapus',
                  showConfirmButton: false,
                  timer: 1500
                }).then(function() {
                  window.location = 'index.php?view=matapelajaran';
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
}elseif(isset($_GET['act']) && $_GET['act']=='edit'){
    if (isset($_POST['update'])){
        mysql_query("UPDATE rb_mata_pelajaran SET kode_pelajaran = '$_POST[a]',
                                         kode_jurusan = '$_POST[c]',
                                         nip = '$_POST[d]',
                                         kode_kurikulum = '$_POST[e]',
                                         namamatapelajaran = '$_POST[f]',
                                         tingkat = '$_POST[h]',
                                         kompetensi_umum = '$_POST[i]',
                                         kompetensi_khusus = '$_POST[j]',
                                         aktif = '$_POST[m]' where kode_pelajaran='$_POST[id]'") or die(mysql_error());
      echo "<script>
            setTimeout(function() {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil diupdate',
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                window.location = 'index.php?view=matapelajaran';
              });
            }, 100);
          </script>";
    }
    $edit = mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[id]'");
    $s = mysql_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Mata Pelajaran</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='".(isset($s['kode_pelajaran']) ? $s['kode_pelajaran'] : '')."'>
                    <tr><th width='140px' scope='row'>Kurikulum</th> <td><select class='form-control' name='e'> 
                             <option value='0' selected>- Pilih Kurikulum -</option>"; 
                              $kurikulum = mysql_query("SELECT * FROM rb_kurikulum");
                                  while($a = mysql_fetch_array($kurikulum)){
                                    if (isset($s['kode_kurikulum']) && $s['kode_kurikulum']==$a['kode_kurikulum']){
                                       echo "<option value='$a[kode_kurikulum]' selected>$a[nama_kurikulum]</option>";
                                    }else{
                                       echo "<option value='$a[kode_kurikulum]'>$a[nama_kurikulum]</option>";
                                    }
                                  }
                             echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Kode Pelajaran <span style='color:red'>*</span></th>       <td><input type='text' class='form-control' name='a' required value='".(isset($s['kode_pelajaran']) ? $s['kode_pelajaran'] : '')."'> </td></tr>
                    <tr><th scope='row'>Nama Mapel</th>           <td><input type='text' class='form-control' name='f' required value='".(isset($s['namamatapelajaran']) ? $s['namamatapelajaran'] : '')."'></td></tr>

                    <tr><th scope='row'>Jurusan</th> <td><select class='form-control' name='c'> 
                             <option value='0' selected>- Pilih Jurusan -</option>"; 
                              $jurusan = mysql_query("SELECT * FROM rb_jurusan");
                                  while($a = mysql_fetch_array($jurusan)){
                                    if (isset($s['kode_jurusan']) && $s['kode_jurusan']==$a['kode_jurusan']){
                                       echo "<option value='$a[kode_jurusan]' selected>$a[nama_jurusan]</option>";
                                    }else{
                                       echo "<option value='$a[kode_jurusan]'>$a[nama_jurusan]</option>";
                                    }
                                  }
                             echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Guru Pengampu</th> <td><select class='form-control' name='d'> 
                             <option value='0' selected>- Pilih Guru Pengampu -</option>"; 
                              $guru = mysql_query("SELECT * FROM rb_guru");
                                  while($a = mysql_fetch_array($guru)){
                                    if (isset($s['nip']) && $s['nip']==$a['nip']){
                                       echo "<option value='$a[nip]' selected>$a[nama_guru]</option>";
                                    }else{
                                       echo "<option value='$a[nip]'>$a[nama_guru]</option>";
                                    }
                                  }
                             echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Tingkat</th>              <td><input type='text' class='form-control' name='h' value='".(isset($s['tingkat']) ? $s['tingkat'] : '')."'></td></tr>
                    <tr><th scope='row'>Kompetensi Umum</th>           <td><input type='text' class='form-control' name='i' value='".(isset($s['kompetensi_umum']) ? $s['kompetensi_umum'] : '')."'></td></tr>
                    <tr><th scope='row'>Kompetensi Khusus</th>           <td><input type='text' class='form-control' name='j' value='".(isset($s['kompetensi_khusus']) ? $s['kompetensi_khusus'] : '')."'></td></tr>


                    <tr><th scope='row'>Aktif</th>                <td>";
                                                                  if (isset($s['aktif']) && $s['aktif']=='Ya'){
                                                                      echo "<input type='radio' name='m' value='Ya' checked> Ya
                                                                             <input type='radio' name='m' value='Tidak'> Tidak";
                                                                  }else{
                                                                      echo "<input type='radio' name='m' value='Ya'> Ya
                                                                             <input type='radio' name='m' value='Tidak' checked> Tidak";
                                                                  }
                  echo "</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?view=matapelajaran'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}elseif(isset($_GET['act']) && $_GET['act']=='tambah'){
    if (isset($_POST['tambah'])){
        mysql_query("INSERT INTO rb_mata_pelajaran VALUES('$_POST[a]','','$_POST[e]','$_POST[c]','$_POST[d]','$_POST[f]','','$_POST[h]','$_POST[i]','$_POST[j]','','','','$_POST[m]')") or die(mysql_error());
        echo "<script>
            setTimeout(function() {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil ditambahkan',
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                window.location = 'index.php?view=matapelajaran';
              });
            }, 100);
          </script>";
    }

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Mata Pelajaran</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='140px' scope='row'>Kurikulum</th> <td><select class='form-control' name='e'> 
                             <option value='0' selected>- Pilih Kurikulum -</option>"; 
                              $kurikulum = mysql_query("SELECT * FROM rb_kurikulum");
                                  while($a = mysql_fetch_array($kurikulum)){
                                    echo "<option value='$a[kode_kurikulum]'>$a[nama_kurikulum]</option>";
                                  }
                             echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Kode Pelajaran <span style='color:red'>*</span></th>       <td><input type='text' class='form-control' name='a' required> </td></tr>
                    <tr><th scope='row'>Nama Mapel</th>           <td><input type='text' class='form-control' name='f' required></td></tr>

                    <tr><th scope='row'>Jurusan</th> <td><select class='form-control' name='c'> 
                             <option value='0' selected>- Pilih Jurusan -</option>"; 
                              $jurusan = mysql_query("SELECT * FROM rb_jurusan");
                                  while($a = mysql_fetch_array($jurusan)){
                                       echo "<option value='$a[kode_jurusan]'>$a[nama_jurusan]</option>";
                                  }
                             echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Guru Pengampu</th> <td><select class='form-control' name='d'> 
                             <option value='0' selected>- Pilih Guru Pengampu -</option>"; 
                              $guru = mysql_query("SELECT * FROM rb_guru");
                                  while($a = mysql_fetch_array($guru)){
                                       echo "<option value='$a[nip]'>$a[nama_guru]</option>";
                                  }
                             echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Tingkat</th>              <td><input type='text' class='form-control' name='h'></td></tr>
                    <tr><th scope='row'>Kompetensi Umum</th>           <td><input type='text' class='form-control' name='i'></td></tr>
                    <tr><th scope='row'>Kompetensi Khusus</th>           <td><input type='text' class='form-control' name='j'></td></tr>


                    <tr><th scope='row'>Aktif</th>                <td><input type='radio' name='m' value='Ya' checked> Ya
                                                                             <input type='radio' name='m' value='Tidak'> Tidak</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=matapelajaran'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}elseif(isset($_GET['act']) && $_GET['act']=='detail'){
    $edit = mysql_query("SELECT a.*, b.nama_kelompok_mata_pelajaran, c.nama_guru, d.nama_kurikulum, e.nama_jurusan FROM rb_mata_pelajaran a 
                                              LEFT JOIN rb_kelompok_mata_pelajaran b ON a.id_kelompok_mata_pelajaran=b.id_kelompok_mata_pelajaran
                                                LEFT JOIN rb_guru c ON a.nip=c.nip
                                                  LEFT JOIN rb_kurikulum d ON a.kode_kurikulum=d.kode_kurikulum
                                                    LEFT JOIN rb_jurusan e ON a.kode_jurusan=e.kode_jurusan
                                                      where a.kode_pelajaran='$_GET[id]'");
    $s = mysql_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Detail Data Mata Pelajaran</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='140px' scope='row'>Kurikulum</th> <td>".(isset($s['nama_kurikulum']) ? $s['nama_kurikulum'] : '')."</td></tr>
                    <tr><th scope='row'>Kode Pelajaran <span style='color:red'>*</span></th>       <td>".(isset($s['kode_pelajaran']) ? $s['kode_pelajaran'] : '')." </td></tr>
                    <tr><th scope='row'>Nama Mapel</th>           <td>".(isset($s['namamatapelajaran']) ? $s['namamatapelajaran'] : '')."</td></tr>

                    <tr><th scope='row'>Jurusan</th>              <td>".(isset($s['nama_jurusan']) ? $s['nama_jurusan'] : '')."</td></tr>
                    <tr><th scope='row'>Guru Pengampu</th>        <td>".(isset($s['nama_guru']) ? $s['nama_guru'] : '')."</td></tr>
                    <tr><th scope='row'>Tingkat</th>              <td>".(isset($s['tingkat']) ? $s['tingkat'] : '')."</td></tr>
                    <tr><th scope='row'>Kompetensi Umum</th>      <td>".(isset($s['kompetensi_umum']) ? $s['kompetensi_umum'] : '')."</td></tr>
                    <tr><th scope='row'>Kompetensi Khusus</th>    <td>".(isset($s['kompetensi_khusus']) ? $s['kompetensi_khusus'] : '')."</td></tr>

                    <tr><th scope='row'>Aktif</th>                <td>".(isset($s['aktif']) ? $s['aktif'] : '')."</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <a href='index.php?view=matapelajaran'><button type='button' class='btn btn-default pull-right'>Kembali</button></a>
                    
                  </div>
              </form>
            </div>";
}
?>