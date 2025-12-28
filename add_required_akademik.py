#!/usr/bin/env python3
"""
Script untuk menambahkan validasi required pada form Data Akademik
"""
import re

files_to_update = [
    '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/master_matapelajaran.php',
    '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/master_jadwalpelajaran.php',
    '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/master_kompetensidasar.php'
]

def add_required_to_inputs(content, file_name):
    """Add required attribute to important input fields"""
    
    # Patterns untuk field yang wajib diisi
    required_patterns = {
        # Mata Pelajaran
        r"(<th scope='row'>Kode Pelajaran</th>\s+<td><input type='text' class='form-control' name='a')": r"\1 required",
        r"(<th scope='row'>Nama Mapel</th>\s+<td><input type='text' class='form-control' name='f')": r"\1 required",
        
        # Jadwal Pelajaran  
        r"(<th scope='row'>Kode Jadwal</th>\s+<td><input type='text' class='form-control' name='a')": r"\1 required",
        
        # Kompetensi Dasar
        r"(<th scope='row'>Kode KD</th>\s+<td><input type='text' class='form-control' name='a')": r"\1 required",
        r"(<th scope='row'>Kompetensi Dasar</th>\s+<td><input type='text' class='form-control' name='b')": r"\1 required",
    }
    
    # Add red asterisk to labels
    label_patterns = {
        r"(<th[^>]*>Kode Pelajaran)(</th>)": r"\1 <span style='color:red'>*</span>\2",
        r"(<th[^>]*>Nama Mapel)(</th>)": r"\1 <span style='color:red'>*</span>\2",
        r"(<th[^>]*>Kode Jadwal)(</th>)": r"\1 <span style='color:red'>*</span>\2",
        r"(<th[^>]*>Kode KD)(</th>)": r"\1 <span style='color:red'>*</span>\2",
        r"(<th[^>]*>Kompetensi Dasar)(</th>)": r"\1 <span style='color:red'>*</span>\2",
    }
    
    # Apply required attributes
    for pattern, replacement in required_patterns.items():
        content = re.sub(pattern, replacement, content, flags=re.IGNORECASE)
    
    # Apply red asterisks
    for pattern, replacement in label_patterns.items():
        # Only add if not already present
        if "<span style='color:red'>*</span>" not in content or content.count(pattern) > content.count("<span style='color:red'>*</span>"):
            content = re.sub(pattern, replacement, content)
    
    return content

# Process each file
for file_path in files_to_update:
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        original_content = content
        content = add_required_to_inputs(content, file_path)
        
        if content != original_content:
            with open(file_path, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f"✅ Updated: {file_path.split('/')[-1]}")
        else:
            print(f"⏭️  No changes needed: {file_path.split('/')[-1]}")
            
    except Exception as e:
        print(f"❌ Error processing {file_path}: {e}")

print("\n✅ All files processed!")
