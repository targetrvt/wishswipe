const { contextBridge, ipcRenderer } = require('electron');

// Expose protected methods that allow the renderer process to use
// ipcRenderer without exposing the entire object
contextBridge.exposeInMainWorld(
    'electron',
    {
        // Add any custom APIs you want to expose to your web app
        platform: process.platform,
        versions: {
            node: process.versions.node,
            chrome: process.versions.chrome,
            electron: process.versions.electron,
        },
        // Example: send message to main process
        // send: (channel, data) => {
        //     const validChannels = ['toMain'];
        //     if (validChannels.includes(channel)) {
        //         ipcRenderer.send(channel, data);
        //     }
        // },
        // Example: receive message from main process
        // receive: (channel, func) => {
        //     const validChannels = ['fromMain'];
        //     if (validChannels.includes(channel)) {
        //         ipcRenderer.on(channel, (event, ...args) => func(...args));
        //     }
        // }
    }
);

// Log that preload script has loaded
console.log('WishSwipe Desktop - Preload script loaded');


