#!/usr/bin/env python3
"""
Script untuk menambahkan validasi required pada form input di file-file master
"""

import re
import os

# Daftar file yang akan diproses
files_to_process = [
    '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/master_tahun_akademik.php',
    '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/master_gedung.php',
    '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/master_ruangan.php',
    '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/master_kelas.php',
    '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/master_jurusan.php',
    '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/master_matapelajaran.php',
]

def add_required_to_inputs(content):
    """
    Menambahkan atribut required pada input text yang belum memilikinya
    """
    # Pattern untuk input text tanpa required
    pattern = r"(<input\s+type=['\"]text['\"][^>]*class=['\"]form-control['\"][^>]*name=['\"][a-zA-Z0-9_]+['\"])(?!\s*required)([^>]*>)"
    
    def replacer(match):
        before = match.group(1)
        after = match.group(2)
        # Jangan tambahkan required jika sudah ada
        if 'required' in before or 'required' in after:
            return match.group(0)
        return before + " required" + after
    
    content = re.sub(pattern, replacer, content)
    
    # Pattern untuk radio button tanpa required
    radio_pattern = r"(<input\s+type=['\"]radio['\"][^>]*name=['\"][a-zA-Z0-9_]+['\"])(?!\s*required)([^>]*>)"
    content = re.sub(radio_pattern, replacer, content)
    
    return content

def process_file(filepath):
    """
    Memproses satu file untuk menambahkan required
    """
    if not os.path.exists(filepath):
        print(f"File tidak ditemukan: {filepath}")
        return False
    
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Backup original
    backup_path = filepath + '.backup'
    with open(backup_path, 'w', encoding='utf-8') as f:
        f.write(content)
    
    # Add required attributes
    new_content = add_required_to_inputs(content)
    
    # Write back
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_content)
    
    print(f"✓ Processed: {os.path.basename(filepath)}")
    return True

if __name__ == '__main__':
    print("Menambahkan validasi required pada form input...")
    print("=" * 60)
    
    for filepath in files_to_process:
        process_file(filepath)
    
    print("=" * 60)
    print("Selesai!")
