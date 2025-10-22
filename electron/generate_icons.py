#!/usr/bin/env python3
"""
WishSwipe Icon Generator
Generates all required icons for the desktop app from the logo
"""

import os
from PIL import Image

# Source logo (512x512 - perfect for icon generation)
LOGO_PATH = "../public/images/wishSwipe_logo.png"
BUILD_DIR = "build"
ICONS_DIR = os.path.join(BUILD_DIR, "icons")

# Ensure directories exist
os.makedirs(ICONS_DIR, exist_ok=True)

print("🎨 WishSwipe Icon Generator")
print("=" * 50)
print(f"Source logo: {LOGO_PATH}")
print()

# Open the source image
try:
    logo = Image.open(LOGO_PATH)
    print(f"✅ Loaded logo: {logo.size[0]}x{logo.size[1]}")
except Exception as e:
    print(f"❌ Error loading logo: {e}")
    exit(1)

# Linux PNG icons
print("\n📦 Generating Linux PNG icons...")
sizes = [16, 32, 48, 64, 128, 256, 512, 1024]
for size in sizes:
    output_path = os.path.join(ICONS_DIR, f"{size}x{size}.png")
    resized = logo.resize((size, size), Image.Resampling.LANCZOS)
    resized.save(output_path, "PNG")
    print(f"  ✅ Created {size}x{size}.png")

print(f"\n✅ Linux icons saved to: {ICONS_DIR}/")

# Windows .ico file (multi-resolution)
print("\n📦 Generating Windows icon (icon.ico)...")
ico_path = os.path.join(BUILD_DIR, "icon.ico")
ico_sizes = [(16, 16), (32, 32), (48, 48), (64, 64), (128, 128), (256, 256)]
ico_images = []
for size in ico_sizes:
    resized = logo.resize(size, Image.Resampling.LANCZOS)
    ico_images.append(resized)

# Save as ICO
ico_images[0].save(
    ico_path, 
    format='ICO', 
    sizes=ico_sizes,
    append_images=ico_images[1:]
)
print(f"✅ Windows icon saved to: {ico_path}")

# macOS .icns - Create iconset directory structure
print("\n📦 Generating macOS iconset...")
iconset_dir = os.path.join(BUILD_DIR, "icon.iconset")
os.makedirs(iconset_dir, exist_ok=True)

# macOS iconset requires specific naming
mac_sizes = [
    (16, "icon_16x16.png"),
    (32, "icon_16x16@2x.png"),
    (32, "icon_32x32.png"),
    (64, "icon_32x32@2x.png"),
    (128, "icon_128x128.png"),
    (256, "icon_128x128@2x.png"),
    (256, "icon_256x256.png"),
    (512, "icon_256x256@2x.png"),
    (512, "icon_512x512.png"),
    (1024, "icon_512x512@2x.png"),
]

for size, filename in mac_sizes:
    output_path = os.path.join(iconset_dir, filename)
    resized = logo.resize((size, size), Image.Resampling.LANCZOS)
    resized.save(output_path, "PNG")
    print(f"  ✅ Created {filename}")

print(f"✅ macOS iconset created: {iconset_dir}")

# Try to convert iconset to icns using iconutil (macOS only)
import platform
import subprocess

if platform.system() == "Darwin":
    print("\n📦 Converting iconset to .icns (macOS)...")
    icns_path = os.path.join(BUILD_DIR, "icon.icns")
    try:
        subprocess.run(
            ["iconutil", "-c", "icns", iconset_dir, "-o", icns_path],
            check=True
        )
        print(f"✅ macOS icon saved to: {icns_path}")
        # Clean up iconset directory
        import shutil
        shutil.rmtree(iconset_dir)
        print("✅ Cleaned up iconset directory")
    except Exception as e:
        print(f"⚠️  Could not create .icns file: {e}")
        print(f"   Iconset directory kept at: {iconset_dir}")
        print("   Convert manually with: iconutil -c icns build/icon.iconset")
else:
    print(f"\n⚠️  Not on macOS - .icns conversion skipped")
    print(f"   Iconset directory created at: {iconset_dir}")
    print("   To create .icns:")
    print("   1. On Mac: iconutil -c icns build/icon.iconset -o build/icon.icns")
    print("   2. Or use: https://cloudconvert.com/png-to-icns")
    print(f"      Upload: {os.path.join(ICONS_DIR, '512x512.png')}")

print("\n" + "=" * 50)
print("✅ Icon generation complete!")
print("\nGenerated files:")
print(f"  • Windows: {ico_path}")
print(f"  • Linux:   {ICONS_DIR}/*.png")
if platform.system() == "Darwin":
    print(f"  • macOS:   {os.path.join(BUILD_DIR, 'icon.icns')}")
else:
    print(f"  • macOS:   {iconset_dir}/ (needs conversion)")
print("\nNext steps:")
print("  1. Check the icons in the build/ directory")
print("  2. Run 'npm run build' to create your desktop app")
print("=" * 50)


