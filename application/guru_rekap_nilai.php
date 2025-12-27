<?php
// Guru Rekap Nilai
$get_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$get_mapel = isset($_GET['mapel']) ? $_GET['mapel'] : '';

cek_session_guru();
?>
<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Rekap Nilai Siswa</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pilih Kelas:</label>
                        <select class='form-control' id='filter_kelas'>
                            <option value=''>- Pilih Kelas -</option>
                            <?php
                            $sem = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik WHERE aktif='Ya'"));
                            $kelas = mysql_query("SELECT DISTINCT b.kode_kelas, b.nama_kelas 
                                                 FROM rb_jadwal_pelajaran a
                                                 JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas
                                                 WHERE a.nip='$_SESSION[id]' 
                                                 AND a.id_tahun_akademik='$sem[id_tahun_akademik]'
                                                 ORDER BY b.nama_kelas");
                            while ($k = mysql_fetch_array($kelas)) {
                                $selected = ($get_kelas == $k['kode_kelas']) ? 'selected' : '';
                                echo "<option value='$k[kode_kelas]' $selected>$k[nama_kelas]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pilih Mata Pelajaran:</label>
                        <select class='form-control' id='filter_mapel'>
                            <option value=''>- Pilih Mata Pelajaran -</option>
                            <?php
                            $mapel = mysql_query("SELECT DISTINCT c.kode_pelajaran, c.namamatapelajaran 
                                                 FROM rb_jadwal_pelajaran a
                                                 JOIN rb_mata_pelajaran c ON a.kode_pelajaran=c.kode_pelajaran
                                                 WHERE a.nip='$_SESSION[id]' 
                                                 AND a.id_tahun_akademik='$sem[id_tahun_akademik]'
                                                 ORDER BY c.namamatapelajaran");
                            while ($m = mysql_fetch_array($mapel)) {
                                $selected = ($get_mapel == $m['kode_pelajaran']) ? 'selected' : '';
                                echo "<option value='$m[kode_pelajaran]' $selected>$m[namamatapelajaran]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <button class='btn btn-primary' onclick='tampilkanRekap()'>Tampilkan Rekap</button>
            
            <?php if ($get_kelas != '' && $get_mapel != '') { 
                // Get list tugas
                $tugas_list = mysql_query("SELECT a.id_elearning, a.nama_file 
                                          FROM rb_elearning a
                                          JOIN rb_jadwal_pelajaran b ON a.kodejdwl=b.kodejdwl
                                          WHERE b.kode_kelas='$get_kelas' 
                                          AND b.kode_pelajaran='$get_mapel'
                                          AND a.id_kategori_elearning='2'
                                          ORDER BY a.tanggal_tugas ASC");
                
                $tugas_arr = array();
                while ($tg = mysql_fetch_array($tugas_list)) {
                    $tugas_arr[] = $tg;
                }
                
                if (count($tugas_arr) > 0) {
            ?>
            <hr>
            <h4>Rekap Nilai</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style='width:30px'>No</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <?php
                            foreach ($tugas_arr as $idx => $tg) {
                                echo "<th>Tugas " . ($idx + 1) . "</th>";
                            }
                            ?>
                            <th>Rata-rata</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $siswa = mysql_query("SELECT * FROM rb_siswa WHERE kode_kelas='$get_kelas' ORDER BY nama ASC");
                    $no = 1;
                    while ($s = mysql_fetch_array($siswa)) {
                        echo "<tr>
                                <td>$no</td>
                                <td>$s[nisn]</td>
                                <td>$s[nama]</td>";
                        
                        $total_nilai = 0;
                        $jumlah_tugas = 0;
                        
                        foreach ($tugas_arr as $tg) {
                            $nilai_query = mysql_query("SELECT nilai FROM rb_elearning_jawab 
                                                       WHERE nisn='$s[nisn]' 
                                                       AND id_elearning='$tg[id_elearning]'");
                            $nilai_data = mysql_fetch_array($nilai_query);
                            $nilai = isset($nilai_data['nilai']) && $nilai_data['nilai'] != '' ? $nilai_data['nilai'] : '-';
                            
                            if ($nilai != '-') {
                                $total_nilai += $nilai;
                                $jumlah_tugas++;
                            }
                            
                            echo "<td class='text-center'>$nilai</td>";
                        }
                        
                        $rata_rata = ($jumlah_tugas > 0) ? round($total_nilai / $jumlah_tugas, 2) : 0;
                        $status = ($rata_rata >= 75) ? "<span class='badge bg-green'>Tuntas</span>" : "<span class='badge bg-red'>Tidak Tuntas</span>";
                        
                        echo "<td class='text-center'><strong>$rata_rata</strong></td>
                              <td class='text-center'>$status</td>
                              </tr>";
                        $no++;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php 
                } else {
                    echo "<div class='alert alert-warning'>Belum ada tugas untuk kelas dan mata pelajaran ini</div>";
                }
            } ?>
        </div>
    </div>
</div>

<script>
function tampilkanRekap() {
    var kelas = document.getElementById('filter_kelas').value;
    var mapel = document.getElementById('filter_mapel').value;
    
    if (kelas == '' || mapel == '') {
        Swal.fire('Perhatian', 'Pilih kelas dan mata pelajaran terlebih dahulu', 'warning');
        return;
    }
    
    window.location = '?view=guru_rekap_nilai&kelas=' + kelas + '&mapel=' + mapel;
}
</script>
