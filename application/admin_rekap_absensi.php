<?php 
$get_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$get_guru = isset($_GET['guru']) ? $_GET['guru'] : '';
$get_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');

cek_session_admin();
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Rekap Absensi Guru</h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='adminrekapabsen'>
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
                    <select name='bulan' style='padding:4px'>
                        <?php 
                            $bulan_arr = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                            for($i=1; $i<=12; $i++){
                                $selected = ($get_bulan == sprintf("%02d", $i)) ? 'selected' : '';
                                echo "<option value='".sprintf("%02d", $i)."' $selected>$bulan_arr[$i]</option>";
                            }
                        ?>
                    </select>
                    <select name='guru' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Semua Guru -</option>";
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
                        <th style='width:20px'>No</th>
                        <th>Tanggal</th>
                        <th>Nama Guru</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Jam</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alpha</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    $where = "1=1";
                    if ($get_tahun != ''){
                        $where .= " AND a.id_tahun_akademik='$get_tahun'";
                    }
                    if ($get_guru != ''){
                        $where .= " AND c.nip='$get_guru'";
                    }
                    if ($get_bulan != ''){
                        $where .= " AND MONTH(abs.tanggal) = '$get_bulan'";
                    }
                    
                    // Query to get attendance records from guru
                    $tampil = mysql_query("SELECT DISTINCT abs.tanggal, c.nama_guru, d.nama_kelas, b.namamatapelajaran, a.jam_mulai, a.jam_selesai,
                                          (SELECT COUNT(*) FROM rb_absensi_siswa WHERE kodejdwl=a.kodejdwl AND tanggal=abs.tanggal AND kode_kehadiran='H') as hadir,
                                          (SELECT COUNT(*) FROM rb_absensi_siswa WHERE kodejdwl=a.kodejdwl AND tanggal=abs.tanggal AND kode_kehadiran='S') as sakit,
                                          (SELECT COUNT(*) FROM rb_absensi_siswa WHERE kodejdwl=a.kodejdwl AND tanggal=abs.tanggal AND kode_kehadiran='I') as izin,
                                          (SELECT COUNT(*) FROM rb_absensi_siswa WHERE kodejdwl=a.kodejdwl AND tanggal=abs.tanggal AND kode_kehadiran='A') as alpha
                                          FROM rb_absensi_siswa abs
                                          JOIN rb_jadwal_pelajaran a ON abs.kodejdwl=a.kodejdwl
                                          JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                          JOIN rb_guru c ON a.nip=c.nip
                                          JOIN rb_kelas d ON a.kode_kelas=d.kode_kelas
                                          WHERE $where
                                          ORDER BY abs.tanggal DESC");
                    
                    $no = 1;
                    if(mysql_num_rows($tampil) > 0){
                        while($r=mysql_fetch_array($tampil)){
                        $tgl = tgl_indo($r['tanggal']);
                        echo "<tr><td>$no</td>
                                  <td>$tgl</td>
                                  <td>$r[nama_guru]</td>
                                  <td>$r[nama_kelas]</td>
                                  <td>$r[namamatapelajaran]</td>
                                  <td>$r[jam_mulai] - $r[jam_selesai]</td>
                                  <td align='center'><span class='badge bg-green'>$r[hadir]</span></td>
                                  <td align='center'><span class='badge bg-yellow'>$r[sakit]</span></td>
                                  <td align='center'><span class='badge bg-blue'>$r[izin]</span></td>
                                  <td align='center'><span class='badge bg-red'>$r[alpha]</span></td>
                                  <td>Guru sudah input absensi</td>
                              </tr>";
                          $no++;
                          }
                    } else {
                        echo "<tr><td colspan='11' style='text-align:center; color:red'>Belum ada data absensi</td></tr>";
                    }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                </div>
            </div>
