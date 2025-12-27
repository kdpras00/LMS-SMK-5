<?php 
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$get_guru = isset($_GET['guru']) ? $_GET['guru'] : '';
$get_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';

cek_session_admin();
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Jurnal Kegiatan Belajar Mengajar (KBM) - Semua Guru</h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='adminjurnal'>
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
                    <select name='kelas' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Semua Kelas -</option>";
                            $kelas = mysql_query("SELECT * FROM rb_kelas ORDER BY nama_kelas");
                            while ($k = mysql_fetch_array($kelas)){
                              if ($get_kelas==$k['kode_kelas']){
                                echo "<option value='$k[kode_kelas]' selected>$k[nama_kelas]</option>";
                              }else{
                                echo "<option value='$k[kode_kelas]'>$k[nama_kelas]</option>";
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
                        <th>Hari/Tanggal</th>
                        <th>Nama Guru</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Jam Ke</th>
                        <th>Ringkasan Materi</th>
                        <th>Action</th>
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
                    if ($get_kelas != ''){
                        $where .= " AND a.kode_kelas='$get_kelas'";
                    }
                    
                    $tampil = mysql_query("SELECT j.*, c.nama_guru, d.nama_kelas, b.namamatapelajaran
                                          FROM rb_journal_list j
                                          JOIN rb_jadwal_pelajaran a ON j.kodejdwl=a.kodejdwl
                                          JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                          JOIN rb_guru c ON a.nip=c.nip
                                          JOIN rb_kelas d ON a.kode_kelas=d.kode_kelas
                                          WHERE $where
                                          ORDER BY j.tanggal DESC, j.jam_ke ASC");
                    
                    $no = 1;
                    if(mysql_num_rows($tampil) > 0){
                        while($r=mysql_fetch_array($tampil)){
                        $tgl = tgl_indo($r['tanggal']);
                        echo "<tr><td>$no</td>
                                  <td>$r[hari], $tgl</td>
                                  <td>$r[nama_guru]</td>
                                  <td>$r[nama_kelas]</td>
                                  <td>$r[namamatapelajaran]</td>
                                  <td align='center'>$r[jam_ke]</td>
                                  <td>$r[materi]</td>
                                  <td><button class='btn btn-info btn-xs' onclick='lihatDetail(\"$r[keterangan]\")' title='Lihat Keterangan Lengkap'><i class='fa fa-eye'></i> Detail</button></td>
                              </tr>";
                          $no++;
                          }
                    } else {
                        echo "<tr><td colspan='8' style='text-align:center; color:red'>Belum ada jurnal KBM</td></tr>";
                    }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                </div>
            </div>

<script>
function lihatDetail(keterangan) {
    Swal.fire({
        title: 'Keterangan Lengkap',
        html: '<div style="text-align:left">' + keterangan + '</div>',
        icon: 'info',
        confirmButtonText: 'Tutup'
    });
}
</script>
