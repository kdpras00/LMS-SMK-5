<?php
// AJAX handler untuk simpan nilai
require_once '../config/koneksi.php';

if (isset($_POST['id']) && isset($_POST['nilai'])) {
    $id = $_POST['id'];
    $nilai = $_POST['nilai'];
    
    // Cek apakah kolom nilai sudah ada
    $check = mysql_query("SHOW COLUMNS FROM rb_elearning_jawab LIKE 'nilai'");
    if (mysql_num_rows($check) == 0) {
        // Tambah kolom nilai jika belum ada
        mysql_query("ALTER TABLE rb_elearning_jawab ADD COLUMN nilai INT(3) DEFAULT NULL");
    }
    
    // Update nilai
    mysql_query("UPDATE rb_elearning_jawab SET nilai='$nilai' WHERE id_elearning_jawab='$id'");
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
