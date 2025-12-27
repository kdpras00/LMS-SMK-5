<?php
// Guru Koreksi Tugas & Penilaian
$act = isset($_GET['act']) ? $_GET['act'] : '';
$get_tugas = isset($_GET['tugas']) ? $_GET['tugas'] : '';
$get_id = isset($_GET['id']) ? $_GET['id'] : '';

if ($act == '') {
    cek_session_guru();
    ?>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Koreksi Tugas</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label>Pilih Tugas:</label>
                    <select class='form-control' onchange="window.location='?view=guru_koreksi_tugas&tugas='+this.value">
                        <option value=''>- Pilih Tugas -</option>
                        <?php
                        $tugas = mysql_query("SELECT a.*, b.nama_kelas, c.namamatapelajaran 
                                             FROM rb_elearning a
                                             JOIN rb_jadwal_pelajaran e ON a.kodejdwl=e.kodejdwl
                                             JOIN rb_kelas b ON e.kode_kelas=b.kode_kelas
                                             JOIN rb_mata_pelajaran c ON e.kode_pelajaran=c.kode_pelajaran
                                             WHERE e.nip='$_SESSION[id]' 
                                             AND a.id_kategori_elearning='2'
                                             ORDER BY a.tanggal_tugas DESC");
                        while ($t = mysql_fetch_array($tugas)) {
                            $selected = ($get_tugas == $t['id_elearning']) ? 'selected' : '';
                            echo "<option value='$t[id_elearning]' $selected>$t[nama_file] - $t[nama_kelas] ($t[namamatapelajaran])</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <?php if ($get_tugas != '') { 
                    $tugas_info = mysql_fetch_array(mysql_query("SELECT a.*, b.nama_kelas, c.namamatapelajaran 
                                                                 FROM rb_elearning a
                                                                 JOIN rb_jadwal_pelajaran e ON a.kodejdwl=e.kodejdwl
                                                                 JOIN rb_kelas b ON e.kode_kelas=b.kode_kelas
                                                                 JOIN rb_mata_pelajaran c ON e.kode_pelajaran=c.kode_pelajaran
                                                                 WHERE a.id_elearning='$get_tugas'"));
                ?>
                <div class="alert alert-info">
                    <strong>Tugas:</strong> <?php echo $tugas_info['nama_file']; ?><br>
                    <strong>Kelas:</strong> <?php echo $tugas_info['nama_kelas']; ?><br>
                    <strong>Mata Pelajaran:</strong> <?php echo $tugas_info['namamatapelajaran']; ?><br>
                    <strong>Batas Waktu:</strong> <?php echo date('d/m/Y H:i', strtotime($tugas_info['tanggal_selesai'])); ?>
                </div>
                
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style='width:30px'>No</th>
                            <th>Nama Siswa</th>
                            <th>Tanggal Kirim</th>
                            <th>File Jawaban</th>
                            <th style='width:100px'>Nilai</th>
                            <th style='width:100px'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $jawaban = mysql_query("SELECT a.*, b.nama 
                                           FROM rb_elearning_jawab a
                                           JOIN rb_siswa b ON a.nisn=b.nisn
                                           WHERE a.id_elearning='$get_tugas'
                                           ORDER BY a.waktu DESC");
                    $no = 1;
                    while ($j = mysql_fetch_array($jawaban)) {
                        echo "<tr>
                                <td>$no</td>
                                <td>$j[nama]</td>
                                <td>" . date('d/m/Y H:i', strtotime($j['waktu'])) . "</td>
                                <td>";
                        if ($j['file_tugas'] != '') {
                            echo "<a href='files/$j[file_tugas]' target='_blank' class='btn btn-xs btn-success'>
                                    <i class='fa fa-download'></i> Download
                                  </a>";
                        } else {
                            echo "<span class='text-muted'>Tidak ada file</span>";
                        }
                        echo "</td>
                                <td>
                                    <input type='number' class='form-control input-sm' id='nilai_$j[id_elearning_jawab]' 
                                           value='" . (isset($j['nilai']) ? $j['nilai'] : '') . "' 
                                           min='0' max='100' placeholder='0-100'>
                                </td>
                                <td>
                                    <button class='btn btn-primary btn-xs' onclick='simpanNilai($j[id_elearning_jawab])'>
                                        <i class='fa fa-save'></i> Simpan
                                    </button>
                                </td>
                              </tr>";
                        $no++;
                    }
                    
                    if (mysql_num_rows($jawaban) == 0) {
                        echo "<tr><td colspan='6' class='text-center text-muted'>Belum ada siswa yang mengumpulkan tugas</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <script>
    function simpanNilai(id) {
        var nilai = document.getElementById('nilai_' + id).value;
        if (nilai === '') {
            Swal.fire('Error', 'Nilai tidak boleh kosong', 'error');
            return;
        }
        
        $.ajax({
            url: 'simpan_nilai.php',
            type: 'POST',
            data: {
                id: id,
                nilai: nilai
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Nilai berhasil disimpan',
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function() {
                Swal.fire('Error', 'Gagal menyimpan nilai', 'error');
            }
        });
    }
    </script>
<?php
}
?>
