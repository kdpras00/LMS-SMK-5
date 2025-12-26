<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$get_id = isset($_GET['id']) ? $_GET['id'] : '';

if ($act==''){ ?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php if ($get_tahun != ''){ echo "Data Kompetensi Dasar"; }else{ echo "Data Kompetensi Dasar pada ".date('Y'); } ?></h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='kompetensiguru'>
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
                    <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
                  </form>

                </div><!-- /.box-header -->
                <div class="box-body">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Kode Pelajaran</th>
                        <th>Jadwal Pelajaran</th>
                        <th>Kelas</th>
                        <th>Guru</th>
                        <th>Hari</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Ruangan</th>
                        <th>Semester</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    $tampil = null;
                    if ($get_tahun != ''){
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$get_tahun' ORDER BY a.hari DESC");
                    
                    }else{
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.nip='$_SESSION[id]' AND a.id_tahun_akademik LIKE '".date('Y')."%' ORDER BY a.hari DESC");
                    }
                    if ($tampil) {
                        $no = 1;
                        while($r=mysql_fetch_array($tampil)){
                        echo "<tr><td>$no</td>
                                  <td>$r[kode_pelajaran]</td>
                                  <td>$r[namamatapelajaran]</td>
                                  <td>$r[nama_kelas]</td>
                                  <td>$r[nama_guru]</td>
                                  <td>$r[hari]</td>
                                  <td>$r[jam_mulai]</td>
                                  <td>$r[jam_selesai]</td>
                                  <td>$r[nama_ruangan]</td>
                                  <td>$r[id_tahun_akademik]</td>
                                  <td style='width:80px !important'><center>
                                            <a class='btn btn-success btn-xs' title='Lihat Kompetensi Dasar' href='index.php?view=kompetensiguru&act=lihat&id=$r[kodejdwl]'><span class='glyphicon glyphicon-search'></span> Lihat Indikator</a>
                                          </center></td>
                              </tr>";
                          $no++;
                          }
                    }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                </div>
            </div>

<?php 
}elseif($act=='lihat'){
    $d = mysql_fetch_array(mysql_query("SELECT a.kode_kelas, b.nama_kelas, c.namamatapelajaran, d.nama_guru FROM `rb_jadwal_pelajaran` a JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas JOIN rb_mata_pelajaran c ON a.kode_pelajaran=c.kode_pelajaran JOIN rb_guru d ON a.nip=d.nip where a.kodejdwl='$get_id'"));
            echo "<div class='col-xs-12'>  
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>Kompetensi Dasar</h3>
                </div>
                <div class='box-body'>
                  <div class='col-md-12'>
                  <table class='table table-condensed table-hover'>
                      <tbody>
                        <tr><th width='120px' scope='row'>Nama Kelas</th> <td>$d[nama_kelas]</td></tr>
                        <tr><th scope='row'>Nama Guru</th>           <td>$d[nama_guru]</td></tr>
                        <tr><th scope='row'>Mata Pelajaran</th>           <td>$d[namamatapelajaran]</td></tr>
                      </tbody>
                  </table>
                  </div>

                  <table id='example' class='table table-bordered table-striped'>
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Ranah</th>
                        <th>Indikator</th>
                      </tr>
                    </thead>
                    <tbody>";
                      $tampil = mysql_query("SELECT * FROM rb_kompetensi_dasar z JOIN rb_jadwal_pelajaran a ON z.kodejdwl=a.kodejdwl JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas JOIN rb_mata_pelajaran c ON a.kode_pelajaran=c.kode_pelajaran where a.kodejdwl='$get_id' ORDER BY z.id_kompetensi_dasar DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[ranah]</td>
                              <td>$r[kompetensi_dasar]</td>
                            </tr>";
                      $no++;
                      }
                    echo "<tbody>
                  </table>
                </div>
                </div>
            </div>";
}
?>