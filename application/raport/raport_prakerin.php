<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$get_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$get_delete = isset($_GET['delete']) ? $_GET['delete'] : '';
$get_edit = isset($_GET['edit']) ? $_GET['edit'] : '';
$get_nisn = isset($_GET['nisn']) ? $_GET['nisn'] : '';

if ($act==''){ 
    if (isset($_POST['simpan'])){
            if ($_POST['status']=='Update'){
              mysql_query("UPDATE rb_nilai_prakerin SET kegiatan='$_POST[a]', nilai='$_POST[b]', deskripsi='$_POST[c]' where id_nilai_extrakulikuler='$_POST[id]'");
            }else{
              mysql_query("INSERT INTO rb_nilai_prakerin VALUES('','$get_tahun','$_POST[nisn]','$get_kelas','$_POST[a]','$_POST[b]','$_POST[c]','$_SESSION[id]','".date('Y-m-d H:i:s')."')");
            }
        echo "<script>document.location='index.php?view=prakerin&tahun=$get_tahun&kelas=$get_kelas#$_POST[nisn]';</script>";
    }

    if ($get_delete != ''){
        mysql_query("DELETE FROM rb_nilai_prakerin where id_nilai_extrakulikuler='$get_delete'");
        echo "<script>document.location='index.php?view=prakerin&tahun=$get_tahun&kelas=$get_kelas#$get_nisn';</script>";
    }
?> 
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Input Prakerin Siswa</h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='prakerin'>
                    <select name='tahun' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Tahun Akademikaaaa -</option>";
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
                            echo "<option value=''>- Filter Kelasaaa -</option>";
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
                    <input type="submit" style='margin-top:-4px' class='btn btn-info btn-sm' value='Lihat'>
                  </form>
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php 
                  echo "<table id='example' class='table table-bordered table-striped'>
                    <thead>
                      <tr><th rowspan='2'>No</th>
                        <th>NISN</th>
                        <th width='170px'>Nama Siswa</th>
                        <th width='240px'><center>Nama Perusahaan</center></th>
                        
                        <th><center>Predikat</center></th>
						<th><center>Alamat Perusahaan</center></th>
                        <th><center>Action</center></th>
                      </tr>
                    </thead>
                    <tbody>";

                  if ($get_kelas != '' AND $get_tahun != ''){
                    $tampil = mysql_query("SELECT * FROM rb_siswa a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                              LEFT JOIN rb_jenis_kelamin c ON a.id_jenis_kelamin=c.id_jenis_kelamin 
                                                LEFT JOIN rb_jurusan d ON b.kode_jurusan=d.kode_jurusan 
                                                  where a.kode_kelas='$get_kelas' ORDER BY a.id_siswa");
                  }
                  if (isset($tampil)) {
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                      if ($get_edit != ''){
                          $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_prakerin where id_nilai_extrakulikuler='$get_edit'"));
                          $name = 'Update';
                      }else{
                          $name = 'Simpan';
                      }

                  if ($get_nisn==$r['nisn']){   
                    echo "<form action='index.php?view=prakerin&tahun=$get_tahun&kelas=$get_kelas' method='POST'>
                            <tr><td>$no</td>
                              <td>$r[nisn]</td>
                              <td style='font-size:12px' id='$r[nisn]'>$r[nama]</td>
                              <input type='hidden' name='nisn' value='$r[nisn]'>
                              <input type='hidden' name='id' value='$e[id_nilai_extrakulikuler]'>
                              <input type='hidden' name='status' value='$name'>
                              <td><input type='text' name='a' class='form-control' style='width:100%; color:blue' placeholder='Tuliskan Tempat Prakerin...' value='$e[kegiatan]'></td>
                              <td><center><input type='text' class='form-control'  name='b' value='$e[nilai]' style='width:50px; text-align:center; padding:0px; color:blue'></center></td>
                              <td><input type='text' name='c' class='form-control' style='width:100%; color:blue' placeholder='Tuliskan Deskripsi...' value='$e[deskripsi]'></td>
                              <td align=center><input type='submit' name='simpan' class='btn btn-xs btn-primary' style='width:65px' value='$name'></td>
                            </tr>
                          </form>";
                  }else{
                    echo "<form action='index.php?view=prakerin&tahun=$get_tahun&kelas=$get_kelas' method='POST'>
                            <tr><td>$no</td>
                              <td>$r[nisn]</td>
                              <td style='font-size:12px' id='$r[nisn]'>$r[nama]</td>
                              <input type='hidden' name='nisn' value='$r[nisn]'>
                              <input type='hidden' name='nisn' value='$r[nisn]'>
                              <td><input type='text' name='a' class='form-control' style='width:100%; color:blue' placeholder='Tuliskan Tempat Prakerin...'></td>
                              <td><center><input type='text' class='form-control'  name='b' style='width:50px; text-align:center; padding:0px; color:blue'></center></td>
                              <td><input type='text' name='c' class='form-control' style='width:100%; color:blue' placeholder='Tuliskan Alamat Prakerin...'></td>
                              <td align=center><input type='submit' name='simpan' class='btn btn-xs btn-primary' style='width:65px' value='$name'></td>
                            </tr>
                          </form>";
                  }

                            $pe = mysql_query("SELECT * FROM rb_nilai_prakerin where id_tahun_akademik='$get_tahun' AND nisn='$r[nisn]' AND kode_kelas='$get_kelas'");
                            while ($n = mysql_fetch_array($pe)){
                                echo "<tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>$n[kegiatan]</td>
                                        <td align=center>$n[nilai]</td>
                                        <td>$n[deskripsi]</td>
                                        <td align=center><a href='index.php?view=prakerin&tahun=".$get_tahun."&kelas=".$get_kelas."&edit=".$n['id_nilai_extrakulikuler']."&nisn=".$r['nisn']."#$r[nisn]' class='btn btn-xs btn-success'><span class='glyphicon glyphicon-edit'></span></a>
                                                        <a href='index.php?view=prakerin&tahun=".$get_tahun."&kelas=".$get_kelas."&delete=".$n['id_nilai_extrakulikuler']."&nisn=".$r['nisn']."' class='btn btn-xs btn-danger' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a></td>
                                      </tr>";
                            }
                      $no++;
                      }
                  }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <?php 
                    if ($get_kelas == '' AND $get_tahun == ''){
                        echo "<center style='padding:60px; color:red'>Silahkan Memilih Taaaaaaaaaaaaahun akademik dan Kelas Terlebih dahulu...</center>";
                    }
                ?>
              </div><!-- /.box -->
              
            </div>
<?php }  ?>