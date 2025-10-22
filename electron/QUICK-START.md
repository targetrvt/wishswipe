# ⚡ WishSwipe Desktop - Quick Start

Get your desktop app running in **5 minutes**!

## Step 1: Install Dependencies (1 min)

```bash
cd /home/df/wishswipe/electron
npm install
```

## Step 2: Start Your Laravel App (30 sec)

```bash
# In a separate terminal
cd /home/df/wishswipe
php artisan serve
```

Keep this running!

## Step 3: Test the Desktop App (30 sec)

```bash
# In the electron directory
npm start
```

A desktop window should open with WishSwipe! 🎉

## Step 4: Create Icons (2 min)

Go to https://icon.kitchen/
- Upload: `/home/df/wishswipe/public/images/wishswipe_logo_black.png`
- Platform: Electron
- Download and extract to `/home/df/wishswipe/electron/build/`

**Or skip for now** - the app will use default icons.

## Step 5: Build for Distribution (3 min)

```bash
npm run build
```

Your installer will be in `/home/df/wishswipe/electron/dist/`!

## 🎯 For Production

Before building for real users:

1. **Deploy Laravel** to a server (e.g., https://wishswipe.com)

2. **Update URL** in `main.js`:
   ```javascript
   const APP_URL = 'https://wishswipe.com';
   ```

3. **Add real icons** (see Step 4)

4. **Build again**:
   ```bash
   npm run build
   ```

5. **Distribute** the installer to users!

## 📦 What You Get

- **Windows**: `WishSwipe Setup 1.0.0.exe`
- **macOS**: `WishSwipe-1.0.0.dmg`
- **Linux**: `WishSwipe-1.0.0.AppImage`, `.deb`, `.rpm`

## ❓ Problems?

- **Can't connect**: Make sure Laravel is running (`php artisan serve`)
- **Build failed**: Run `npm install` again
- **White screen**: Press F12 to see errors

## 📚 More Help

- **Full docs**: See `README.md` in this directory
- **Icon help**: See `create-icons-simple.md`
- **Setup guide**: See `/home/df/wishswipe/DESKTOP-APP-SETUP.md`

---

**That's it!** You now have a desktop app. 🚀


