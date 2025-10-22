# WishSwipe Desktop App

This directory contains the Electron wrapper to build WishSwipe as a desktop application for Windows, macOS, and Linux.

## 📋 Prerequisites

1. **Node.js** (v18 or higher)
2. **npm** or **yarn**
3. **Running Laravel app** (your WishSwipe backend must be running)

## 🚀 Quick Start

### 1. Install Dependencies

```bash
cd electron
npm install
```

### 2. Configure App URL

Create a `.env` file or set the environment variable:

```bash
# For local development
export APP_URL=http://localhost:8000

# For production
export APP_URL=https://wishswipe.com
```

Or edit the URL directly in `main.js`:
```javascript
const APP_URL = process.env.APP_URL || 'http://localhost:8000';
```

### 3. Prepare Icons (Important!)

Before building, you need to add app icons. See `build/icon-instructions.md` for details.

**Quick solution:** Use an online icon generator like https://www.electron.build/icons with your logo.

### 4. Run in Development Mode

```bash
# Start your Laravel app first
cd /home/df/wishswipe
php artisan serve

# In another terminal, run Electron
cd /home/df/wishswipe/electron
npm start
```

## 📦 Building for Distribution

### Build for Current Platform

```bash
npm run build
```

This creates an installer for your current operating system in `electron/dist/`.

### Build for Specific Platforms

```bash
# Windows
npm run build:win

# macOS
npm run build:mac

# Linux
npm run build:linux

# All platforms (requires appropriate OS or CI/CD)
npm run build:all
```

## 📁 Build Output

After building, you'll find installers in `electron/dist/`:

### Windows
- `WishSwipe Setup 1.0.0.exe` - Standard installer
- `WishSwipe-1.0.0-portable.exe` - Portable version (no installation)

### macOS
- `WishSwipe-1.0.0.dmg` - Disk image installer
- `WishSwipe-1.0.0-mac.zip` - Archive version

### Linux
- `WishSwipe-1.0.0.AppImage` - Portable Linux app
- `wishswipe_1.0.0_amd64.deb` - Debian/Ubuntu package
- `wishswipe-1.0.0.x86_64.rpm` - RedHat/Fedora package
- `wishswipe_1.0.0_amd64.snap` - Snap package

## 🔧 Configuration

### Update App Information

Edit `electron/package.json`:

```json
{
  "name": "wishswipe-desktop",
  "productName": "WishSwipe",
  "version": "1.0.0",
  "description": "WishSwipe - Swipe Your Way to Great Deals",
  "author": "Your Name",
  "homepage": "https://wishswipe.com"
}
```

### Customize Window Settings

Edit `electron/main.js` to change window size, behavior, etc:

```javascript
mainWindow = new BrowserWindow({
    width: 1400,      // Change width
    height: 900,      // Change height
    minWidth: 800,    // Minimum width
    minHeight: 600,   // Minimum height
    // ... other options
});
```

### Change App URL

For production builds, update the APP_URL:

```javascript
// In main.js
const APP_URL = 'https://wishswipe.com';
```

Or use environment variable:
```bash
export APP_URL=https://wishswipe.com
npm run build
```

## 🎨 Branding

### App Icons
Place your icons in `electron/build/`:
- `icon.ico` - Windows
- `icon.icns` - macOS
- `icons/` - Linux (PNG files)

See `build/icon-instructions.md` for detailed instructions.

### DMG Background (macOS)
Add `electron/build/background.png` (540x380px) for custom DMG installer background.

### Installer License
Edit `electron/build/license.txt` to include your license terms.

## 🌐 Deployment Scenarios

### Scenario 1: Desktop + Cloud Backend (Recommended)
Your users download the desktop app, which connects to your cloud-hosted Laravel backend.

**Pros:**
- Centralized data
- Easy updates to backend
- Users get native desktop experience

**Setup:**
1. Deploy your Laravel app to a server (https://wishswipe.com)
2. Build desktop app with `APP_URL=https://wishswipe.com`
3. Distribute installers to users

### Scenario 2: Fully Local (Advanced)
Package Laravel with the desktop app for offline use.

**Note:** This is complex and requires bundling PHP, database, etc. Not recommended unless you need full offline functionality.

## 🔐 Security Notes

1. **HTTPS Required:** For production, your Laravel app MUST use HTTPS
2. **CORS:** Configure CORS in Laravel to allow desktop app requests
3. **Session/Auth:** Desktop app uses web sessions/cookies like a browser

### Laravel CORS Configuration

Add to `config/cors.php`:
```php
'paths' => ['api/*', 'sanctum/csrf-cookie', 'app/*', 'admin/*'],
'supports_credentials' => true,
```

## 🐛 Troubleshooting

### "Failed to load URL"
- Ensure your Laravel app is running
- Check the APP_URL is correct
- Verify firewall isn't blocking connections

### "White screen on startup"
- Check browser console (View → Toggle Developer Tools)
- Verify Laravel app is accessible in regular browser
- Check for CORS issues

### "Build failed - missing icons"
- Add icon files to `electron/build/`
- Or temporarily comment out icon paths in package.json

### "App crashes on launch"
- Run with `npm run dev` to see error logs
- Check DevTools console for errors

## 📝 Notes

### Platform-Specific Building

To build for different platforms, you generally need to be on that platform:

- **Windows installers**: Build on Windows
- **macOS installers**: Build on macOS
- **Linux installers**: Build on Linux

**Exception:** You can build Windows/Linux apps from macOS with proper configuration.

### Auto-Updates

The app includes `electron-updater` for automatic updates. To enable:

1. Set up a GitHub repository for releases
2. Update `publish` section in package.json
3. Create releases with installers as assets
4. The app will automatically check for updates

## 🎯 Next Steps

1. ✅ Install dependencies: `npm install`
2. ✅ Add app icons (see icon-instructions.md)
3. ✅ Configure APP_URL for your environment
4. ✅ Test in development: `npm start`
5. ✅ Build for distribution: `npm run build`
6. ✅ Test the installer
7. ✅ Distribute to users!

## 📚 Resources

- [Electron Documentation](https://www.electronjs.org/docs)
- [electron-builder Documentation](https://www.electron.build/)
- [WishSwipe Main Repo](https://github.com/your-repo/wishswipe)

## 💡 Tips

1. **Test thoroughly** before distributing to users
2. **Use semantic versioning** for updates (1.0.0, 1.0.1, etc.)
3. **Code sign your apps** for better user trust (especially macOS/Windows)
4. **Set up CI/CD** to automatically build releases (GitHub Actions recommended)

---

**Need help?** Check the troubleshooting section or open an issue on GitHub.


