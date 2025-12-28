#!/usr/bin/env python3
"""
Script untuk file-file besar: guru, siswa, admin, mata pelajaran, dll
"""

import re
import os

BASE_DIR = '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application'

FILES = [
    ('master_matapelajaran.php', 'matapelajaran'),
    ('master_kompetensidasar.php', 'kompetensidasar'),
    ('master_guru.php', 'guru'),
    ('master_siswa.php', 'siswa'),
    ('master_admin.php', 'admin'),
    ('master_jadwalpelajaran.php', 'jadwalpelajaran'),
    ('master_identitas.php', 'identitas'),
    ('master_predikat.php', 'predikat'),
]

def add_server_validation(content, view_name):
    """
    Menambahkan validasi server-side pada INSERT dan UPDATE
    """
    
    # Pattern untuk INSERT
    insert_pattern = r"(if\s*\(isset\(\$_POST\['tambah'\]\)\)\{\s*\n\s*)(mysql_query\(\"INSERT)"
    
    insert_validation = """// Validasi input
        $has_error = false;
        foreach($_POST as $key => $value) {
            if($key != 'tambah' && is_string($value) && trim($value) == '') {
                // Skip field opsional
                if(!in_array($key, array('f', 'g', 'keterangan', 'alamat', 'foto'))) {
                    $has_error = true;
                    break;
                }
            }
        }
        
        if($has_error){
            echo "<script>
                setTimeout(function() {
                  Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Semua field wajib harus diisi!',
                    showConfirmButton: true
                  });
                }, 100);\n              </script>";
        } else {
        """
    
    # Tambahkan validasi sebelum INSERT
    content = re.sub(insert_pattern, r'\1' + insert_validation + '\n        ' + r'\2', content)
    
    # Tambahkan closing brace setelah success message untuk INSERT
    success_pattern = r"(window\.location = 'index\.php\?view=" + view_name + r"';\s*\}\);\s*\}\);?\s*\}, 100\);\s*</script>\";)\s*\n\s*\}"
    content = re.sub(success_pattern, r'\1\n        }\n    }', content)
    
    # Pattern untuk UPDATE
    update_pattern = r"(if\s*\(isset\(\$_POST\['update'\]\)\)\{\s*\n\s*)(mysql_query\(\"UPDATE)"
    
    update_validation = """// Validasi input
        $has_error = false;
        foreach($_POST as $key => $value) {
            if($key != 'update' && $key != 'id' && is_string($value) && trim($value) == '') {
                // Skip field opsional
                if(!in_array($key, array('f', 'g', 'keterangan', 'alamat', 'foto'))) {
                    $has_error = true;
                    break;
                }
            }
        }
        
        if($has_error){
            echo "<script>
                setTimeout(function() {
                  Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Semua field wajib harus diisi!',
                    showConfirmButton: true
                  });
                }, 100);\n              </script>";
        } else {
        """
    
    # Tambahkan validasi sebelum UPDATE
    content = re.sub(update_pattern, r'\1' + update_validation + '\n        ' + r'\2', content)
    
    return content

def process_file(filepath, view_name):
    """Process single file"""
    try:
        with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
        
        # Cek apakah sudah ada validasi
        if 'Semua field wajib harus diisi!' in content:
            return True, "Already has validation"
        
        # Backup
        backup_path = filepath + '.server_bak'
        with open(backup_path, 'w', encoding='utf-8') as f:
            f.write(content)
        
        # Add validation
        new_content = add_server_validation(content, view_name)
        
        # Write back
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        
        return True, "Validation added"
    except Exception as e:
        return False, str(e)

def main():
    print("=" * 70)
    print("MENAMBAHKAN VALIDASI SERVER-SIDE - FILE BESAR")
    print("=" * 70)
    print()
    
    success_count = 0
    failed_count = 0
    
    for filename, view_name in FILES:
        filepath = os.path.join(BASE_DIR, filename)
        
        if not os.path.exists(filepath):
            print(f"❌ {filename:40} - File tidak ditemukan")
            failed_count += 1
            continue
        
        success, message = process_file(filepath, view_name)
        
        if success:
            print(f"✅ {filename:40} - {message}")
            success_count += 1
        else:
            print(f"❌ {filename:40} - {message}")
            failed_count += 1
    
    print()
    print("=" * 70)
    print(f"SELESAI: {success_count} berhasil, {failed_count} gagal")
    print("=" * 70)

if __name__ == '__main__':
    main()
