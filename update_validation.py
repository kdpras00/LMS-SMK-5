#!/usr/bin/env python3
import re

file_path = '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/bahan_tugas.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Find and replace the JavaScript validation function
old_validation = r"""function validateForm\(\) \{
\s+var kategori = document\.getElementsByName\('a'\)\[0\]\.value;
\s+var namaFile = document\.getElementsByName\('b'\)\[0\]\.value;
\s+var fileInput = document\.getElementsByName\('c'\)\[0\];
\s+var isRpp = document\.getElementsByName\('is_rpp'\)\[0\]\.checked;
\s+
\s+// Validasi kategori
\s+if \(kategori == '0' \|\| kategori == ''\) \{
\s+alert\('Silakan pilih kategori terlebih dahulu!'\);
\s+return false;
\s+\}
\s+
\s+// Validasi nama file
\s+if \(namaFile\.trim\(\) == ''\) \{
\s+alert\('Nama file tidak boleh kosong!'\);
\s+return false;
\s+\}
\s+
\s+// Validasi file wajib jika RPP dicentang
\s+if \(isRpp && fileInput\.files\.length == 0\) \{
\s+alert\('File wajib diupload jika Anda mencentang "Upload sebagai RPP"!'\);
\s+return false;
\s+\}
\s+
\s+return true;
\s+\}"""

new_validation = """function validateForm() {
                var kategori = document.getElementsByName('a')[0].value;
                var namaFile = document.getElementsByName('b')[0].value;
                var fileMateri = document.getElementsByName('c')[0];
                var fileRPP = document.getElementsByName('file_rpp')[0];
                
                // Validasi kategori
                if (kategori == '0' || kategori == '') {
                  alert('Silakan pilih kategori terlebih dahulu!');
                  return false;
                }
                
                // Validasi nama file
                if (namaFile.trim() == '') {
                  alert('Nama file tidak boleh kosong!');
                  return false;
                }
                
                // Validasi: minimal salah satu file harus diupload
                if (fileMateri.files.length == 0 && fileRPP.files.length == 0) {
                  alert('Silakan upload minimal salah satu file (Materi/Tugas atau RPP)!');
                  return false;
                }
                
                return true;
              }"""

# Replace
content = re.sub(old_validation, new_validation, content, flags=re.DOTALL)

# Save
with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("✅ JavaScript validation updated successfully!")
print("  - Removed checkbox validation")
print("  - Added validation for separate file uploads")
print("  - Requires at least one file (Materi or RPP)")
