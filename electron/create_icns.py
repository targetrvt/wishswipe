#!/usr/bin/env python3
"""
Create macOS .icns file from iconset directory
This is a workaround for Linux/Windows systems without iconutil
"""

import os
import struct
from PIL import Image

ICONSET_DIR = "build/icon.iconset"
OUTPUT_ICNS = "build/icon.icns"

# ICNS format constants
ICNS_MAGIC = b'icns'

# Icon type codes and their corresponding files
ICON_TYPES = [
    ('ic07', 'icon_128x128.png'),      # 128x128
    ('ic08', 'icon_256x256.png'),      # 256x256
    ('ic09', 'icon_512x512.png'),      # 512x512
    ('ic10', 'icon_512x512@2x.png'),   # 1024x1024
    ('ic11', 'icon_16x16.png'),        # 16x16
    ('ic12', 'icon_32x32.png'),        # 32x32
    ('ic13', 'icon_128x128@2x.png'),   # 256x256 (retina)
    ('ic14', 'icon_256x256@2x.png'),   # 512x512 (retina)
]

print("🍎 Creating macOS .icns file...")

def create_icns():
    if not os.path.exists(ICONSET_DIR):
        print(f"❌ Iconset directory not found: {ICONSET_DIR}")
        return False
    
    # Collect icon data
    icon_data = []
    total_size = 8  # ICNS header size
    
    for icon_type, filename in ICON_TYPES:
        filepath = os.path.join(ICONSET_DIR, filename)
        if not os.path.exists(filepath):
            print(f"⚠️  Skipping {filename} (not found)")
            continue
        
        # Read PNG data
        with open(filepath, 'rb') as f:
            png_data = f.read()
        
        # ICNS icon entry: 4-byte type + 4-byte size + data
        entry_size = 8 + len(png_data)
        icon_data.append((icon_type.encode('ascii'), png_data))
        total_size += entry_size
        print(f"  ✅ Added {filename} ({len(png_data)} bytes)")
    
    # Write ICNS file
    with open(OUTPUT_ICNS, 'wb') as f:
        # Write ICNS header
        f.write(ICNS_MAGIC)
        f.write(struct.pack('>I', total_size))
        
        # Write icon entries
        for icon_type, png_data in icon_data:
            entry_size = 8 + len(png_data)
            f.write(icon_type)
            f.write(struct.pack('>I', entry_size))
            f.write(png_data)
    
    print(f"\n✅ macOS icon created: {OUTPUT_ICNS}")
    print(f"   Size: {os.path.getsize(OUTPUT_ICNS)} bytes")
    
    # Clean up iconset directory
    import shutil
    try:
        shutil.rmtree(ICONSET_DIR)
        print(f"✅ Cleaned up iconset directory")
    except Exception as e:
        print(f"⚠️  Could not remove iconset directory: {e}")
    
    return True

if __name__ == "__main__":
    try:
        if create_icns():
            print("\n🎉 All icons ready for building!")
        else:
            print("\n❌ Failed to create .icns file")
            print("   You can use: https://cloudconvert.com/png-to-icns")
    except Exception as e:
        print(f"\n❌ Error: {e}")
        print("   Alternative: Upload build/icons/512x512.png to:")
        print("   https://cloudconvert.com/png-to-icns")


