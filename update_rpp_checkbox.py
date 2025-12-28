#!/usr/bin/env python3
import re

# Read the file
with open('/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/bahan_tugas.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Change 1: Add RPP alert after successful upload (with file)
content = re.sub(
    r"(if \(\$result\)\{\r?\n\s+echo \"<script>document\.location='index\.php\?view=bahantugas&act=listbahantugas&jdwl=\"\.\$get_jdwl\.\"&id=\"\.\$get_id\.\"&kd=\"\.\$get_kd\.\"';</script>\";)",
    r"""if ($result){
                if ($is_rpp == 1) {
                  echo "<script>window.alert('RPP berhasil diupload dan akan muncul di halaman Admin RPP!');
                              document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
                } else {
                  echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
                }""",
    content,
    count=1
)

# Change 2: Update non-file upload section
content = re.sub(
    r"(\}else\{\r?\n\s+\$query = \"INSERT INTO rb_elearning VALUES \(NULL,'\$kategori','\$get_jdwl','\$nama_file','','\$waktu_mulai','\$waktu_selesai','\$keterangan'\)\";)",
    r"""          }else{
            // If marked as RPP, save to rb_elearning1, otherwise to rb_elearning
            if ($is_rpp == 1) {
              $query = "INSERT INTO rb_elearning1 VALUES (NULL,'$kategori','$get_jdwl','$nama_file','','$waktu_mulai','$waktu_selesai','$keterangan')";
            } else {
              $query = "INSERT INTO rb_elearning VALUES (NULL,'$kategori','$get_jdwl','$nama_file','','$waktu_mulai','$waktu_selesai','$keterangan')";
            }""",
    content,
    count=1
)

# Change 3: Add RPP alert for non-file upload
pattern = r"(\$result = mysql_query\(\$query\);\r?\n\s+\r?\n\s+if \(\$result\)\{\r?\n\s+echo \"<script>document\.location='index\.php\?view=bahantugas&act=listbahantugas&jdwl=\"\.\$get_jdwl\.\"&id=\"\.\$get_id\.\"&kd=\"\.\$get_kd\.\"';</script>\";)"
replacement = r"""$result = mysql_query($query);
            
            if ($result){
              if ($is_rpp == 1) {
                echo "<script>window.alert('RPP berhasil diupload dan akan muncul di halaman Admin RPP!');
                            document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
              } else {
                echo "<script>document.location='index.php?view=bahantugas&act=listbahantugas&jdwl=".$get_jdwl."&id=".$get_id."&kd=".$get_kd."';</script>";
              }"""

# Find the second occurrence (non-file upload)
matches = list(re.finditer(pattern, content))
if len(matches) >= 2:
    # Replace the second occurrence
    start = matches[1].start()
    end = matches[1].end()
    content = content[:start] + replacement + content[end:]

# Change 4: Add checkbox to form
content = re.sub(
    r"(<tr><th scope='row'>Keterangan</th>\s+<td><input type='text' class='form-control' name='f'></td></tr>\r?\n\s+\r?\n\s+</tbody>)",
    r"""<tr><th scope='row'>Keterangan</th>       <td><input type='text' class='form-control' name='f'></td></tr>
                    <tr><th scope='row'>Upload sebagai RPP</th> <td>
                      <label style='font-weight:normal'>
                        <input type='checkbox' name='is_rpp' value='1'> 
                        <span style='color:#d9534f; font-weight:bold'>Ya, upload ini adalah RPP</span>
                        <br><small style='color:#666'><i>Centang jika file ini adalah RPP (Rencana Pelaksanaan Pembelajaran). RPP akan muncul di halaman Admin RPP.</i></small>
                      </label>
                    </td></tr>
                    
                  </tbody>""",
    content
)

# Write back
with open('/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/bahan_tugas.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("File updated successfully!")
