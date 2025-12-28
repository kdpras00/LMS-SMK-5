#!/usr/bin/env python3
"""
Script sederhana untuk memperbaiki kurung kurawal yang hilang
Menambahkan } setelah exit; pada blok validasi
"""

import re
import os
import subprocess

BASE_DIR = '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application'

FILES = [
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

def fix_file(filepath):
    """
    Memperbaiki file dengan menambahkan } setelah exit;
    pada blok validasi yang memiliki pola:
        exit;
    }
    """
    with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
        lines = f.readlines()
    
    # Backup
    with open(filepath + '.fix_bak', 'w', encoding='utf-8') as f:
        f.writelines(lines)
    
    # Cari dan perbaiki
    fixed_lines = []
    i = 0
    while i < len(lines):
        line = lines[i]
        fixed_lines.append(line)
        
        # Cek apakah ini baris exit; yang perlu diperbaiki
        if 'exit;' in line and i + 1 < len(lines):
            next_line = lines[i + 1]
            # Jika baris berikutnya adalah }, tambahkan } dengan indentasi lebih dalam
            if next_line.strip() == '}':
                # Tambahkan } dengan indentasi 8 spasi (2 level lebih dalam)
                fixed_lines.append('        }\n')
        
        i += 1
    
    # Tulis kembali
    with open(filepath, 'w', encoding='utf-8') as f:
        f.writelines(fixed_lines)
    
    return True

def main():
    print("=" * 70)
    print("MEMPERBAIKI SYNTAX ERROR")
    print("=" * 70)
    print()
    
    for filename in FILES:
        filepath = os.path.join(BASE_DIR, filename)
        
        if not os.path.exists(filepath):
            print(f"❌ {filename:40} - Not found")
            continue
        
        try:
            fix_file(filepath)
            
            # Verify dengan php -l
            result = subprocess.run(
                ['php', '-l', filepath],
                capture_output=True,
                text=True
            )
            
            if result.returncode == 0:
                print(f"✅ {filename:40} - Fixed & Verified")
            else:
                print(f"⚠️  {filename:40} - Fixed but still has errors")
                print(f"   {result.stderr.strip()}")
        except Exception as e:
            print(f"❌ {filename:40} - Error: {e}")
    
    print()
    print("=" * 70)
    print("SELESAI")
    print("=" * 70)

if __name__ == '__main__':
    main()
