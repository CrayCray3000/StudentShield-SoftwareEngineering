import fs from 'node:fs';import path from 'node:path';import {fileURLToPath} from 'node:url';
const dir=path.dirname(fileURLToPath(import.meta.url));
const task=process.argv[2];const batch=Number(process.argv[3]||0);
const targets={'07':'771:700','10':'771:706','11':'771:708','12':'771:710','15':'771:716','09':'771:704','13':'771:712','18':'771:722','19':'771:724','menu':'771:700'};
const captures={'07':'07-admin-overview','10':'10-admin-registrations','11':'11-admin-audit','12':'12-admin-users','15':'15-admin-case-top','09':'09-overlay','13':'13-overlay','18':'18-overlay','19':'19-overlay','menu':'07-menu-overlay'};
const state=JSON.parse(fs.readFileSync(path.join(dir,'figma-state.json')));
if(task==='persist'){const input=JSON.parse(fs.readFileSync(0,'utf8'));let k=process.argv[3];let s=state.stages[k]||{};s={...s,...input,mapping:{...s.mapping,...input.mapping},batch:process.argv[4]};state.stages[k]=s;fs.writeFileSync(path.join(dir,'figma-state.json'),JSON.stringify(state,null,2));console.log('saved');process.exit();}
let cap=JSON.parse(fs.readFileSync(path.join(dir,captures[task]+'-batches.json')))[batch];
if(!cap){console.log(JSON.stringify({complete:true}));process.exit();}
const source=JSON.parse(fs.readFileSync(path.join(dir,captures[task]+'.json')));
const anchors={};let s=state.stages[task];const ids=new Set(cap.nodes.map(n=>n.id));const needed=new Set(cap.nodes.filter(n=>n.parent>=0&&!ids.has(n.parent)).map(n=>String(n.parent)));if(s?.mapping)for(const [id,fid] of Object.entries(s.mapping)){if(!needed.has(id))continue;const n=source.nodes.find(n=>n.id===Number(id));if(n)anchors[id]={id:fid,b:n.b,scroll:n.scroll};}
const iconAssets={};for(const n of cap.nodes.filter(n=>n.icon)){const name=n.icon.match(/\bfa-([\w-]+)/)?.[1];const type=n.icon.includes('far ')?'regular':'solid';iconAssets[type+'/'+name]=fs.readFileSync(path.join(dir,'icons',name+'.svg'),'utf8');}
const code='const CAPTURE='+JSON.stringify(cap)+';const SCREEN_NAME='+JSON.stringify(task+' — '+(source.title||captures[task]))+';const FRAME_ID='+JSON.stringify(targets[task])+';const ICON_ASSETS='+JSON.stringify(iconAssets)+';const MODAL_SCREEN='+(['09','13','18','19'].includes(task))+';const PARENT_ANCHORS='+JSON.stringify(anchors)+';'+(batch&&s?'const STAGE_ID='+JSON.stringify(s.stageId)+';const BODY_ID='+JSON.stringify(s.bodyId)+';':'')+fs.readFileSync(path.join(dir,'render-ready.js'),'utf8');
console.log(JSON.stringify({code,length:code.length,batch,total:JSON.parse(fs.readFileSync(path.join(dir,captures[task]+'-batches.json'))).length}));
