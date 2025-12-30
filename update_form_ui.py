#!/usr/bin/env python3
import re

file_path = '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/bahan_tugas.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace the checkbox section with separate file upload fields
# Find the old checkbox section
old_ui = r"""<tr><th scope='row'>Upload sebagai RPP</th> <td>
\s+<label style='font-weight:normal'>
\s+<input type='checkbox' name='is_rpp' value='1'> 
\s+<span style='color:#d9534f; font-weight:bold'>Ya, upload ini adalah RPP</span>
\s+<br><small style='color:#666'><i>Centang jika file ini adalah RPP \(Rencana Pelaksanaan Pembelajaran\)\. RPP akan muncul di halaman Admin RPP\.</i></small>
\s+</label>
\s+</td></tr>"""

new_ui = """<tr><th scope='row'>File RPP (Optional)</th> <td>
                      <div style='position:relative;''>
                        <a class='btn btn-success' href='javascript:;'>
                          <i class='fa fa-file-pdf-o'></i> Cari File RPP...
                          <input type='file' class='files' name='file_rpp' onchange='$("#upload-rpp-info").html($(this).val());' accept='.pdf,.doc,.docx'>
                        </a> <span style='width:155px' class='label label-success' id='upload-rpp-info'></span>
                        <br><small style='color:#d9534f'><i><b>Upload file RPP jika ada. RPP akan muncul di halaman Admin RPP.</b></i></small>
                      </div>
                    </td></tr>"""

# Replace
content = re.sub(old_ui, new_ui, content, flags=re.DOTALL)

# Also update the "File" label to "File Materi/Tugas"
content = re.sub(
    r"<tr><th scope='row'>File</th>\s+<td>",
    "<tr><th scope='row'>File Materi/Tugas</th>             <td>",
    content
)

# Add helper text after file upload button
content = re.sub(
    r"(<span style='width:155px' class='label label-info' id='upload-file-info'></span>)\s+(</div>)",
    r"\1\n                                                                          <br><small style='color:#666'><i>Upload file materi atau tugas (PDF/Word) - Optional</i></small>\n                                                                        \2",
    content
)

# Save
with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("✅ Form UI updated successfully!")
print("  - Changed 'File' to 'File Materi/Tugas'")
print("  - Replaced checkbox with separate RPP file upload field")
print("  - Added helper text for both fields")
