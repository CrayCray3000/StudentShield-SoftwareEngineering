const fs = require('fs');
const vm = require('vm');
const assert = require('assert/strict');
const root = 'web-app-ci4/';
const read = p => fs.readFileSync(root+p,'utf8');
const strip = s => s.replace(/<\?[\s\S]*?\?>/g,'10');
function element() {
    const classes = new Set();
    return {hidden:false,disabled:false,textContent:'',attrs:{},handlers:{},
        classList:{add:x=>classes.add(x),remove:x=>classes.delete(x),contains:x=>classes.has(x)},
        addEventListener(name,fn){this.handlers[name]=fn;},removeAttribute(name){delete this.attrs[name];if(name==='href')delete this.href;},
        focus(){}, reset(){}};
}
const flush = () => new Promise(resolve=>setImmediate(resolve));
(async()=>{
    for (const file of ['case_summary.php','student_dashboard.php','vent_form.php']) {
        const src=read('app/Views/'+file);
        for (const m of src.matchAll(/<script\b[^>]*>([\s\S]*?)<\/script>/g)) new vm.Script(strip(m[1]),{filename:file});
        assert(!/Jitsi|currentRoomName|meet\.jit\.si/.test(src));
    }
    const parts=Object.fromEntries(['status','join','request','cancel','error'].map(k=>[k,element()]));
    let state={status:'success',has_meeting:false,has_request:false}, fail=false, poll;
    const calls=[];
    const card={dataset:{statusUrl:'status',requestUrl:'request',cancelUrl:'cancel'},querySelector:s=>parts[s.match(/data-meet-(\w+)/)[1]]};
    const context={document:{querySelector:s=>s==='.student-meet-card'?card:{content:'csrf'}},setInterval:fn=>poll=fn,
        fetch:async(url,options)=>{calls.push([url,options]);if(fail) return {ok:false,json:async()=>({status:'error',message:'Failed'})};
            if(url==='request')state.has_request=true;if(url==='cancel')state.has_request=false;
            return {ok:true,json:async()=>state};}};
    vm.runInNewContext(read('public/js/student-meet.js'),context);
    await flush(); assert.equal(parts.request.hidden,false);
    await parts.request.handlers.click(); assert.equal(parts.cancel.hidden,false);
    fail=true;await parts.cancel.handlers.click();assert.equal(parts.error.hidden,false);assert.equal(parts.cancel.hidden,false);
    fail=false;await parts.cancel.handlers.click();assert.equal(parts.request.hidden,false);
    state={status:'success',has_meeting:true,join_url:'/meeting/join/1'};await poll();assert.equal(parts.join.href,state.join_url);assert.equal(parts.join.hidden,false);
    state={status:'success',has_meeting:false};await poll();assert.equal(parts.join.hidden,true);assert.equal(parts.join.href,undefined);
    assert(calls.filter(([,o])=>o.method==='POST').every(([,o])=>o.headers['X-CSRF-TOKEN']==='csrf'));
    console.log('PASS student: request, failed cancellation, successful cancellation, active link, ended link removal, CSRF.');
    const src=read('app/Views/case_summary.php');
    const code=src.slice(src.indexOf('        let currentHostJoinUrl'),src.indexOf('        // Load Page Data'))+src.slice(src.indexOf('        function startVideoMeeting()'),src.indexOf('        // Bootstrap load'));
    const nodes=Object.fromEntries(['pendingRequestBadge','btnJoinGoogleMeeting','btnStartVideoMeeting','btnEndVideoMeeting','googleMeetError','googleMeetModal','googleMeetLink','googleMeetForm','publishGoogleMeetButton'].map(k=>[k,element()]));
    let hostState={status:'success',has_meeting:true,owned:true,join_url:'/meeting/join/1'},opened;
    const host={studentId:10,document:{getElementById:id=>nodes[id],querySelector:()=>({content:'csrf'})},setInterval(){},confirm:()=>true,alert(){},window:{open:url=>opened=url},URLSearchParams,FormData:class { [Symbol.iterator](){return [][Symbol.iterator]();}},
        fetch:async(url,options={})=>{if(options.method==='POST')hostState={status:'success',has_meeting:false,message:'Closed'};return {ok:true,json:async()=>hostState};}};
    vm.createContext(host);vm.runInContext(code,host);await flush();host.joinGoogleMeeting();assert.equal(opened,'/meeting/join/1');
    await host.endVideoMeeting();await flush();assert(nodes.btnJoinGoogleMeeting.classList.contains('hidden'));
    hostState={status:'success',has_meeting:true,owned:false};host.checkPendingRequest();await flush();assert(nodes.btnStartVideoMeeting.disabled);assert(nodes.btnEndVideoMeeting.classList.contains('hidden'));
    console.log('PASS host: synchronous Join, End handler, other-host controls hidden and publication disabled.');
    console.log('PASS all three PHP view script blocks parse. These are logic checks, not visual/browser tests.');
})().catch(e=>{console.error(e);process.exitCode=1;});
