const { spawn } = require('child_process');

async function measurePayload() {
    const chrome = spawn('/Applications/Google Chrome.app/Contents/MacOS/Google Chrome', [
        '--headless',
        '--disable-gpu',
        '--remote-debugging-port=9222',
        'about:blank'
    ]);
    await new Promise(r => setTimeout(r, 2000));
    const res = await fetch('http://127.0.0.1:9222/json');
    const list = await res.json();
    const ws = new WebSocket(list.find(p => p.type === 'page').webSocketDebuggerUrl);
    await new Promise(r => ws.onopen = r);

    let id = 1;
    function send(method, params = {}) {
        return new Promise((resolve) => {
            const reqId = id++;
            const handler = (evt) => {
                const data = JSON.parse(evt.data);
                if (data.id === reqId) {
                    ws.removeEventListener('message', handler);
                    resolve(data.result);
                }
            };
            ws.addEventListener('message', handler);
            ws.send(JSON.stringify({ id: reqId, method, params }));
        });
    }

    const sizes = {};
    let totalBytes = 0;
    ws.onmessage = (evt) => {
        const msg = JSON.parse(evt.data);
        if (msg.method === 'Network.responseReceived') {
            const type = msg.params.type || 'Other';
            const len = parseInt(msg.params.response.headers['content-length'] || '0', 10);
            sizes[type] = (sizes[type] || 0) + len;
            totalBytes += len;
        } else if (msg.method === 'Network.dataReceived') {
            totalBytes += msg.params.dataLength;
        }
    };

    await send('Network.enable');
    await send('Page.enable');
    await send('Page.navigate', { url: 'https://qai8358l95-staging.onrocket.site/' });
    await new Promise(r => setTimeout(r, 20000));

    console.log('--- HOMEPAGE RESOURCE SIZES ---');
    for (const [k, v] of Object.entries(sizes)) {
        console.log(`${k}: ${(v / (1024 * 1024)).toFixed(2)} MB`);
    }
    console.log(`TOTAL RECEIVED: ${(totalBytes / (1024 * 1024)).toFixed(2)} MB`);

    ws.close();
    chrome.kill();
}

measurePayload();

