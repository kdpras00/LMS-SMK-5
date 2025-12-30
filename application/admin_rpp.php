<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$get_guru = isset($_GET['guru']) ? $_GET['guru'] : '';

// List RPP (default view - Admin view only)
if ($act==''){ 
cek_session_admin();
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Perangkat Pembelajaran Guru (RPP)</h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='adminrpp'>
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
                    <select name='guru' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Guru -</option>";
                            $guru = mysql_query("SELECT * FROM rb_guru ORDER BY nama_guru");
                            while ($g = mysql_fetch_array($guru)){
                              if ($get_guru==$g['nip']){
                                echo "<option value='$g[nip]' selected>$g[nama_guru]</option>";
                              }else{
                                echo "<option value='$g[nip]'>$g[nama_guru]</option>";
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
                        <th style='width:30px'>No</th>
                        <th>Nama Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Nama File</th>
                        <th>File</th>
                        <th style='width:80px'>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    $where = "1=1";
                    if ($get_tahun != ''){
                        $where .= " AND jdwl.id_tahun_akademik='$get_tahun'";
                    }
                    if ($get_guru != ''){
                        $where .= " AND jdwl.nip='$get_guru'";
                    }
                    
                    $tampil = mysql_query("SELECT e.*, b.namamatapelajaran, c.nama_guru
                                          FROM rb_elearning1 e
                                          JOIN rb_jadwal_pelajaran jdwl ON e.kodejdwl=jdwl.kodejdwl
                                          JOIN rb_mata_pelajaran b ON jdwl.kode_pelajaran=b.kode_pelajaran
                                          JOIN rb_guru c ON jdwl.nip=c.nip 
                                          WHERE $where
                                          ORDER BY e.id_elearning DESC");
                    
                    $no = 1;
                    if(mysql_num_rows($tampil) > 0){
                        while($r=mysql_fetch_array($tampil)){
                        echo "<tr><td>$no</td>
                                  <td>$r[nama_guru]</td>
                                  <td>$r[namamatapelajaran]</td>
                                  <td>$r[nama_file]</td>
                                  <td>";
                        if($r['file_upload'] != ''){
                            echo "<a href='files/$r[file_upload]' target='_blank' class='btn btn-xs btn-success'>
                                    <i class='fa fa-download'></i> Download
                                  </a>";
                        } else {
                            echo "<span class='label label-warning'>Tidak ada file</span>";
                        }
                        echo "</td>
                                  <td>
                                    <center>
                                      <a class='btn btn-info btn-xs' title='Detail' href='index.php?view=adminrpp&act=detail&id_elearning=$r[id]'>
                                        <span class='glyphicon glyphicon-eye-open'></span> Detail
                                      </a>
                                    </center>
                                  </td>
                              </tr>";
                        $no++;
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center'>Tidak ada data RPP</td></tr>";
                    }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
<?php
}

// Detail RPP (Admin view-only)
elseif($act == 'detail'){
    cek_session_admin();
    $id = mysql_real_escape_string($_GET['id']);
    $detail = mysql_query("SELECT e.*, b.namamatapelajaran, c.nama_guru
                           FROM rb_elearning1 e
                           JOIN rb_jadwal_pelajaran jdwl ON e.kodejdwl=jdwl.kodejdwl
                           JOIN rb_mata_pelajaran b ON jdwl.kode_pelajaran=b.kode_pelajaran
                           JOIN rb_guru c ON jdwl.nip=c.nip
                           WHERE e.id_elearning='$id'");
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
                    <a href='index.php?view=adminrpp'><button type='button' class='btn btn-default'>Kembali</button></a>
                  </div>
            </div>";
}
?>
