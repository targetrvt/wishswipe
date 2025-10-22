#!/bin/bash

# WishSwipe Icon Generator Script
# This script helps create app icons from your logo

echo "🎨 WishSwipe Icon Generator"
echo "=========================="
echo ""

# Check if ImageMagick is installed
if ! command -v convert &> /dev/null; then
    echo "❌ ImageMagick is not installed."
    echo ""
    echo "Please install it first:"
    echo "  Ubuntu/Debian: sudo apt-get install imagemagick"
    echo "  macOS:         brew install imagemagick"
    echo "  Fedora:        sudo dnf install imagemagick"
    echo ""
    echo "Alternative: Use online tool at https://icon.kitchen/"
    exit 1
fi

# Source logo file
LOGO="../public/images/wishswipe_logo_black.png"

if [ ! -f "$LOGO" ]; then
    echo "❌ Logo file not found at: $LOGO"
    echo "Please ensure the logo exists."
    exit 1
fi

echo "✅ Found logo: $LOGO"
echo ""

# Create build directory if it doesn't exist
mkdir -p build/icons

echo "📦 Generating Linux PNG icons..."
for size in 16 32 48 64 128 256 512 1024; do
    echo "  - Creating ${size}x${size}.png"
    convert "$LOGO" -resize ${size}x${size} -background none -gravity center -extent ${size}x${size} build/icons/${size}x${size}.png
done

echo "✅ Linux icons created in build/icons/"
echo ""

echo "📦 Generating Windows icon (icon.ico)..."
convert "$LOGO" -background none -define icon:auto-resize=256,128,64,48,32,16 build/icon.ico
echo "✅ Windows icon created: build/icon.ico"
echo ""

echo "📦 Generating macOS icon (icon.icns)..."
echo "⚠️  Note: Creating .icns requires additional tools on Linux."
echo "   Recommended: Use https://cloudconvert.com/png-to-icns"
echo "   Or install png2icns on macOS: brew install libicns"
echo ""

if command -v png2icns &> /dev/null; then
    # Create iconset for macOS
    ICONSET="build/icon.iconset"
    mkdir -p "$ICONSET"
    
    convert "$LOGO" -resize 16x16 "$ICONSET/icon_16x16.png"
    convert "$LOGO" -resize 32x32 "$ICONSET/icon_16x16@2x.png"
    convert "$LOGO" -resize 32x32 "$ICONSET/icon_32x32.png"
    convert "$LOGO" -resize 64x64 "$ICONSET/icon_32x32@2x.png"
    convert "$LOGO" -resize 128x128 "$ICONSET/icon_128x128.png"
    convert "$LOGO" -resize 256x256 "$ICONSET/icon_128x128@2x.png"
    convert "$LOGO" -resize 256x256 "$ICONSET/icon_256x256.png"
    convert "$LOGO" -resize 512x512 "$ICONSET/icon_256x256@2x.png"
    convert "$LOGO" -resize 512x512 "$ICONSET/icon_512x512.png"
    convert "$LOGO" -resize 1024x1024 "$ICONSET/icon_512x512@2x.png"
    
    png2icns build/icon.icns "$ICONSET"/*.png
    echo "✅ macOS icon created: build/icon.icns"
    rm -rf "$ICONSET"
else
    echo "⚠️  Skipping .icns creation (png2icns not found)"
    echo "   Upload build/icons/512x512.png to https://cloudconvert.com/png-to-icns"
    echo "   Then download and save as build/icon.icns"
fi

echo ""
echo "✅ Icon generation complete!"
echo ""
echo "📋 Next steps:"
echo "1. If icon.icns wasn't created, use https://cloudconvert.com/png-to-icns"
echo "2. Review the icons in the build/ directory"
echo "3. Run 'npm run build' to create your desktop app"
echo ""


