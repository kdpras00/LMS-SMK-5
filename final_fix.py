#!/usr/bin/env python3
"""
Final fix - use sed-style line replacement
"""

file_path = '/Applications/XAMPP/xamppfiles/htdocs/LMSsmkn5/application/bahan_tugas.php'

with open(file_path, 'r', encoding='utf-8') as f:
    lines = f.readlines()

# Read new logic
with open('/tmp/new_upload_logic.txt', 'r', encoding='utf-8') as f:
    new_logic = f.read()

# Replace lines 252-304 (0-indexed: 251-303)
new_lines = lines[:251] + [new_logic + "\n"] + lines[304:]

with open(file_path, 'w', encoding='utf-8') as f:
    f.writelines(new_lines)

print("✅ Replaced lines 252-304 with new upload logic")
