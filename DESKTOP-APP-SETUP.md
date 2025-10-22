# 🖥️ WishSwipe Desktop App - Quick Setup Guide

This guide will help you build WishSwipe as a downloadable desktop application for Windows, Mac, and Linux.

## 🎯 What You'll Get

After following this guide, you'll have:
- ✅ **Windows**: `.exe` installer and portable version
- ✅ **macOS**: `.dmg` installer
- ✅ **Linux**: `.AppImage`, `.deb`, `.rpm`, and `.snap` packages

Users can download and install WishSwipe like any other desktop app!

## 📝 Prerequisites

1. **Node.js** installed (check with `node --version`)
2. **Your Laravel app running** (WishSwipe backend)

## 🚀 Quick Start (5 Steps)

### Step 1: Navigate to Electron Directory

```bash
cd /home/df/wishswipe/electron
```

### Step 2: Install Dependencies

```bash
npm install
```

This installs Electron and the build tools (~2-3 minutes).

### Step 3: Create App Icons

**Option A - Use Online Tool (Easiest, 2 minutes):**

1. Go to https://icon.kitchen/ or https://www.electron.build/icons
2. Upload your logo: `/home/df/wishswipe/public/images/wishswipe_logo_black.png`
3. Select "Electron" as the platform
4. Download the icons package
5. Extract files to `/home/df/wishswipe/electron/build/`

**Option B - Skip icons for testing:**

For now, you can test without icons. The app will use default Electron icons.

### Step 4: Set Your App URL

Open `/home/df/wishswipe/electron/main.js` and update line 6:

```javascript
// For local testing:
const APP_URL = 'http://localhost:8000';

// For production (when you deploy):
const APP_URL = 'https://wishswipe.com';
```

### Step 5: Build the Desktop App

```bash
# Make sure your Laravel app is running first!
# In another terminal: php artisan serve

# Then build the desktop app
npm run build
```

**Build time:** 3-5 minutes

**Output location:** `/home/df/wishswipe/electron/dist/`

## 📦 What Gets Created

Depending on your OS:

### On Windows:
- `WishSwipe Setup 1.0.0.exe` - Full installer (~80MB)
- `WishSwipe-1.0.0-portable.exe` - No-install version (~80MB)

### On macOS:
- `WishSwipe-1.0.0.dmg` - Drag-to-Applications installer
- `WishSwipe-1.0.0-mac.zip` - Zipped app

### On Linux:
- `WishSwipe-1.0.0.AppImage` - Universal Linux app
- `wishswipe_1.0.0_amd64.deb` - Ubuntu/Debian
- `wishswipe-1.0.0.x86_64.rpm` - Fedora/RedHat
- `wishswipe_1.0.0_amd64.snap` - Snap package

## 🧪 Testing the App

Before building, test in development mode:

```bash
# Terminal 1: Start Laravel
cd /home/df/wishswipe
php artisan serve

# Terminal 2: Start Electron
cd /home/df/wishswipe/electron
npm start
```

A desktop window should open showing your WishSwipe app!

## 🌐 Deployment Strategy

### For Production Use:

1. **Deploy your Laravel backend** to a server with a domain (e.g., https://wishswipe.com)
2. **Update APP_URL** in `main.js` to your production URL
3. **Build the desktop app** with `npm run build`
4. **Distribute installers** to users (via website download, email, etc.)

Users download and install the desktop app → It connects to your cloud backend.

### Architecture:
```
Desktop App (User's Computer)
       ↓ HTTPS
Laravel Backend (Your Server - wishswipe.com)
       ↓
Database (Your Server)
```

**Benefits:**
- Users get native desktop experience
- You maintain one backend (easy to update)
- Data is centralized
- Works across all platforms

## ⚙️ Configuration

### Change App Version

Edit `/home/df/wishswipe/electron/package.json`:

```json
{
  "version": "1.0.0"  // Change to 1.1.0, 2.0.0, etc.
}
```

### Customize Window Size

Edit `/home/df/wishswipe/electron/main.js`:

```javascript
mainWindow = new BrowserWindow({
    width: 1400,      // Change these
    height: 900,
    // ...
});
```

### Add Auto-Updates

The app is already configured for auto-updates via GitHub releases. To enable:

1. Create a GitHub repository for your app
2. Build new versions and upload installers as release assets
3. The app automatically checks for updates on launch

## 🔧 Platform-Specific Builds

Build for specific platforms:

```bash
npm run build:win    # Windows only
npm run build:mac    # macOS only
npm run build:linux  # Linux only
npm run build:all    # All platforms (requires proper setup)
```

**Note:** Generally, you need to build on the target platform:
- Build Windows apps on Windows
- Build Mac apps on macOS
- Build Linux apps on Linux

## ❓ Troubleshooting

### "Cannot connect to app"
- Ensure your Laravel app is running (`php artisan serve`)
- Check the APP_URL matches your Laravel URL
- Try accessing the URL in a regular browser first

### "Build failed"
- Make sure you ran `npm install` in the electron directory
- Check Node.js version (should be 18+)
- See full error details in terminal

### "Missing icons warning"
- This is okay for testing
- For production, add proper icons (see Step 3)

### "White/blank screen"
- Press F12 to open DevTools and check for errors
- Verify Laravel app is accessible at the APP_URL
- Check CORS settings in Laravel

## 📚 More Information

For detailed documentation, see `/home/df/wishswipe/electron/README.md`

For icon creation help, see `/home/df/wishswipe/electron/build/icon-instructions.md`

## 🎉 You're Done!

You now have:
1. ✅ Electron setup in `/home/df/wishswipe/electron/`
2. ✅ Build scripts ready to use
3. ✅ Desktop installers for distribution

**Next steps:**
1. Test the app in development mode (`npm start`)
2. Build for your platform (`npm run build`)
3. Test the installer
4. Deploy your Laravel backend
5. Build production version with production URL
6. Distribute to users!

---

**Questions?** Check the detailed README in the `electron/` directory or the Electron documentation at https://electronjs.org


