# App Icon Instructions

To build the desktop app, you need to provide app icons in the following formats:

## Required Icons

### Windows
- **icon.ico** - Place in `/electron/build/icon.ico`
  - Should be a .ico file with multiple sizes: 16x16, 32x32, 48x48, 64x64, 128x128, 256x256
  
### macOS
- **icon.icns** - Place in `/electron/build/icon.icns`
  - macOS icon file with multiple sizes

### Linux
- **icons/** - Create directory `/electron/build/icons/` with PNG files:
  - 16x16.png
  - 32x32.png
  - 48x48.png
  - 64x64.png
  - 128x128.png
  - 256x256.png
  - 512x512.png
  - 1024x1024.png

## Quick Solution: Use Your Existing Logo

You have `wishswipe_logo_black.png` in `/public/images/`. Here's how to create icons from it:

### Option 1: Use Online Tools (Easiest)
1. Go to https://www.electron.build/icons or https://icon.kitchen/
2. Upload your `wishswipe_logo_black.png`
3. Download the generated icons package
4. Extract to `/electron/build/`

### Option 2: Use electron-icon-builder (Command Line)
```bash
# Install icon builder
npm install -g electron-icon-builder

# Generate all icons from your logo
electron-icon-builder --input=/path/to/wishswipe_logo_black.png --output=/home/df/wishswipe/electron/build
```

### Option 3: Manual with ImageMagick
```bash
# Install ImageMagick
sudo apt-get install imagemagick  # Linux
brew install imagemagick          # macOS

# Create icon.ico for Windows
convert wishswipe_logo_black.png -define icon:auto-resize=256,128,64,48,32,16 icon.ico

# Create icns for macOS (requires additional tools)
# Use https://cloudconvert.com/png-to-icns

# Create PNG icons for Linux
for size in 16 32 48 64 128 256 512 1024; do
  convert wishswipe_logo_black.png -resize ${size}x${size} icons/${size}x${size}.png
done
```

## Temporary Workaround (For Testing Only)

If you want to test building WITHOUT icons, you can temporarily comment out the icon paths in `package.json`:

```json
// Comment these lines in electron/package.json:
// "icon": "build/icon.icns",
// "icon": "build/icon.ico",
// "icon": "build/icons",
```

The app will build with default Electron icons, but you should add proper icons before distribution.


