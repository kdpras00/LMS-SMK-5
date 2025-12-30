#!/usr/bin/env python3
"""
Complete rewrite of bahan_tugas.php upload logic
"""

file_path = '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/bahan_tugas.php'

with open(file_path, 'r', encoding='utf-8') as f:
    lines = f.readlines()

# Find the section to replace (approximately lines 251-310)
# We'll replace everything from "$dir_gambar = 'files/';" to the end of the upload logic

new_upload_logic = """          
          $dir_gambar = 'files/';
          
          $kategori = mysql_real_escape_string($_POST['a']);
          $nama_file = mysql_real_escape_string($_POST['b']);
          $waktu_mulai = mysql_real_escape_string($_POST['d']);
          $waktu_selesai = mysql_real_escape_string($_POST['e']);
          $keterangan = mysql_real_escape_string($_POST['f']);
          
          // Handle file Materi/Tugas and RPP separately
          $file_materi = $_FILES['c'];
          $file_rpp = $_FILES['file_rpp'];
          
          $uploaded_materi = false;
          $uploaded_rpp = false;
          $error_message = '';
          
          // Upload Materi/Tugas ke rb_elearning
          if (isset($file_materi['name']) && $file_materi['name'] != '') {
              $filename_materi = date("YmdHis").'-'.basename($file_materi['name']);
              $uploadfile_materi = $dir_gambar . $filename_materi;
              
              if (move_uploaded_file($file_materi['tmp_name'], $uploadfile_materi)) {
                  $query = "INSERT INTO rb_elearning VALUES (NULL,'$kategori','$get_jdwl','$nama_file','$filename_materi','$waktu_mulai','$waktu_selesai','$keterangan')";
                  $result = mysql_query($query);
                  if ($result) {
                      $uploaded_materi = true;
                  } else {
                      $error_message .= "Gagal menyimpan Materi/Tugas ke database. ";
                  }
              } else {
                  $error_message .= "Gagal upload file Materi/Tugas. ";
              }
          }
          
          // Upload RPP ke rb_elearning1
          if (isset($file_rpp['name']) && $file_rpp['name'] != '') {
              $filename_rpp = date("YmdHis").'-RPP-'.basename($file_rpp['name']);
              $uploadfile_rpp = $dir_gambar . $filename_rpp;
              
              if (move_uploaded_file($file_rpp['tmp_name'], $uploadfile_rpp)) {
                  $query = "INSERT INTO rb_elearning1 VALUES (NULL,'$kategori','$get_jdwl','$nama_file','$filename_rpp','$waktu_mulai','$waktu_selesai','$keterangan')";
                  $result = mysql_query($query);
                  if ($result) {
                      $uploaded_rpp = true;
                  } else {
                      $error_message .= "Gagal menyimpan RPP ke database. ";
                  }
              } else {
                  $error_message .= "Gagal upload file RPP. ";
              }
          }
          
          // Success/Error messages
          if ($error_message != '') {
              echo "<script>window.alert('$error_message');
                          window.location='index.php?view=bahantugas&act=tambah&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
          } elseif ($uploaded_rpp && $uploaded_materi) {
              echo "<script>window.alert('Materi/Tugas dan RPP berhasil diupload! RPP akan muncul di halaman Admin RPP.');
                          document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
          } elseif ($uploaded_rpp) {
              echo "<script>window.alert('RPP berhasil diupload dan akan muncul di halaman Admin RPP!');
                          document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
          } elseif ($uploaded_materi) {
              echo "<script>window.alert('Materi/Tugas berhasil diupload!');
                          document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
          } else {
              echo "<script>window.alert('Tidak ada file yang diupload.');
                          window.location='index.php?view=bahantugas&act=tambah&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
          }
"""

# Find start and end indices
start_idx = None
end_idx = None

for i, line in enumerate(lines):
    if "$dir_gambar = 'files/';" in line and start_idx is None:
        start_idx = i
    if start_idx is not None and "}       }" in line and i > start_idx + 50:
        end_idx = i
        break

if start_idx and end_idx:
    # Replace the section
    new_lines = lines[:start_idx] + [new_upload_logic + "\n"] + lines[end_idx+1:]
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.writelines(new_lines)
    
    print(f"✅ Replaced lines {start_idx+1} to {end_idx+1}")
    print(f"✅ Backend upload logic updated!")
else:
    print(f"❌ Could not find the section to replace")
    print(f"Start: {start_idx}, End: {end_idx}")
