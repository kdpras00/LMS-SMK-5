#!/usr/bin/env python3
"""
Script untuk menambahkan validasi required pada semua file master secara batch
"""

import re
import os
from pathlib import Path

# Base directory
BASE_DIR = '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application'

# Daftar file yang akan diproses
FILES_TO_PROCESS = [
    'master_gedung.php',
    'master_ruangan.php',
    'master_kelas.php',
    'master_jurusan.php',
    'master_matapelajaran.php',
    'master_kompetensidasar.php',
    'master_guru.php',
    'master_siswa.php',
    'master_admin.php',
    'master_jadwalpelajaran.php',
    'master_identitas.php',
    'master_golongan.php',
    'master_ptk.php',
    'master_statuspegawai.php',
    'master_kelompokmapel.php',
    'master_predikat.php',
    'master_wakilkepala.php',
    'master_titimangsa.php',
    'master_penilaiandiri.php',
    'master_penilaianteman.php',
]

def add_required_to_text_inputs(content):
    """Tambahkan required pada input type='text'"""
    # Pattern: <input type='text' class='form-control' name='...' (tanpa required)
    pattern = r"(<input\s+type=['\"]text['\"][^>]*class=['\"]form-control['\"][^>]*name=['\"][a-zA-Z0-9_\[\]]+['\"])(?![^>]*required)([^>]*>)"
    
    def replacer(match):
        before = match.group(1)
        after = match.group(2)
        # Skip jika sudah ada required atau readonly
        if 'required' in (before + after) or 'readonly' in (before + after):
            return match.group(0)
        # Skip jika field keterangan atau optional
        if "name='g'" in before or "name='keterangan'" in before:
            return match.group(0)
        return before + " required" + after
    
    return re.sub(pattern, replacer, content, flags=re.IGNORECASE)

def add_required_to_radio_buttons(content):
    """Tambahkan required pada radio buttons"""
    # Pattern untuk radio button pertama dalam grup
    pattern = r"(<input\s+type=['\"]radio['\"][^>]*name=['\"]([a-zA-Z0-9_\[\]]+)['\"])(?![^>]*required)([^>]*>)"
    
    def replacer(match):
        before = match.group(1)
        after = match.group(3)
        if 'required' in (before + after):
            return match.group(0)
        return before + " required" + after
    
    return re.sub(pattern, replacer, content, flags=re.IGNORECASE)

def add_required_to_selects(content):
    """Tambahkan required pada select elements"""
    pattern = r"(<select[^>]*class=['\"]form-control['\"][^>]*name=['\"][a-zA-Z0-9_\[\]]+['\"])(?![^>]*required)([^>]*>)"
    
    def replacer(match):
        before = match.group(1)
        after = match.group(2)
        if 'required' in (before + after):
            return match.group(0)
        return before + " required" + after
    
    return re.sub(pattern, replacer, content, flags=re.IGNORECASE)

def process_file(filepath):
    """Process single file"""
    try:
        with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
        
        # Backup
        backup_path = filepath + '.bak'
        with open(backup_path, 'w', encoding='utf-8') as f:
            f.write(content)
        
        # Apply transformations
        new_content = content
        new_content = add_required_to_text_inputs(new_content)
        new_content = add_required_to_radio_buttons(new_content)
        new_content = add_required_to_selects(new_content)
        
        # Write back
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        
        return True, "Success"
    except Exception as e:
        return False, str(e)

def main():
    print("=" * 70)
    print("MENAMBAHKAN VALIDASI REQUIRED PADA FILE MASTER")
    print("=" * 70)
    print()
    
    success_count = 0
    failed_count = 0
    
    for filename in FILES_TO_PROCESS:
        filepath = os.path.join(BASE_DIR, filename)
        
        if not os.path.exists(filepath):
            print(f"❌ {filename:40} - File tidak ditemukan")
            failed_count += 1
            continue
        
        success, message = process_file(filepath)
        
        if success:
            print(f"✅ {filename:40} - Berhasil")
            success_count += 1
        else:
            print(f"❌ {filename:40} - {message}")
            failed_count += 1
    
    print()
    print("=" * 70)
    print(f"SELESAI: {success_count} berhasil, {failed_count} gagal")
    print("=" * 70)
    print()
    print("CATATAN:")
    print("- File backup disimpan dengan ekstensi .bak")
    print("- Validasi server-side (PHP) perlu ditambahkan manual")
    print("- Review hasil sebelum commit ke production")

if __name__ == '__main__':
    main()
