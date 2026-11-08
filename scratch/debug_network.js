const { spawn } = require('child_process');

async function debugNetwork() {
    const chrome = spawn('/Applications/Google Chrome.app/Contents/MacOS/Google Chrome', [
        '--headless',
        '--disable-gpu',
        '--remote-debugging-port=9222',
        'about:blank'
    ]);
    await new Promise(r => setTimeout(r, 2000));
    const res = await fetch('http://127.0.0.1:9222/json');
    const list = await res.json();
    const page = list.find(p => p.type === 'page');
    const ws = new WebSocket(page.webSocketDebuggerUrl);
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

    const requests = new Map();
    ws.onmessage = (evt) => {
        const msg = JSON.parse(evt.data);
        if (msg.method === 'Network.requestWillBeSent') {
            requests.set(msg.params.requestId, {
                url: msg.params.request.url,
                start: msg.params.timestamp,
                type: msg.params.type
            });
        } else if (msg.method === 'Network.loadingFinished') {
            const r = requests.get(msg.params.requestId);
            if (r) {
                r.duration = (msg.params.timestamp - r.start);
                r.finished = true;
            }
        } else if (msg.method === 'Network.loadingFailed') {
            const r = requests.get(msg.params.requestId);
            if (r) {
                r.error = msg.params.errorText;
                r.failed = true;
            }
        }
    };

    await send('Network.enable');
    await send('Page.enable');
    console.log('Navigating to homepage...');
    const t0 = Date.now();
    await send('Page.navigate', { url: 'https://qai8358l95-staging.onrocket.site/' });

    // Wait 25 seconds
    await new Promise(r => setTimeout(r, 25000));

    console.log('--- SLOW OR UNFINISHED REQUESTS (> 3s or pending) ---');
    for (const [id, r] of requests.entries()) {
        if (!r.finished || r.duration > 3) {
            console.log(`URL: ${r.url.slice(0, 100)} | Finished: ${r.finished} | Duration: ${r.duration ? r.duration.toFixed(2) + 's' : 'PENDING'} | Error: ${r.error || 'none'}`);
        }
    }

    ws.close();
    chrome.kill();
}

debugNetwork();

