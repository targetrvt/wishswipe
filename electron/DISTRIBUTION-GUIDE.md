# 📦 WishSwipe Desktop - Distribution Guide

How to make your desktop app downloadable for users.

## 🎯 What You'll Get After Building

Once `npm run build` finishes, you'll have installer files in `/home/df/wishswipe/electron/dist/`:

### Linux Installers
- **WishSwipe-1.0.0.AppImage** (~150-200MB) - Universal Linux app
- **wishswipe-desktop_1.0.0_amd64.deb** - Debian/Ubuntu package
- **wishswipe-desktop-1.0.0.x86_64.rpm** - Fedora/RHEL package  
- **wishswipe-desktop_1.0.0_amd64.snap** - Snap package

### Windows Installers (if built on Windows)
- **WishSwipe Setup 1.0.0.exe** - Standard installer
- **WishSwipe-1.0.0-portable.exe** - Portable version

### macOS Installers (if built on Mac)
- **WishSwipe-1.0.0.dmg** - Disk image
- **WishSwipe-1.0.0-mac.zip** - Zipped app

## 📤 How to Make It Downloadable

### Option 1: Host on Your Website (Recommended)

Upload the installer files to your web server and create download links.

**Example:**

1. Upload files to your server:
   ```bash
   # Using SCP
   scp electron/dist/WishSwipe-1.0.0.AppImage user@yourserver.com:/var/www/wishswipe.com/downloads/
   scp electron/dist/*.deb user@yourserver.com:/var/www/wishswipe.com/downloads/
   ```

2. Create a downloads page on your site:
   ```html
   <!-- On your landing page or downloads page -->
   <div class="downloads">
     <h2>Download WishSwipe Desktop</h2>
     
     <!-- Linux -->
     <a href="/downloads/WishSwipe-1.0.0.AppImage" download>
       Download for Linux (AppImage)
     </a>
     
     <!-- Debian/Ubuntu -->
     <a href="/downloads/wishswipe-desktop_1.0.0_amd64.deb" download>
       Download for Ubuntu/Debian (.deb)
     </a>
     
     <!-- Windows -->
     <a href="/downloads/WishSwipe-Setup-1.0.0.exe" download>
       Download for Windows
     </a>
     
     <!-- macOS -->
     <a href="/downloads/WishSwipe-1.0.0.dmg" download>
       Download for macOS
     </a>
   </div>
   ```

### Option 2: GitHub Releases (Best for Open Source)

Upload to GitHub releases for automatic version management and CDN hosting.

**Steps:**

1. Create a GitHub repository for your app
2. Go to Releases → Create new release
3. Tag version: `v1.0.0`
4. Upload installer files from `dist/` folder
5. Publish release

**Download links will be:**
```
https://github.com/yourusername/wishswipe-desktop/releases/download/v1.0.0/WishSwipe-1.0.0.AppImage
https://github.com/yourusername/wishswipe-desktop/releases/download/v1.0.0/wishswipe-desktop_1.0.0_amd64.deb
```

### Option 3: Cloud Storage Services

Upload to Google Drive, Dropbox, OneDrive, etc., and share public links.

**Pros:** Easy, no server needed
**Cons:** Not professional, slower downloads

### Option 4: CDN Services

Use services like:
- **Cloudflare R2** (free tier available)
- **AWS S3** + CloudFront
- **DigitalOcean Spaces**
- **Backblaze B2**

## 🌐 Add Download Links to Your Laravel App

Edit your landing page (`/home/df/wishswipe/resources/views/landing.blade.php`):

```php
<!-- Add this section to your landing page -->
<section class="download-section">
    <div class="container">
        <h2>Download WishSwipe Desktop</h2>
        <p>Get the native desktop experience</p>
        
        <div class="download-buttons">
            <!-- Detect OS and show appropriate download -->
            <div class="download-option">
                <i class="fab fa-linux"></i>
                <h3>Linux</h3>
                <a href="{{ asset('downloads/WishSwipe-1.0.0.AppImage') }}" 
                   class="btn btn-primary"
                   download>
                    Download AppImage
                </a>
                <a href="{{ asset('downloads/wishswipe-desktop_1.0.0_amd64.deb') }}" 
                   class="btn btn-secondary"
                   download>
                    Download .deb
                </a>
            </div>
            
            <div class="download-option">
                <i class="fab fa-windows"></i>
                <h3>Windows</h3>
                <a href="{{ asset('downloads/WishSwipe-Setup-1.0.0.exe') }}" 
                   class="btn btn-primary"
                   download>
                    Download Installer
                </a>
            </div>
            
            <div class="download-option">
                <i class="fab fa-apple"></i>
                <h3>macOS</h3>
                <a href="{{ asset('downloads/WishSwipe-1.0.0.dmg') }}" 
                   class="btn btn-primary"
                   download>
                    Download .dmg
                </a>
            </div>
        </div>
    </div>
</section>
```

## 🤖 Auto-Detect User's OS (JavaScript)

Add this to your landing page to automatically show the right download:

```javascript
<script>
function detectOS() {
    const userAgent = window.navigator.userAgent.toLowerCase();
    const platform = window.navigator.platform.toLowerCase();
    
    if (platform.includes('win')) return 'windows';
    if (platform.includes('mac')) return 'macos';
    if (platform.includes('linux')) return 'linux';
    
    return 'unknown';
}

window.addEventListener('DOMContentLoaded', () => {
    const os = detectOS();
    const downloadButtons = document.querySelectorAll('.download-option');
    
    downloadButtons.forEach(btn => {
        if (btn.dataset.os === os) {
            btn.classList.add('recommended');
        }
    });
});
</script>
```

## 📊 File Sizes to Expect

Typical installer sizes:
- **AppImage:** 150-200 MB
- **.deb:** 150-200 MB
- **.rpm:** 150-200 MB
- **Windows .exe:** 80-100 MB
- **macOS .dmg:** 150-200 MB

These are large because they include:
- Chromium browser engine
- Node.js runtime
- Your app code

## 🔐 Important: Update APP_URL for Production

**Before building for real users**, update `electron/main.js`:

```javascript
// CHANGE THIS before building for distribution
const APP_URL = 'https://wishswipe.com';  // Your production URL
```

Then rebuild:
```bash
npm run build
```

## 🚀 Quick Distribution Checklist

Before distributing to users:

- [ ] Update APP_URL to production domain
- [ ] Update version in package.json (1.0.0 → 1.0.1, etc.)
- [ ] Test the installer on a clean machine
- [ ] Ensure Laravel backend is deployed and accessible
- [ ] Build installers: `npm run build`
- [ ] Upload installers to hosting location
- [ ] Create download page with links
- [ ] Test downloads from the page
- [ ] Announce to users!

## 📝 Example Download Page HTML

Here's a complete download page you can add:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Download WishSwipe Desktop</title>
    <style>
        .download-container {
            max-width: 1200px;
            margin: 50px auto;
            text-align: center;
        }
        .download-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        .download-card {
            border: 2px solid #ddd;
            border-radius: 12px;
            padding: 30px;
            transition: transform 0.3s;
        }
        .download-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .download-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .download-btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 10px 5px;
        }
        .file-info {
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="download-container">
        <h1>Download WishSwipe Desktop</h1>
        <p>Choose your platform and start swiping!</p>
        
        <div class="download-grid">
            <!-- Linux -->
            <div class="download-card">
                <div class="download-icon">🐧</div>
                <h2>Linux</h2>
                <a href="/downloads/WishSwipe-1.0.0.AppImage" 
                   class="download-btn" download>
                    AppImage (Universal)
                </a>
                <a href="/downloads/wishswipe-desktop_1.0.0_amd64.deb" 
                   class="download-btn" download>
                    .deb (Ubuntu/Debian)
                </a>
                <div class="file-info">Version 1.0.0 • ~150 MB</div>
            </div>
            
            <!-- Windows -->
            <div class="download-card">
                <div class="download-icon">🪟</div>
                <h2>Windows</h2>
                <a href="/downloads/WishSwipe-Setup-1.0.0.exe" 
                   class="download-btn" download>
                    Download Installer
                </a>
                <a href="/downloads/WishSwipe-1.0.0-portable.exe" 
                   class="download-btn" download>
                    Portable Version
                </a>
                <div class="file-info">Version 1.0.0 • ~80 MB</div>
            </div>
            
            <!-- macOS -->
            <div class="download-card">
                <div class="download-icon">🍎</div>
                <h2>macOS</h2>
                <a href="/downloads/WishSwipe-1.0.0.dmg" 
                   class="download-btn" download>
                    Download .dmg
                </a>
                <div class="file-info">Version 1.0.0 • ~150 MB • Apple Silicon + Intel</div>
            </div>
        </div>
        
        <div style="margin-top: 50px;">
            <h3>Installation Instructions</h3>
            <ul style="text-align: left; max-width: 600px; margin: 20px auto;">
                <li><strong>Linux AppImage:</strong> Make executable (chmod +x) and run</li>
                <li><strong>Linux .deb:</strong> Double-click or use: sudo dpkg -i wishswipe*.deb</li>
                <li><strong>Windows:</strong> Run the installer and follow prompts</li>
                <li><strong>macOS:</strong> Open .dmg and drag to Applications</li>
            </ul>
        </div>
    </div>
</body>
</html>
```

## 🔄 Updates

When you release a new version:

1. Update version in `package.json`
2. Rebuild: `npm run build`
3. Upload new installers
4. Update download links
5. If using GitHub Releases, users with auto-update enabled will be notified!

---

**Need help?** See the main README.md for more details.


