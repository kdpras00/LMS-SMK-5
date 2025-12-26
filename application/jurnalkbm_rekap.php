<?php 
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

function nama_hari($date){
    $day = date('D', strtotime($date));
    $dayList = array(
        'Sun' => 'Minggu',
        'Mon' => 'Senin',
        'Tue' => 'Selasa',
        'Wed' => 'Rabu',
        'Thu' => 'Kamis',
        'Fri' => 'Jumat',
        'Sat' => 'Sabtu'
    );
    return $dayList[$day];
}

$hari_ini = nama_hari($tanggal);
?>
<div class="col-xs-12">  
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Jurnal Kegiatan Belajar Mengajar (KBM)</h3>
      <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
        <input type="hidden" name='view' value='jurnalkbmrekap'>
        <input type="date" name='tanggal' style='padding:4px' value='<?php echo $tanggal; ?>'>
        <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
        <a target='_blank' href='application/cetak_jurnalkbm.php?tanggal=<?php echo $tanggal; ?>' style='margin-top:-4px' class='btn btn-primary btn-sm'>Cetak Laporan</a>
      </form>
    </div><!-- /.box-header -->
    <div class="box-body">
      <div class="alert alert-info">
        Menampilkan data jadwal untuk hari <b><?php echo $hari_ini; ?></b>, Tanggal <b><?php echo tgl_indo($tanggal); ?></b>.
      </div>
      <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th style='width:20px'>No</th>
            <th>Mata Pelajaran</th>
            <th>Kelas</th>
            <th>Guru Pengampu</th>
            <th>Jam</th>
            <th>Materi</th>
            <th>Status</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
      <?php
        // Filter by Day Name derived from Date
        $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, c.nama_guru, 
                              j.materi, j.keterangan, j.id_journal
                              FROM rb_jadwal_pelajaran a 
                              JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                              JOIN rb_guru c ON a.nip=c.nip 
                              JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas
                              LEFT JOIN rb_journal_list j ON a.kodejdwl=j.kodejdwl AND j.tanggal='$tanggal'
                              WHERE a.hari='$hari_ini' 
                              ORDER BY a.jam_mulai ASC");
        
        $no = 1;
        while($r=mysql_fetch_array($tampil)){
            $status = ($r['id_journal'] != '') ? "<span class='label label-success'>Sudah Isi</span>" : "<span class='label label-danger'>Belum Isi</span>";
            $materi = ($r['materi'] != '') ? $r['materi'] : '-';
            $keterangan = ($r['keterangan'] != '') ? $r['keterangan'] : '-';
            
            echo "<tr><td>$no</td>
                      <td>$r[namamatapelajaran]</td>
                      <td>$r[nama_kelas]</td>
                      <td>$r[nama_guru]</td>
                      <td>$r[jam_mulai] - $r[jam_selesai]</td>
                      <td>$materi</td>
                      <td>$status</td>
                      <td>$keterangan</td>
                  </tr>";
            $no++;
        }
      ?>
        </tbody>
      </table>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</div>
