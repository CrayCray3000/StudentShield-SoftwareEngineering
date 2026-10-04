import fs from 'node:fs';
import path from 'node:path';
import {fileURLToPath} from 'node:url';
const dir=path.dirname(fileURLToPath(import.meta.url));
const mode=process.argv[2]||'inspect';
const files=fs.readdirSync(dir).filter(f=>/^\d.*\.json$/.test(f)&&!f.includes('-batches'));
if(mode==='inspect'){
 console.log(JSON.stringify(files.map(f=>{const d=JSON.parse(fs.readFileSync(path.join(dir,f)));return {file:f,nodes:d.nodes.length,canvases:d.nodes.filter(n=>n.canvas||n.tag==='canvas').map(n=>({id:n.id,b:n.b,data:!!n.data,name:n.name})),svg:d.nodes.filter(n=>n.svg).map(n=>({id:n.id,length:n.svg.length})).sort((a,b)=>b.length-a.length).slice(0,3),modal:d.nodes.filter(n=>/modal/i.test(n.name)).map(n=>({id:n.id,parent:n.parent,b:n.b,name:n.name})).slice(-8)};})));
 process.exit();
}
let renderer=fs.readFileSync(path.join(dir,'render-figma.js'),'utf8');
renderer=renderer.replace("required.set('Noto Color Emoji/Regular',{family:'Noto Color Emoji',style:'Regular'});",'');
renderer=renderer.replace(/  const emoji=.*?return n;/s,'  return n;');
renderer=renderer.replace(/node.topLeftRadius=number\(s.borderTopLeftRadius\);node.topRightRadius=number\(s.borderTopRightRadius\);node.bottomRightRadius=number\(s.borderBottomRightRadius\);node.bottomLeftRadius=number\(s.borderBottomLeftRadius\);/,"const r=v=>String(v).includes('%')?Math.min(node.width,node.height)*number(v)/100:number(v);node.topLeftRadius=r(s.borderTopLeftRadius);node.topRightRadius=r(s.borderTopRightRadius);node.bottomRightRadius=r(s.borderBottomRightRadius);node.bottomLeftRadius=r(s.borderBottomLeftRadius);");
renderer=renderer.replace("let hash='d3d134e63f72aec0558d746d297cf499d7a12a7a';","let hash='cf5414dd42f52ae12632d455ecd04185aadb32df';");
renderer=renderer.replace("if(data.canvas){if(!data.data)throw new Error('Missing rendered chart '+data.name);const bin=figma.base64Decode(data.data.split(',')[1]);hash=figma.createImage(bin).hash;}","if(data.canvas){if(data.data){const bin=figma.base64Decode(data.data.split(',')[1]);hash=figma.createImage(bin).hash;}else if(data.name==='riskPieChart')hash='39665bc99899baee00efdf88e2319beac508942d';else throw new Error('Missing rendered chart '+data.name);}");
renderer=renderer.replace("n.fills=[{type:'IMAGE',imageHash:hash,scaleMode:data.fit==='cover'?'FILL':'FIT'}];","setBox(n,s);n.fills=[{type:'IMAGE',imageHash:hash,scaleMode:data.fit==='cover'?'FILL':'FIT'}];");
renderer=renderer.replace("n.textAutoResize='HEIGHT';n.resize(Math.max(1,size[2]+2),Math.max(1,size[3]));", "n.textAutoResize=single?'WIDTH_AND_HEIGHT':'HEIGHT';if(!single)n.resize(Math.max(1,size[2]+2),Math.max(1,size[3]));");
renderer=renderer.replace('\n}\nfunction place',`\n  if('children' in node){
  const sides=['Top','Right','Bottom','Left'],ws=sides.map(x=>number(s['border'+x+'Width'])),cs=sides.map(x=>s['border'+x+'Color']);
  if(ws.some(x=>x>0)){
   if(ws.every(x=>x===ws[0])&&cs.every(x=>x===cs[0])){node.strokes=[paint(cs[0])];node.strokeWeight=ws[0];}
   else{node.strokes=[];for(let i=0;i<4;i++){if(!ws[i]||!color(cs[i]).a)continue;const edge=track(figma.createRectangle());node.appendChild(edge);if(node.layoutMode!=='NONE')edge.layoutPositioning='ABSOLUTE';edge.name='Source border / '+sides[i];edge.resize(i%2?ws[i]:node.width,i%2?node.height:ws[i]);edge.x=i===1?node.width-ws[i]:0;edge.y=i===2?node.height-ws[i]:0;edge.fills=[paint(cs[i])];}}
  }
  }
}\nfunction place`);
renderer=renderer.split('\n').filter(l=>!l.trim().startsWith('//')).map(l=>l.trim()).join('\n');
fs.writeFileSync(path.join(dir,'render-ready.js'),renderer);
const defs={backgroundColor:'rgba(0, 0, 0, 0)',backgroundImage:'none',opacity:'1',boxShadow:'none',filter:'none',backdropFilter:'none',transform:'none',fontStyle:'normal',letterSpacing:'normal',textTransform:'none',textAlign:'start',overflow:'visible',overflowX:'visible',overflowY:'visible',position:'static',zIndex:'auto',display:'block',paddingLeft:'0px',paddingRight:'0px',paddingTop:'0px',paddingBottom:'0px'};
for(const f of files){
 const d=JSON.parse(fs.readFileSync(path.join(dir,f),'utf8'));
 const childLookup=new Map();for(const n of d.nodes){if(!childLookup.has(n.parent))childLookup.set(n.parent,[]);childLookup.get(n.parent).push(n);}
 for(const n of d.nodes){if(/animate-slide-up|animate-fade-in/.test(n.name||'')){const s=d.styles[n.s];const m=s.transform?.match(/matrix\(([^)]+)\)/);const vals=m?m[1].split(',').map(Number):[1,0,0,1,0,0];const dx=vals[4]||0,dy=vals[5]||0;const shift=q=>{q.b[0]-=dx;q.b[1]-=dy;for(const c of childLookup.get(q.id)||[])shift(c);};shift(n);d.styles.push({...s,opacity:'1',transform:'none'});n.s=d.styles.length-1;}}
 d.styles=d.styles.map(s=>Object.fromEntries(Object.entries(s).filter(([k,v])=>defs[k]!==v&&!(/Width$|Radius$/.test(k)&&v==='0px')&&!(/Color$/.test(k)&&k!=='color'&&k!=='backgroundColor'&&s[k.replace('Color','Width')]==='0px')&&!['display','textTransform','textAlign'].includes(k))));
 d.nodes=d.nodes.map(n=>{let q={...n,name:String(n.name).slice(0,115)};delete q.rects;delete q.emojis;delete q.document;delete q.href;delete q.src;if(q.scroll&&q.scroll[0]===0&&q.scroll[1]===0&&q.scroll[2]<=q.b[2]+2&&q.scroll[3]<=q.b[3]+2)delete q.scroll;
 if(q.svg)q.svg=q.svg.replace(/\s(?:class|data-[\w-]+|aria-[\w-]+)="[^"]*"/g,'');return q;});
 // A control and its descendants must stay together because it becomes an instance.
 const byId=new Map(d.nodes.map(n=>[n.id,n]));const children=new Map();for(const n of d.nodes){if(!children.has(n.parent))children.set(n.parent,[]);children.get(n.parent).push(n);}
 const blocks=[];function descend(n){if(n.id===0){for(const c of children.get(0)||[])descend(c);return;}if(['button','input','textarea','select'].includes(n.tag)){const group=[];const all=q=>{group.push(q);for(const c of children.get(q.id)||[])all(c);};all(n);blocks.push(group);}else {blocks.push([n]);for(const c of children.get(n.id)||[])descend(c);}}descend(d.nodes[0]);
 const pack=nodes=>{const inds=[...new Set(nodes.flatMap(n=>[n.s,n.placeholderStyle].filter(x=>x!==undefined)))];const sm=new Map(inds.map((i,j)=>[i,j]));return {...d,nodes:nodes.map(n=>({...n,s:sm.get(n.s),...(n.placeholderStyle!==undefined?{placeholderStyle:sm.get(n.placeholderStyle)}:{})})),styles:inds.map(i=>d.styles[i])};};
 const batches=[];let ns=[d.nodes[0]];for(const block of blocks){const candidate=[...ns,...block];if(JSON.stringify(pack(candidate)).length>20500&&ns.length>1){batches.push(pack(ns));ns=[d.nodes[0],...block];}else ns=candidate;}if(ns.length>1)batches.push(pack(ns));
 fs.writeFileSync(path.join(dir,f.replace('.json','-batches.json')),JSON.stringify(batches));
}
 console.log(JSON.stringify({renderer:renderer.length,batches:files.map(f=>({file:f,batches:JSON.parse(fs.readFileSync(path.join(dir,f.replace('.json','-batches.json')))).length}))}));
