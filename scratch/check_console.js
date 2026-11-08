const { spawn } = require('child_process');

async function checkConsole() {
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

    const logs = [];
    ws.onmessage = (evt) => {
        const msg = JSON.parse(evt.data);
        if (msg.method === 'Runtime.consoleAPICalled') {
            logs.push({ type: msg.params.type, args: msg.params.args.map(a => a.value || a.description) });
        } else if (msg.method === 'Runtime.exceptionThrown') {
            logs.push({ type: 'EXCEPTION', text: msg.params.exceptionDetails.text, exception: msg.params.exceptionDetails.exception });
        } else if (msg.method === 'Log.entryAdded') {
            logs.push({ type: 'LOG', entry: msg.params.entry });
        }
    };

    await send('Runtime.enable');
    await send('Log.enable');
    await send('Page.enable');

    console.log('Navigating to homepage...');
    await send('Page.navigate', { url: 'https://qai8358l95-staging.onrocket.site/' });

    await new Promise(r => setTimeout(r, 10000));

    console.log('--- BROWSER CONSOLE / EXCEPTIONS ---');
    console.log(JSON.stringify(logs, null, 2));

    ws.close();
    chrome.kill();
}

checkConsole();

