#!/usr/bin/env python3
import re

file_path = '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/bahan_tugas.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Pattern to find and replace the entire upload section
# From line 252 ($dir_gambar) to line 305 (closing brace before echo)

pattern = r"""(\s+)\$dir_gambar = 'files/';
\s+\$filename = basename\(\$_FILES\['c'\]\['name'\]\);
\s+\$filenamee = date\("YmdHis"\)\.'-'\.basename\(\$_FILES\['c'\]\['name'\]\);
\s+\$uploadfile = \$dir_gambar \. \$filenamee;
\s+
\s+\$kategori = mysql_real_escape_string\(\$_POST\['a'\]\);
\s+\$nama_file = mysql_real_escape_string\(\$_POST\['b'\]\);
\s+\$waktu_mulai = mysql_real_escape_string\(\$_POST\['d'\]\);
\s+\$waktu_selesai = mysql_real_escape_string\(\$_POST\['e'\]\);
\s+\$keterangan = mysql_real_escape_string\(\$_POST\['f'\]\);
\s+\$is_rpp = isset\(\$_POST\['is_rpp'\]\) \? 1 : 0;.*?
\s+\}
\s+\}"""

replacement = r"""\1$dir_gambar = 'files/';
\1
\1$kategori = mysql_real_escape_string($_POST['a']);
\1$nama_file = mysql_real_escape_string($_POST['b']);
\1$waktu_mulai = mysql_real_escape_string($_POST['d']);
\1$waktu_selesai = mysql_real_escape_string($_POST['e']);
\1$keterangan = mysql_real_escape_string($_POST['f']);
\1
\1// Handle file Materi/Tugas and RPP separately
\1$file_materi = $_FILES['c'];
\1$file_rpp = $_FILES['file_rpp'];
\1
\1$uploaded_materi = false;
\1$uploaded_rpp = false;
\1$error_message = '';
\1
\1// Upload Materi/Tugas ke rb_elearning
\1if (isset($file_materi['name']) && $file_materi['name'] != '') {
\1    $filename_materi = date("YmdHis").'-'.basename($file_materi['name']);
\1    $uploadfile_materi = $dir_gambar . $filename_materi;
\1    
\1    if (move_uploaded_file($file_materi['tmp_name'], $uploadfile_materi)) {
\1        $query = "INSERT INTO rb_elearning VALUES (NULL,'$kategori','$get_jdwl','$nama_file','$filename_materi','$waktu_mulai','$waktu_selesai','$keterangan')";
\1        $result = mysql_query($query);
\1        if ($result) {
\1            $uploaded_materi = true;
\1        } else {
\1            $error_message .= "Gagal menyimpan Materi/Tugas ke database. ";
\1        }
\1    } else {
\1        $error_message .= "Gagal upload file Materi/Tugas. ";
\1    }
\1}
\1
\1// Upload RPP ke rb_elearning1
\1if (isset($file_rpp['name']) && $file_rpp['name'] != '') {
\1    $filename_rpp = date("YmdHis").'-RPP-'.basename($file_rpp['name']);
\1    $uploadfile_rpp = $dir_gambar . $filename_rpp;
\1    
\1    if (move_uploaded_file($file_rpp['tmp_name'], $uploadfile_rpp)) {
\1        $query = "INSERT INTO rb_elearning1 VALUES (NULL,'$kategori','$get_jdwl','$nama_file','$filename_rpp','$waktu_mulai','$waktu_selesai','$keterangan')";
\1        $result = mysql_query($query);
\1        if ($result) {
\1            $uploaded_rpp = true;
\1        } else {
\1            $error_message .= "Gagal menyimpan RPP ke database. ";
\1        }
\1    } else {
\1        $error_message .= "Gagal upload file RPP. ";
\1    }
\1}
\1
\1// Success/Error messages
\1if ($error_message != '') {
\1    echo "<script>window.alert('$error_message');
\1                window.location='index.php?view=bahantugas&act=tambah&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
\1} elseif ($uploaded_rpp && $uploaded_materi) {
\1    echo "<script>window.alert('Materi/Tugas dan RPP berhasil diupload! RPP akan muncul di halaman Admin RPP.');
\1                document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
\1} elseif ($uploaded_rpp) {
\1    echo "<script>window.alert('RPP berhasil diupload dan akan muncul di halaman Admin RPP!');
\1                document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
\1} elseif ($uploaded_materi) {
\1    echo "<script>window.alert('Materi/Tugas berhasil diupload!');
\1                document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
\1} else {
\1    echo "<script>window.alert('Tidak ada file yang diupload.');
\1                window.location='index.php?view=bahantugas&act=tambah&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
\1}
\1}"""

# Try to replace
new_content = re.sub(pattern, replacement, content, flags=re.DOTALL)

if new_content != content:
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(new_content)
    print("✅ Backend upload logic replaced successfully!")
else:
    print("❌ Pattern not found, trying alternative approach...")
    # Save for debugging
    with open('/tmp/debug_pattern.txt', 'w') as f:
        # Find the section manually
        start = content.find("$dir_gambar = 'files/';")
        if start > 0:
            end = content.find("}\n       }", start)
            if end > 0:
                f.write(f"Found section from {start} to {end}\n")
                f.write("="*50 + "\n")
                f.write(content[start:end+10])
            else:
                f.write("Could not find end marker")
        else:
            f.write("Could not find start marker")
    print("Debug info saved to /tmp/debug_pattern.txt")
