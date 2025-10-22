# 📱 WishSwipe PWA - Installation Guide for Mac & All Platforms

## 🎉 What is a PWA?

A **Progressive Web App (PWA)** lets users install WishSwipe directly from their browser - no App Store needed! Works on:

- ✅ **macOS** (Safari & Chrome)
- ✅ **Windows** (Edge & Chrome)
- ✅ **Linux** (Chrome & Firefox)
- ✅ **iOS** (Safari)
- ✅ **Android** (Chrome)

## 🍎 Installation on macOS

### Method 1: Safari (macOS Monterey 12.3+)

1. **Open Safari** and go to your WishSwipe website
2. **Click Share button** (or File → Share)
3. **Select "Add to Dock"**
4. **Click "Add"**

WishSwipe will now appear in your Dock and Applications folder!

### Method 2: Google Chrome/Edge (macOS)

1. **Open Chrome/Edge** and visit your WishSwipe site
2. **Look for install icon** in address bar (➕ or ⬇️)
3. **Click "Install WishSwipe"**
4. Or click **⋮ menu → Install WishSwipe**

The app will open in its own window and appear in your Applications folder!

### Method 3: Manual (Safari)

1. Visit WishSwipe in Safari
2. Click **📱 Install App** button (floating button on page)
3. Follow the prompts

## 📱 Installation on iOS (iPhone/iPad)

1. **Open Safari** and go to WishSwipe
2. Tap the **Share button** (square with arrow)
3. Scroll down and tap **"Add to Home Screen"**
4. Tap **"Add"**

WishSwipe icon appears on your home screen like a native app!

## 🪟 Installation on Windows

### Chrome/Edge

1. Open your WishSwipe site
2. Click **⋮ menu → Install WishSwipe**
3. Or click install icon in address bar
4. Click **"Install"**

## 🤖 Installation on Android

1. Open Chrome and visit WishSwipe
2. Tap **⋮ menu → Install app** or **Add to Home screen**
3. Tap **"Install"**

## ✨ Features After Installation

Once installed, WishSwipe:

- ✅ Opens in its own window (looks like native app)
- ✅ Appears in Applications/Dock/Start Menu
- ✅ Works offline (cached data)
- ✅ Receives push notifications (if enabled)
- ✅ Launches faster
- ✅ No browser UI (full-screen experience)

## 🎯 What You Get

### On macOS:
- App icon in **Applications** folder
- Icon in **Dock**
- Launchpad icon
- Cmd+Tab app switcher
- Full-screen mode

### On Windows:
- Start Menu shortcut
- Desktop shortcut (optional)
- Taskbar pinning
- Alt+Tab switching

### On Mobile:
- Home screen icon
- Full-screen experience
- App drawer listing

## 🔧 Developer Setup (Your Side)

### ✅ Already Done For You:

1. **manifest.json** - PWA configuration ✅
2. **Service Worker (sw.js)** - Offline support ✅
3. **PWA meta tags** - Browser compatibility ✅
4. **Install button** - Floating install prompt ✅
5. **iOS compatibility** - Safari support ✅

### Files Created:

- `/public/manifest.json` - PWA manifest
- `/public/sw.js` - Service worker for caching
- Landing page updated with PWA tags

## 🌐 Testing PWA Installation

### Test on Mac:

1. Visit `http://wishswipe.test` in Safari
2. Look for "Add to Dock" in Share menu
3. Or open in Chrome and look for install prompt

### Test on Mobile (iOS Simulator):

```bash
# If you have Xcode installed
open -a Simulator
# Open Safari in simulator
# Navigate to your Valet URL
```

### Test Install Prompt:

Open Chrome DevTools:
1. F12 → Application tab
2. Check "Manifest" - should show WishSwipe details
3. Check "Service Workers" - should show registered worker

## 📊 PWA Requirements Met

✅ **HTTPS or localhost** - Valet supports both
✅ **Web App Manifest** - Created
✅ **Service Worker** - Registered
✅ **Icons** - Using WishSwipe logo
✅ **Responsive** - Your site is mobile-friendly
✅ **Offline support** - Service worker caches assets

## 🚀 For Production

When deploying to production:

1. **Ensure HTTPS** - Required for PWA on production
2. **Update manifest.json** - Change `start_url` if needed
3. **Test on real devices** - iOS, Android, Desktop
4. **Update cache version** - In sw.js when you update

### Update Service Worker Version:

Edit `/public/sw.js`:
```javascript
const CACHE_NAME = 'wishswipe-v1.0.1'; // Increment version
```

## 📝 User Instructions to Share

### For Mac Users:

> **Install WishSwipe on your Mac:**
> 
> **Safari:**
> 1. Visit wishswipe.com
> 2. Click Share → Add to Dock
> 3. Done! Find it in your Applications
>
> **Chrome:**
> 1. Visit wishswipe.com  
> 2. Click install icon in address bar
> 3. Enjoy the app!

### For iPhone Users:

> **Add WishSwipe to your iPhone:**
> 
> 1. Open Safari and go to wishswipe.com
> 2. Tap Share button (square with arrow)
> 3. Scroll and tap "Add to Home Screen"
> 4. Tap "Add"
> 
> WishSwipe will appear on your home screen!

## 🎨 Customization

### Change App Colors:

Edit `/public/manifest.json`:
```json
{
  "theme_color": "#667eea",      // Address bar color
  "background_color": "#ffffff"   // Splash screen color
}
```

### Update Icons:

Replace in manifest.json with your icon paths:
```json
{
  "icons": [
    {
      "src": "/images/icon-192.png",
      "sizes": "192x192",
      "type": "image/png"
    }
  ]
}
```

## 🔍 Troubleshooting

### "Add to Dock" not showing (Safari):

- macOS Monterey 12.3+ required
- Visit the site a few times first
- Clear Safari cache and try again

### Install button not appearing (Chrome):

- Check HTTPS/localhost
- Check DevTools console for errors
- Ensure manifest.json is accessible

### Service Worker not registering:

- Check browser console for errors
- Ensure `/sw.js` is accessible
- Try hard refresh (Cmd+Shift+R)

## 📚 Resources

- [PWA Documentation](https://web.dev/progressive-web-apps/)
- [Safari PWA Support](https://webkit.org/blog/13878/web-push-for-web-apps-on-ios-and-ipados/)
- [Chrome Install Criteria](https://web.dev/install-criteria/)

---

**Your WishSwipe app is now installable on Mac and all platforms!** 🎉

