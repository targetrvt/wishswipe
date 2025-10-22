# WishSwipe Desktop - Files Overview

This document explains all the files in the electron directory.

## 📁 Directory Structure

```
electron/
├── main.js                    # Main Electron process (app entry point)
├── preload.js                 # Security bridge between Electron and web content
├── package.json               # Electron dependencies and build configuration
├── .gitignore                 # Git ignore rules for build artifacts
│
├── build/                     # Build resources
│   ├── .gitkeep              # Keeps directory in git
│   ├── icon.ico              # (YOU ADD) Windows app icon
│   ├── icon.icns             # (YOU ADD) macOS app icon
│   ├── icons/                # (YOU ADD) Linux app icons (PNG files)
│   ├── icon-instructions.md  # How to create icons
│   ├── license.txt           # License text for installers
│   └── entitlements.mac.plist # macOS security permissions
│
├── dist/                      # (GENERATED) Build output - installers go here
├── node_modules/              # (GENERATED) Dependencies after npm install
│
├── README.md                  # Complete documentation
├── QUICK-START.md            # 5-minute quick start guide
├── create-icons.sh           # Script to generate icons (Linux/Mac)
└── create-icons-simple.md    # Simple icon creation guide (web-based)
```

## 🔧 Core Files

### main.js
The main Electron process. This:
- Creates the app window
- Loads your Laravel app URL
- Handles window management
- Creates the app menu
- Manages security settings

**Key configuration:**
```javascript
const APP_URL = 'http://localhost:8000'; // Change for production
```

### preload.js
Security layer between Electron and your web app. Currently minimal, but you can add custom APIs here if needed.

### package.json
Defines:
- App metadata (name, version, description)
- Dependencies (Electron, electron-builder)
- Build scripts (npm start, npm run build, etc.)
- Build configuration (icons, installers, platforms)

## 📦 Build Resources (build/)

### Icons (YOU NEED TO ADD)
Before building, add these:

- **icon.ico** - Windows icon (multi-size .ico file)
- **icon.icns** - macOS icon
- **icons/** - Directory with PNG icons for Linux:
  - 16x16.png, 32x32.png, 48x48.png, 64x64.png
  - 128x128.png, 256x256.png, 512x512.png, 1024x1024.png

**How to create:**
1. Use https://icon.kitchen/ (easiest)
2. Run `./create-icons.sh` (requires ImageMagick)
3. See `icon-instructions.md` for details

### license.txt
License text shown in Windows installer. Edit if needed.

### entitlements.mac.plist
macOS security permissions. Allows network access, JIT, etc.

## 🚀 Build Output (dist/)

After running `npm run build`, this directory contains:

### Windows
- `WishSwipe Setup 1.0.0.exe` - Standard installer (~80MB)
- `WishSwipe-1.0.0-portable.exe` - Portable/no-install version

### macOS
- `WishSwipe-1.0.0.dmg` - Disk image installer
- `WishSwipe-1.0.0-mac.zip` - Zipped app bundle

### Linux
- `WishSwipe-1.0.0.AppImage` - Universal Linux app
- `wishswipe_1.0.0_amd64.deb` - Debian/Ubuntu package
- `wishswipe-1.0.0.x86_64.rpm` - Fedora/RHEL package
- `wishswipe_1.0.0_amd64.snap` - Snap package

## 📝 Documentation Files

### README.md
Complete documentation covering:
- Installation
- Configuration
- Building for all platforms
- Deployment strategies
- Troubleshooting
- Advanced features

### QUICK-START.md
Fast 5-minute setup guide. Start here if you want to test quickly.

### create-icons-simple.md
Simple icon creation guide using web tools (no command line needed).

### icon-instructions.md (in build/)
Detailed icon creation instructions with multiple methods.

## 🔄 Generated Files/Directories

These are created automatically, don't edit manually:

- **node_modules/** - Created by `npm install`
- **dist/** - Created by `npm run build`
- **package-lock.json** - Created by npm
- **.env** - Optional, you can create this for environment variables

## 🎯 Workflow

### Development
1. `npm install` - Install dependencies
2. Edit `main.js` to set APP_URL
3. `npm start` - Run in development mode
4. Test the app

### Building for Distribution
1. Add icons to `build/` directory
2. Update APP_URL for production
3. Update version in `package.json`
4. `npm run build` - Create installer
5. Distribute files from `dist/`

## ⚙️ Key Configuration Points

### Change App URL
`main.js`, line 6:
```javascript
const APP_URL = process.env.APP_URL || 'https://your-domain.com';
```

### Change App Version
`package.json`:
```json
{
  "version": "1.0.0"
}
```

### Change Window Size
`main.js`, ~line 18:
```javascript
width: 1400,
height: 900,
```

### Change App Name
`package.json`:
```json
{
  "productName": "WishSwipe"
}
```

## 🔐 Security Notes

The app is configured with security best practices:
- `nodeIntegration: false` - Node.js not exposed to web content
- `contextIsolation: true` - Separate contexts for security
- `webSecurity: true` - Web security enabled
- External links open in system browser
- HTTPS required for production

## 📚 Additional Resources

- [Electron Docs](https://www.electronjs.org/docs)
- [electron-builder Docs](https://www.electron.build/)
- [Electron Security](https://www.electronjs.org/docs/latest/tutorial/security)

## 💡 Tips

1. **Version numbering**: Use semantic versioning (1.0.0, 1.1.0, 2.0.0)
2. **File size**: Final installers are ~80-100MB (includes Chromium)
3. **Code signing**: For production, consider signing apps (especially macOS/Windows)
4. **Auto-updates**: Already configured via electron-updater
5. **Testing**: Always test installers before distributing to users

---

**Questions?** See the main README.md or open an issue.


