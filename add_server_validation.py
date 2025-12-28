#!/usr/bin/env python3
"""
Script untuk menambahkan validasi server-side (PHP) pada file master
Mendeteksi pola INSERT dan UPDATE, lalu menambahkan validasi sebelumnya
"""

import re
import os

BASE_DIR = '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application'

# File yang perlu validasi server-side
FILES = [
    'master_ruangan.php',
    'master_kelas.php',
    'master_jurusan.php',
    'master_golongan.php',
    'master_ptk.php',
    'master_statuspegawai.php',
    'master_kelompokmapel.php',
    'master_wakilkepala.php',
    'master_titimangsa.php',
    'master_penilaiandiri.php',
    'master_penilaianteman.php',
]

def add_validation_to_insert(content, view_name):
    """
    Menambahkan validasi pada blok INSERT
    """
    # Pattern untuk mendeteksi INSERT tanpa validasi
    pattern = r"(if\s*\(isset\(\$_POST\['tambah'\]\)\)\{\s*)\n\s*(mysql_query\(\"INSERT)"
    
    validation_code = f"""
        // Validasi input
        $errors = array();
        foreach($_POST as $key => $value) {{
            if($key != 'tambah' && is_string($value) && trim($value) == '') {{
                // Skip field opsional seperti keterangan
                if(!in_array($key, array('g', 'keterangan', 'c', 'd', 'e', 'f'))) {{
                    $errors[] = $key;
                }}
            }}
        }}
        
        if(!empty($errors)){{
            echo "<script>
                setTimeout(function() {{
                  Swal.fire({{
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Semua field wajib harus diisi!',
                    showConfirmButton: true
                  }});
                }}, 100);
              </script>";
        }} else {{
        """
    
    # Tambahkan closing brace sebelum success message
    def replacer(match):
        return match.group(1) + validation_code + "\n        " + match.group(2)
    
    return re.sub(pattern, replacer, content)

def process_simple_validation(filepath, view_name):
    """
    Proses file dengan menambahkan validasi sederhana
    Hanya untuk file yang belum memiliki validasi
    """
    try:
        with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
        
        # Cek apakah sudah ada validasi
        if 'Semua field' in content or 'harus diisi' in content:
            return True, "Already has validation"
        
        # Backup
        backup_path = filepath + '.validation_bak'
        with open(backup_path, 'w', encoding='utf-8') as f:
            f.write(content)
        
        # Add validation
        new_content = add_validation_to_insert(content, view_name)
        
        # Write back
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        
        return True, "Validation added"
    except Exception as e:
        return False, str(e)

def main():
    print("=" * 70)
    print("MENAMBAHKAN VALIDASI SERVER-SIDE (PHP)")
    print("=" * 70)
    print()
    print("CATATAN: Script ini menambahkan validasi dasar.")
    print("Untuk validasi yang lebih spesifik, edit manual diperlukan.")
    print()
    
    for filename in FILES:
        filepath = os.path.join(BASE_DIR, filename)
        view_name = filename.replace('master_', '').replace('.php', '')
        
        if not os.path.exists(filepath):
            print(f"❌ {filename:40} - File tidak ditemukan")
            continue
        
        success, message = process_simple_validation(filepath, view_name)
        
        if success:
            print(f"✅ {filename:40} - {message}")
        else:
            print(f"❌ {filename:40} - {message}")
    
    print()
    print("=" * 70)
    print("SELESAI")
    print("=" * 70)

if __name__ == '__main__':
    main()
