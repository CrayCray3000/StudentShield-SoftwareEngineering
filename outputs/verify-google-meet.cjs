// Offline UI regression harness. Uses the real card and meeting handlers with mocked HTTP responses.
const fs = require('fs');
const vm = require('vm');
const assert = require('assert/strict');
const {chromium} = require('C:/Users/drvon/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/playwright');
const root = 'D:/laragon/www/student shield/web-app-ci4/';
const read = p => fs.readFileSync(root + p, 'utf8');
const strip = s => s.replace(/<\?[\s\S]*?\?>/g, '10');
(async () => {
    for (const file of ['case_summary.php', 'student_dashboard.php', 'vent_form.php']) {
        const source = read('app/Views/' + file);
        for (const match of source.matchAll(/<script\b[^>]*>([\s\S]*?)<\/script>/g)) new vm.Script(strip(match[1]), {filename: file});
        assert(!/Jitsi|currentRoomName|meet\.jit\.si/.test(source), file + ' retains legacy integration');
    }
    const browser = await chromium.launch({headless:true});
    try {
        const page = await browser.newPage({viewport:{width:375,height:812}});
        const errors = [];
        page.on('pageerror', e => errors.push(e.message));
        let state = {status:'success',has_meeting:false,has_request:false};
        let failCancel = false;
        const calls = [];
        await page.route('http://meet-test.local/**', async route => {
            const url = route.request().url();
            calls.push({url, method:route.request().method(), headers:route.request().headers()});
            if (url.endsWith('/request')) state.has_request = true;
            if (url.endsWith('/cancel') && !failCancel) state.has_request = false;
            const fail = url.endsWith('/cancel') && failCancel;
            await route.fulfill({status:fail ? 409 : 200,contentType:'application/json',body:JSON.stringify(fail ? {status:'error',message:'Cannot cancel'} : state)});
        });
        await page.clock.install();
        const card = strip(read('app/Views/partials/student_meet_card.php')).replace(/<script[\s\S]*?<\/script>/g,'')
            .replace('data-status-url="10"','data-status-url="http://meet-test.local/status"')
            .replace('data-request-url="10"','data-request-url="http://meet-test.local/request"')
            .replace('data-cancel-url="10"','data-cancel-url="http://meet-test.local/cancel"');
        await page.setContent('<meta name="csrf-token" content="test-token">' + card);
        await page.addScriptTag({content:read('public/js/student-meet.js')});
        await page.locator('[data-meet-request]').waitFor({state:'visible'});
        await page.locator('[data-meet-request]').click();
        await page.locator('[data-meet-cancel]').waitFor({state:'visible'});
        failCancel = true;
        await page.locator('[data-meet-cancel]').click();
        await page.locator('[data-meet-error]').waitFor({state:'visible'});
        assert(await page.locator('[data-meet-cancel]').isVisible());
        failCancel = false;
        await page.locator('[data-meet-cancel]').click();
        await page.locator('[data-meet-request]').waitFor({state:'visible'});
        state = {status:'success',has_meeting:true,has_request:false,join_url:'http://meet-test.local/join/1'};
        await page.clock.fastForward(8000);
        await page.locator('[data-meet-join]').waitFor({state:'visible'});
        assert.equal(await page.locator('[data-meet-join]').getAttribute('href'),state.join_url);
        for (const width of [320,375,1280]) {
            await page.setViewportSize({width,height:812});
            assert(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth));
        }
        await page.screenshot({path:'outputs/google-meet-card-verified.png'});
        state = {status:'success',has_meeting:false,has_request:false};
        await page.clock.fastForward(8000);
        await page.locator('[data-meet-request]').waitFor({state:'visible'});
        assert.equal(await page.locator('[data-meet-join]').getAttribute('href'),null);
        assert(calls.filter(c=>c.method==='POST').every(c=>c.headers['x-csrf-token']==='test-token'));
        assert.deepEqual(errors,[]);
        console.log('PASS: student idle/request/cancel failure/cancel success/active/ended; CSRF; 320/375/1280px no horizontal overflow; all view JavaScript parses.');
        const source = read('app/Views/case_summary.php');
        const statusJs = source.slice(source.indexOf('        let currentHostJoinUrl'),source.indexOf('        // Load Page Data'));
        const actionJs = source.slice(source.indexOf('        function startVideoMeeting()'),source.indexOf('        // Bootstrap load'));
        await page.close();
        const host = await browser.newPage();
        host.on('pageerror', e => errors.push(e.message));
        await host.route('http://host-test.local/**', route => route.fulfill({contentType:'text/html',body:'<meta name="csrf-token" content="test-token">'}));
        await host.goto('http://host-test.local/');
        await host.setContent('<style>.hidden{display:none}</style>' + ['pendingRequestBadge','btnJoinGoogleMeeting','btnStartVideoMeeting','btnEndVideoMeeting'].map(id=>`<button id="${id}">${id}</button>`).join('') + source.slice(source.indexOf('    <!-- Google Meet publication dialog -->'),source.indexOf('    <script>',source.indexOf('    <!-- Google Meet publication dialog -->'))));
        await host.evaluate(() => {
            window.studentId=10;
            window.setInterval=()=>0;
            window.confirm=()=>true;
            window.alert=()=>{};
            window.open=(url)=>window.openedUrl=url;
            window.meetingState={status:'success',has_meeting:true,owned:true,join_url:'/meeting/join/1'};
            window.fetch=async (url, options={}) => {
                if (options.method==='POST') window.meetingState={status:'success',has_meeting:false,owned:false,message:'Closed'};
                return {ok:true,json:async()=>window.meetingState};
            };
        });
        await host.addScriptTag({content:statusJs+actionJs});
        await host.locator('#btnJoinGoogleMeeting').waitFor({state:'visible'});
        await host.evaluate(()=>joinGoogleMeeting());
        assert.equal(await host.evaluate(()=>window.openedUrl),'/meeting/join/1');
        await host.evaluate(()=>endVideoMeeting());
        await host.locator('#btnJoinGoogleMeeting').waitFor({state:'hidden'});
        await host.evaluate(()=>{window.meetingState={status:'success',has_meeting:true,owned:false};checkPendingRequest();});
        await host.waitForFunction(()=>document.getElementById('btnStartVideoMeeting').disabled);
        assert(await host.locator('#btnEndVideoMeeting').isHidden());
        assert.deepEqual(errors,[]);
        console.log('PASS: host Join opens synchronously; End closes access; another host cannot see Join/End and cannot publish over an active session.');
    } finally { await browser.close(); }
})().catch(e=>{console.error(e);process.exitCode=1;});
