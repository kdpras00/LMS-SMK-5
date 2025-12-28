#!/usr/bin/env python3
"""
Script untuk memperbaiki syntax error pada semua file master
Menambahkan kurung kurawal penutup yang hilang
"""

import re
import os

BASE_DIR = '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application'

# File yang perlu diperbaiki berdasarkan hasil php -l
FILES_TO_FIX = [
    'master_golongan.php',
    'master_identitas.php',
    'master_jadwalpelajaran.php',
    'master_kelompokmapel.php',
    'master_kompetensidasar.php',
    'master_matapelajaran.php',
    'master_penilaiandiri.php',
    'master_penilaianteman.php',
    'master_predikat.php',
    'master_ptk.php',
    'master_ruangan.php',
    'master_statuspegawai.php',
    'master_titimangsa.php',
]

def fix_missing_braces(content):
    """
    Memperbaiki kurung kurawal yang hilang setelah blok validasi
    """
    
    # Pattern 1: Fix UPDATE block - tambahkan } sebelum }
    # Cari pattern: exit;\n    }
    # Ganti dengan: exit;\n        }\n    }
    pattern1 = r"(exit;)\n(\s{4}\})\n(\s{4}\$edit = mysql_query)"
    replacement1 = r"\1\n        }\n\2\n\3"
    content = re.sub(pattern1, replacement1, content)
    
    # Pattern 2: Fix INSERT block - tambahkan } sebelum }
    # Cari pattern: exit;\n    }\n\n    echo
    # Ganti dengan: exit;\n        }\n    }\n\n    echo
    pattern2 = r"(exit;)\n(\s{4}\})\n\n(\s{4}echo \"<div)"
    replacement2 = r"\1\n        }\n\2\n\n\3"
    content = re.sub(pattern2, replacement2, content)
    
    return content

def process_file(filepath):
    """Process single file"""
    try:
        with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
        
        # Backup
        backup_path = filepath + '.syntax_fix_bak'
        with open(backup_path, 'w', encoding='utf-8') as f:
            f.write(content)
        
        # Fix braces
        new_content = fix_missing_braces(content)
        
        # Write back
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        
        return True, "Fixed"
    except Exception as e:
        return False, str(e)

def main():
    print("=" * 70)
    print("MEMPERBAIKI SYNTAX ERROR - KURUNG KURAWAL HILANG")
    print("=" * 70)
    print()
    
    success_count = 0
    failed_count = 0
    
    for filename in FILES_TO_FIX:
        filepath = os.path.join(BASE_DIR, filename)
        
        if not os.path.exists(filepath):
            print(f"❌ {filename:40} - File tidak ditemukan")
            failed_count += 1
            continue
        
        success, message = process_file(filepath)
        
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
    print()
    print("Verifikasi dengan: for file in application/master_*.php; do php -l \"$file\"; done")

if __name__ == '__main__':
    main()
