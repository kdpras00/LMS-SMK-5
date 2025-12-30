<?php
// Get teacher's NIP from session
$nip_guru = $_SESSION['id'];


// Get action
$act = isset($_GET['act']) ? $_GET['act'] : '';

// List RPP (default view)
if (empty($act)) {
    ?>
    <div class="col-xs-12">  
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Daftar RPP Saya</h3>
          <a class='pull-right btn btn-primary btn-sm' href='index.php?view=uploadrpp&act=tambah'>
            <i class='fa fa-plus'></i> Tambah RPP
          </a>
        </div>
        <div class="box-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style='width:30px'>No</th>
                <th>Nama Guru</th>
                <th>Mata Pelajaran</th>
                <th>Nama File</th>
                <th>File</th>
                <th style='width:100px'>Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT a.*, b.namamatapelajaran, c.nama_guru 
                      FROM rb_elearning1 a
                      LEFT JOIN rb_jadwal_pelajaran jdwl ON a.kodejdwl = jdwl.kodejdwl
                      LEFT JOIN rb_mata_pelajaran b ON jdwl.kode_pelajaran = b.kode_pelajaran
                      LEFT JOIN rb_guru c ON jdwl.nip = c.nip
                      WHERE jdwl.nip = '$nip_guru'
                      ORDER BY a.id_elearning DESC";
            
            $tampil = mysql_query($query);
            $no = 1;
            
            while($r = mysql_fetch_array($tampil)){
                echo "<tr>
                        <td>$no</td>
                        <td>".$r['nama_guru']."</td>
                        <td>".$r['namamatapelajaran']."</td>
                        <td>".$r['nama_file']."</td>
                        <td>";
                if($r['file_upload'] != ''){
                    echo "<a href='files/".$r['file_upload']."' target='_blank' class='btn btn-xs btn-success'>
                            <i class='fa fa-download'></i> Download
                          </a>";
                } else {
                    echo "<span class='label label-warning'>Tidak ada file</span>";
                }
                echo "</td>
                        <td>
                          <center>
                            <a class='btn btn-info btn-xs' title='Detail RPP' href='index.php?view=uploadrpp&act=detail&id_elearning=".$r['id_elearning']."'>
                              <span class='glyphicon glyphicon-eye-open'></span> Detail
                            </a>
                            <a class='btn btn-danger btn-xs' title='Hapus RPP' href='javascript:void(0)' onclick=\"konfirmasiHapus('index.php?view=uploadrpp&hapus=".$r['id_elearning']."')\">
                              <span class='glyphicon glyphicon-remove'></span>
                            </a>
                          </center>
                        </td>
                      </tr>";
                $no++;
            }
            
            // Handle delete
            if (isset($_GET['hapus'])){
                $id = mysql_real_escape_string($_GET['hapus']);
                
                // Get file name to delete
                $get_file = mysql_fetch_array(mysql_query("SELECT file_upload FROM rb_elearning1 WHERE id_elearning='$id'"));
                if($get_file['file_upload'] != ''){
                    @unlink('files/'.$get_file['file_upload']);
                }
                
                mysql_query("DELETE FROM rb_elearning1 WHERE id_elearning='$id'");
                echo "<script>
                        setTimeout(function() {
                          Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'RPP berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                          }).then(function() {
                            window.location = 'index.php?view=uploadrpp';
                          });
                        }, 100);
                      </script>";
            }
            ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php
}

// Upload new RPP
elseif($act == 'tambah'){
    if (isset($_POST['tambah'])){
        // Validation
        if (!isset($_POST['mapel']) || $_POST['mapel'] == '0'){
            echo "<script>window.alert('Silakan pilih Mata Pelajaran terlebih dahulu.');
                        window.location='index.php?view=uploadrpp&act=tambah';</script>";
            exit;
        }
        
        if (!isset($_POST['nama_file']) || trim($_POST['nama_file']) == ''){
            echo "<script>window.alert('Nama File tidak boleh kosong.');
                        window.location='index.php?view=uploadrpp&act=tambah';</script>";
            exit;
        }
        
        if (!isset($_FILES['file']) || $_FILES['file']['name'] == ''){
            echo "<script>window.alert('File RPP wajib diupload.');
                        window.location='index.php?view=uploadrpp&act=tambah';</script>";
            exit;
        }
        
        // Process upload
        $dir_gambar = 'files/';
        $kodejdwl = mysql_real_escape_string($_POST['mapel']);
        $nama_file = mysql_real_escape_string($_POST['nama_file']);
        $waktu = date("Y-m-d H:i:s");
        
        $file = $_FILES['file'];
        $filename_rpp = date("YmdHis").'-RPP-'.basename($file['name']);
        $uploadfile = $dir_gambar . $filename_rpp;
        
        if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
            $query = "INSERT INTO rb_elearning1 VALUES (NULL,'1','$kodejdwl','$nama_file','$filename_rpp','$waktu','$waktu','')";
            $result = mysql_query($query);
            
            if ($result){
                echo "<script>
                        setTimeout(function() {
                          Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'RPP berhasil diupload!',
                            showConfirmButton: false,
                            timer: 1500
                          }).then(function() {
                            window.location = 'index.php?view=uploadrpp';
                          });
                        }, 100);
                      </script>";
            } else {
                echo "<script>window.alert('Gagal menyimpan data: " . mysql_error() . "');
                            window.location='index.php?view=uploadrpp&act=tambah';</script>";
            }
        } else {
            echo "<script>window.alert('Gagal upload file.');
                        window.location='index.php?view=uploadrpp&act=tambah';</script>";
        }
    }
    
    // Display form
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'><i class='fa fa-file-pdf-o'></i> Tambah RPP</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data' onsubmit='return validateFormRPP()'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='200px' scope='row'>Nama Guru</th> 
                      <td>";
                      
                      // Get and display guru name
                      $nip_guru = $_SESSION['id'];
                      $guru_q = mysql_query("SELECT nama_guru FROM rb_guru WHERE nip='$nip_guru'");
                      $guru_r = mysql_fetch_array($guru_q);
                      echo "<input type='text' class='form-control' value='".$guru_r['nama_guru']."' readonly style='background:#f4f4f4'>";
                      
                  echo "</td>
                    </tr>
                    <tr><th scope='row'>Mata Pelajaran <span style='color:red'>*</span></th> 
                      <td><select class='form-control' name='mapel' required>
                            <option value='0' selected>- Pilih Mata Pelajaran -</option>";
                            
                            // Get teacher's schedule
                            $jadwal = mysql_query("SELECT a.kodejdwl, b.namamatapelajaran, c.nama_kelas 
                                                   FROM rb_jadwal_pelajaran a
                                                   JOIN rb_mata_pelajaran b ON a.kode_pelajaran = b.kode_pelajaran
                                                   JOIN rb_kelas c ON a.kode_kelas = c.kode_kelas
                                                   WHERE a.nip = '$nip_guru'
                                                   ORDER BY b.namamatapelajaran, c.nama_kelas");
                            while($j = mysql_fetch_array($jadwal)){
                                echo "<option value='".$j['kodejdwl']."'>".$j['namamatapelajaran']." - ".$j['nama_kelas']."</option>";
                            }
                            
                      echo "</select>
                      </td></tr>
                    <tr><th scope='row'>Nama File <span style='color:red'>*</span></th>        
                      <td><input type='text' class='form-control' name='nama_file' required placeholder='Contoh: RPP Tenses Pertemuan 1'></td>
                    </tr>
                    <tr><th scope='row'>File RPP <span style='color:red'>*</span></th>             
                      <td><div style='position:relative;'>
                            <a class='btn btn-success' href='javascript:;'>
                              <i class='fa fa-file-pdf-o'></i> Pilih File RPP
                              <input type='file' class='files' name='file' onchange='$(\"#upload-file-info\").html($(this).val());' accept='.pdf,.doc,.docx' required>
                            </a> <span style='width:200px' class='label label-success' id='upload-file-info'></span>
                            <br><small style='color:#666'><i>Format: PDF atau Word</i></small>
                          </div>
                      </td>
                    </tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-success'><i class='fa fa-save'></i> Simpan</button>
                    <a href='index.php?view=uploadrpp'><button type='button' class='btn btn-default pull-right'>Batal</button></a>
                  </div>
              </form>
              <script>
              function validateFormRPP() {
                var mapel = document.getElementsByName('mapel')[0].value;
                var namaFile = document.getElementsByName('nama_file')[0].value;
                var file = document.getElementsByName('file')[0];
                
                if (mapel == '0' || mapel == '') {
                  alert('Silakan pilih Mata Pelajaran!');
                  return false;
                }
                
                if (namaFile.trim() == '') {
                  alert('Nama File tidak boleh kosong!');
                  return false;
                }
                
                if (file.files.length == 0) {
                  alert('File RPP wajib diupload!');
                  return false;
                }
                
                return true;
              }
              </script>
            </div>";
}

// Detail RPP
elseif($act == 'detail'){
    $id = mysql_real_escape_string($_GET['id_elearning']);
    $detail = mysql_query("SELECT a.*, b.namamatapelajaran, c.nama_guru 
                           FROM rb_elearning1 a
                           LEFT JOIN rb_jadwal_pelajaran jdwl ON a.kodejdwl = jdwl.kodejdwl
                           LEFT JOIN rb_mata_pelajaran b ON jdwl.kode_pelajaran = b.kode_pelajaran
                           LEFT JOIN rb_guru c ON jdwl.nip = c.nip
                           WHERE a.id_elearning='$id'");
    $d = mysql_fetch_array($detail);
    
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'><i class='fa fa-eye'></i> Detail RPP</h3>
                </div>
              <div class='box-body'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='200px'>Nama Guru</th> 
                      <td>".$d['nama_guru']."</td>
                    </tr>
                    <tr><th>Mata Pelajaran</th> 
                      <td>".$d['namamatapelajaran']."</td>
                    </tr>
                    <tr><th>Nama File</th>        
                      <td>".$d['nama_file']."</td>
                    </tr>
                    <tr><th>File RPP</th>             
                      <td>";
                      if($d['file_upload'] != ''){
                          echo "<a href='files/".$d['file_upload']."' target='_blank' class='btn btn-sm btn-success'>
                                  <i class='fa fa-download'></i> Download ".$d['file_upload']."
                                </a>";
                      } else {
                          echo "<span class='label label-warning'>Tidak ada file</span>";
                      }
                  echo "</td>
                    </tr>
                    <tr><th>Tanggal Upload</th>             
                      <td>".date('d-m-Y H:i', strtotime($d['waktu_mulai']))."</td>
                    </tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <a href='index.php?view=uploadrpp'><button type='button' class='btn btn-default'>Kembali</button></a>
                  </div>
            </div>";
}
?>
