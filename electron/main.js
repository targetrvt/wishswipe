const { app, BrowserWindow, Menu, shell } = require('electron');
const path = require('path');

// Disable GPU sandbox on Linux to avoid SUID sandbox issues
if (process.platform === 'linux') {
    app.commandLine.appendSwitch('no-sandbox');
    app.commandLine.appendSwitch('disable-gpu-sandbox');
}

// Your Laravel app URL
// Change this to your production URL when deploying
const APP_URL = process.env.APP_URL || 'http://wishswipe.test';

let mainWindow;

function createWindow() {
    mainWindow = new BrowserWindow({
        width: 1400,
        height: 900,
        minWidth: 800,
        minHeight: 600,
        icon: path.join(__dirname, 'build', 'icon.png'),
        webPreferences: {
            preload: path.join(__dirname, 'preload.js'),
            nodeIntegration: false,
            contextIsolation: true,
            webSecurity: true,
            devTools: !app.isPackaged, // Only allow devTools in development
        },
        backgroundColor: '#667eea',
        titleBarStyle: 'default',
        show: false, // Don't show until ready
    });

    // Show window immediately with loading message
    mainWindow.show();
    
    // Create a simple loading HTML
    const loadingHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    color: white;
                }
                .loading-container {
                    text-align: center;
                }
                .logo {
                    font-size: 60px;
                    margin-bottom: 20px;
                }
                h1 {
                    font-size: 32px;
                    margin: 20px 0;
                }
                .spinner {
                    border: 4px solid rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    border-top: 4px solid white;
                    width: 50px;
                    height: 50px;
                    animation: spin 1s linear infinite;
                    margin: 30px auto;
                }
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                .message {
                    font-size: 14px;
                    opacity: 0.9;
                    margin-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class="loading-container">
                <div class="logo">🛒</div>
                <h1>WishSwipe</h1>
                <div class="spinner"></div>
                <p class="message">Loading your marketplace...</p>
                <p class="message" style="font-size: 12px; margin-top: 20px;">Connecting to ${APP_URL}</p>
            </div>
        </body>
        </html>
    `;
    
    // Load loading screen first
    mainWindow.loadURL(`data:text/html;charset=utf-8,${encodeURIComponent(loadingHTML)}`);
    
    // Then load the actual app after a short delay
    setTimeout(() => {
        mainWindow.loadURL(APP_URL).catch(err => {
            console.error('Failed to load app:', err);
            showErrorPage();
        });
    }, 1000);

    // Handle successful load
    mainWindow.webContents.on('did-finish-load', () => {
        console.log('App loaded successfully');
    });
    
    // Handle load failures
    mainWindow.webContents.on('did-fail-load', (event, errorCode, errorDescription) => {
        console.error('Failed to load:', errorCode, errorDescription);
        if (errorCode !== -3) { // -3 is user cancelled
            showErrorPage();
        }
    });

    // Open external links in default browser
    mainWindow.webContents.setWindowOpenHandler(({ url }) => {
        // If it's an external URL, open in browser
        if (url.startsWith('http://') || url.startsWith('https://')) {
            shell.openExternal(url);
            return { action: 'deny' };
        }
        return { action: 'allow' };
    });

    // Handle navigation - open external links in browser
    mainWindow.webContents.on('will-navigate', (event, url) => {
        const appUrl = new URL(APP_URL);
        const navigateUrl = new URL(url);
        
        // If navigating to external domain, open in browser
        if (navigateUrl.host !== appUrl.host) {
            event.preventDefault();
            shell.openExternal(url);
        }
    });

    // Create application menu
    createMenu();

    // Open DevTools in development
    if (!app.isPackaged) {
        mainWindow.webContents.openDevTools();
    }

    mainWindow.on('closed', () => {
        mainWindow = null;
    });
}

function showErrorPage() {
    const errorHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    color: white;
                }
                .error-container {
                    text-align: center;
                    max-width: 600px;
                    padding: 40px;
                }
                .error-icon {
                    font-size: 60px;
                    margin-bottom: 20px;
                }
                h1 {
                    font-size: 28px;
                    margin: 20px 0;
                }
                p {
                    font-size: 16px;
                    line-height: 1.6;
                    opacity: 0.95;
                }
                .url {
                    background: rgba(0, 0, 0, 0.2);
                    padding: 10px 20px;
                    border-radius: 8px;
                    margin: 20px 0;
                    font-family: monospace;
                }
                .solutions {
                    text-align: left;
                    margin: 30px auto;
                    background: rgba(255, 255, 255, 0.1);
                    padding: 20px;
                    border-radius: 12px;
                }
                .solutions h3 {
                    margin-top: 0;
                }
                .solutions li {
                    margin: 10px 0;
                    line-height: 1.6;
                }
                button {
                    background: white;
                    color: #667eea;
                    border: none;
                    padding: 12px 30px;
                    font-size: 16px;
                    font-weight: 600;
                    border-radius: 8px;
                    cursor: pointer;
                    margin: 10px;
                }
                button:hover {
                    opacity: 0.9;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <div class="error-icon">⚠️</div>
                <h1>Cannot Connect to WishSwipe</h1>
                <p>The app couldn't connect to the server.</p>
                <div class="url">${APP_URL}</div>
                
                <div class="solutions">
                    <h3>Quick Fixes:</h3>
                    <ul>
                        <li><strong>Check if Valet is running:</strong> Open Terminal and run: <code>valet status</code></li>
                        <li><strong>Restart Valet:</strong> <code>valet restart</code></li>
                        <li><strong>Check the URL:</strong> Make sure ${APP_URL} works in your browser</li>
                        <li><strong>Check your internet:</strong> Ensure you're connected to the network</li>
                    </ul>
                </div>
                
                <button onclick="location.reload()">🔄 Try Again</button>
                <button onclick="require('electron').shell.openExternal('${APP_URL}')">🌐 Open in Browser</button>
            </div>
            
            <script>
                // Auto-retry every 5 seconds
                let retryCount = 0;
                const maxRetries = 3;
                
                function autoRetry() {
                    if (retryCount < maxRetries) {
                        retryCount++;
                        setTimeout(() => {
                            location.reload();
                        }, 5000);
                    }
                }
                
                // Start auto-retry
                autoRetry();
            </script>
        </body>
        </html>
    `;
    
    if (mainWindow) {
        mainWindow.loadURL(`data:text/html;charset=utf-8,${encodeURIComponent(errorHTML)}`);
    }
}

function createMenu() {
    const template = [
        {
            label: 'File',
            submenu: [
                {
                    label: 'Reload',
                    accelerator: 'CmdOrCtrl+R',
                    click: () => {
                        if (mainWindow) mainWindow.reload();
                    }
                },
                {
                    label: 'Force Reload',
                    accelerator: 'CmdOrCtrl+Shift+R',
                    click: () => {
                        if (mainWindow) mainWindow.webContents.reloadIgnoringCache();
                    }
                },
                { type: 'separator' },
                {
                    label: 'Quit',
                    accelerator: process.platform === 'darwin' ? 'Cmd+Q' : 'Alt+F4',
                    click: () => {
                        app.quit();
                    }
                }
            ]
        },
        {
            label: 'Edit',
            submenu: [
                { role: 'undo' },
                { role: 'redo' },
                { type: 'separator' },
                { role: 'cut' },
                { role: 'copy' },
                { role: 'paste' },
                { role: 'selectAll' }
            ]
        },
        {
            label: 'View',
            submenu: [
                { role: 'resetZoom' },
                { role: 'zoomIn' },
                { role: 'zoomOut' },
                { type: 'separator' },
                { role: 'togglefullscreen' }
            ]
        },
        {
            label: 'Window',
            submenu: [
                { role: 'minimize' },
                { role: 'close' }
            ]
        },
        {
            label: 'Help',
            submenu: [
                {
                    label: 'Learn More',
                    click: async () => {
                        await shell.openExternal('https://github.com/your-repo/wishswipe');
                    }
                },
                {
                    label: 'Toggle Developer Tools',
                    accelerator: process.platform === 'darwin' ? 'Alt+Command+I' : 'Ctrl+Shift+I',
                    click: () => {
                        if (mainWindow) mainWindow.webContents.toggleDevTools();
                    }
                }
            ]
        }
    ];

    // Add macOS-specific menu items
    if (process.platform === 'darwin') {
        template.unshift({
            label: app.name,
            submenu: [
                { role: 'about' },
                { type: 'separator' },
                { role: 'services' },
                { type: 'separator' },
                { role: 'hide' },
                { role: 'hideOthers' },
                { role: 'unhide' },
                { type: 'separator' },
                { role: 'quit' }
            ]
        });

        // Window menu
        template[4].submenu = [
            { role: 'close' },
            { role: 'minimize' },
            { role: 'zoom' },
            { type: 'separator' },
            { role: 'front' }
        ];
    }

    const menu = Menu.buildFromTemplate(template);
    Menu.setApplicationMenu(menu);
}

// App event handlers
app.whenReady().then(createWindow);

app.on('window-all-closed', () => {
    // On macOS, apps typically stay active until user quits explicitly
    if (process.platform !== 'darwin') {
        app.quit();
    }
});

app.on('activate', () => {
    // On macOS, re-create window when dock icon is clicked
    if (BrowserWindow.getAllWindows().length === 0) {
        createWindow();
    }
});

// Handle certificate errors (useful for development with self-signed certs)
app.on('certificate-error', (event, webContents, url, error, certificate, callback) => {
    if (!app.isPackaged && url.startsWith('https://localhost')) {
        // In development, allow self-signed certs for localhost
        event.preventDefault();
        callback(true);
    } else {
        callback(false);
    }
});

// Security: Prevent navigation to potentially dangerous protocols
app.on('web-contents-created', (event, contents) => {
    contents.on('will-navigate', (event, navigationUrl) => {
        const parsedUrl = new URL(navigationUrl);
        const allowedProtocols = ['http:', 'https:'];
        
        if (!allowedProtocols.includes(parsedUrl.protocol)) {
            event.preventDefault();
        }
    });
});

