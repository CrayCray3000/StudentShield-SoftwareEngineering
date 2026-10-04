// The calling script defines CAPTURE, SCREEN_NAME, FRAME_ID and ICON_ASSETS.
// Geometry and content come exclusively from the measured local browser capture.
await figma.setCurrentPageAsync(await figma.getNodeByIdAsync('771:684'));
const page = figma.currentPage;
const made = [], mutated = [], warnings = [], mapping = {}, stylesMade = [];
const track = n => { made.push(n.id); return n; };
const recordTree = n => { made.push(n.id); if ('children' in n) for (const c of n.children) recordTree(c); };
const number = v => parseFloat(v) || 0;
const color = s => {
  if (!s || s === 'transparent') return {r:0,g:0,b:0,a:0};
  if (s[0] === '#') { let x=s.slice(1); if(x.length===3)x=x.split('').map(c=>c+c).join(''); return {r:parseInt(x.slice(0,2),16)/255,g:parseInt(x.slice(2,4),16)/255,b:parseInt(x.slice(4,6),16)/255,a:x.length===8?parseInt(x.slice(6,8),16)/255:1}; }
  const m=s.match(/rgba?\(([^)]+)\)/); if (!m) return {r:0,g:0,b:0,a:0};
  const a=m[1].split(/[,\s/]+/).filter(Boolean).map(Number);return {r:a[0]/255,g:a[1]/255,b:a[2]/255,a:a.length>3?a[3]:1};
};
const solid = s => { const c=color(s);return {type:'SOLID',color:{r:c.r,g:c.g,b:c.b},opacity:c.a}; };
const available = await figma.listAvailableFontsAsync();
const font = s => {
  let family = s.fontFamily?.includes('Outfit')?'Outfit':s.fontFamily?.includes('monospace')?'Roboto Mono':'Inter';
  const w=number(s.fontWeight)||400; let style=({100:'Thin',200:'Extra Light',300:'Light',400:'Regular',500:'Medium',600:'Semi Bold',700:'Bold',800:'Extra Bold',900:'Black'})[w]||'Regular';
  if (family==='Outfit')style=style.replace(/ /g,'');
  if(family==='Roboto Mono')style=style.replace(/ /g,'');
  if(s.fontStyle==='italic')style=style==='Regular'?'Italic':style+' Italic';
  if(!available.some(f=>f.fontName.family===family&&f.fontName.style===style)){style='Regular';}
  return {family,style};
};
const required=new Map();for(const s of CAPTURE.styles){const f=font(s);required.set(f.family+'/'+f.style,f);}
required.set('Noto Color Emoji/Regular',{family:'Noto Color Emoji',style:'Regular'});
for(const f of required.values())await figma.loadFontAsync(f);

// Reuse the scoped token collection, compatible styles and control components.
let collection=(await figma.variables.getLocalVariableCollectionsAsync()).find(c=>c.name==='StudentShield / Current website');
if(!collection){collection=figma.variables.createVariableCollection('StudentShield / Current website');collection.renameMode(collection.defaultModeId,'Current');}
const variables=(await figma.variables.getLocalVariablesAsync()).filter(v=>v.variableCollectionId===collection.id);
const tokenDefs=[['color/primary','#0F8A3A','--school-green'],['color/surface','#FFFFFF','--form-surface'],['color/text','#24352B','--form-text'],['color/border','#80988B','--form-border']];
const tokenMap=new Map();
for(const [name,value,css] of tokenDefs){let p=variables.find(q=>q.name==='primitives/'+value.slice(1));if(!p){p=figma.variables.createVariable('primitives/'+value.slice(1),collection,'COLOR');p.setValueForMode(collection.defaultModeId,color(value));p.scopes=[];p.setVariableCodeSyntax('WEB','var('+css+')');variables.push(p);}let v=variables.find(q=>q.name===name);if(!v){v=figma.variables.createVariable(name,collection,'COLOR');v.setValueForMode(collection.defaultModeId,{type:'VARIABLE_ALIAS',id:p.id});v.scopes=['FRAME_FILL','SHAPE_FILL','TEXT_FILL','STROKE_COLOR'];v.setVariableCodeSyntax('WEB','var('+css+')');variables.push(v);}tokenMap.set(value.toLowerCase(),v);}
const variableIds=variables.map(v=>v.id);
const paint = s => {let p=solid(s);const c=p.color;const hex='#'+[c.r,c.g,c.b].map(x=>Math.round(x*255).toString(16).padStart(2,'0')).join('');const v=tokenMap.get(hex);if(v&&p.opacity===1)p=figma.variables.setBoundVariableForPaint(p,'color',v);return p;};
const textStyles=await figma.getLocalTextStylesAsync(),effectStyles=await figma.getLocalEffectStylesAsync();
const split = str => {const out=[];let start=0,depth=0;for(let i=0;i<str.length;i++){if(str[i]==='(')depth++;if(str[i]===')')depth--;if(str[i]===','&&depth===0){out.push(str.slice(start,i).trim());start=i+1;}}out.push(str.slice(start).trim());return out;};
const xml = s => s.replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;');
function gradientSvg(image,w,h) {
  const layers=split(image), defs=[],rects=[];let ix=0;
  for(const layer of layers.reverse()){
    if(!/gradient\(/.test(layer))continue;
    const radial=layer.startsWith('radial-'),args=split(layer.slice(layer.indexOf('(')+1,-1));let heading='';
    if(!/^(rgba?|#)/.test(args[0]))heading=args.shift();
    const stops=args.map((a,i)=>{let m=a.match(/^(rgba?\([^)]+\)|#[\da-fA-F]+)\s*(.*)$/);if(!m)return '';let c=color(m[1]),offset=m[2].endsWith('%')?number(m[2])/100:m[2].endsWith('px')?number(m[2])/Math.max(w,h):i/Math.max(args.length-1,1);return '<stop offset="'+Math.max(0,Math.min(1,offset))+'" stop-color="rgb('+[c.r,c.g,c.b].map(v=>Math.round(v*255)).join(',')+')" stop-opacity="'+c.a+'"/>';}).join('');
    const id='g'+ix++;
    if(radial){let m=heading.match(/at\s+([-\d.]+)%\s+([-\d.]+)%/),cx=m?number(m[1])/100:.5,cy=m?number(m[2])/100:.5;defs.push('<radialGradient id="'+id+'" cx="'+cx+'" cy="'+cy+'" r=".75">'+stops+'</radialGradient>');}
    else {let deg=heading.includes('deg')?number(heading):heading.includes('right')?90:heading.includes('left')?270:heading.includes('top')?0:180;let a=deg*Math.PI/180,dx=Math.sin(a),dy=-Math.cos(a),len=Math.abs(w*dx)+Math.abs(h*dy);let x1=.5-dx*len/(2*w),y1=.5-dy*len/(2*h),x2=1-x1,y2=1-y1;defs.push('<linearGradient id="'+id+'" x1="'+x1+'" y1="'+y1+'" x2="'+x2+'" y2="'+y2+'">'+stops+'</linearGradient>');}
    rects.push('<rect width="'+w+'" height="'+h+'" fill="url(#'+id+')"/>');
  }
  return '<svg xmlns="http://www.w3.org/2000/svg" width="'+w+'" height="'+h+'"><defs>'+defs.join('')+'</defs>'+rects.join('')+'</svg>';
}
function addSvg(svg,parent,name,w,h){
  let m=svg.match(/^<svg\b([^>]*)>/);if(m){let attrs=m[1].replace(/\s(?:width|height)="[^"]*"/g,'');svg=svg.replace(m[0],'<svg'+attrs+' width="'+w+'" height="'+h+'">');}
  const n=figma.createNodeFromSvg(svg);recordTree(n);parent.appendChild(n);if(parent.layoutMode&&parent.layoutMode!=='NONE')n.layoutPositioning='ABSOLUTE';n.name=name;n.resize(Math.max(w,.01),Math.max(h,.01));return n;
}
function setBox(node,s) {
  node.fills=color(s.backgroundColor).a?[paint(s.backgroundColor)]:[];
  if('cornerRadius' in node){node.topLeftRadius=number(s.borderTopLeftRadius);node.topRightRadius=number(s.borderTopRightRadius);node.bottomRightRadius=number(s.borderBottomRightRadius);node.bottomLeftRadius=number(s.borderBottomLeftRadius);}
  const border=number(s.borderTopWidth);if(border){node.strokes=[paint(s.borderTopColor)];node.strokeWeight=border;node.strokeAlign='INSIDE';}
  if('strokeTopWeight' in node && s.borderBottomWidth){node.strokeTopWeight=number(s.borderTopWidth);node.strokeRightWeight=number(s.borderRightWidth);node.strokeBottomWeight=number(s.borderBottomWidth);node.strokeLeftWeight=number(s.borderLeftWidth);const clr=number(s.borderBottomWidth)?s.borderBottomColor:number(s.borderRightWidth)?s.borderRightColor:s.borderTopColor;node.strokes=(node.strokeTopWeight+node.strokeBottomWeight+node.strokeRightWeight+node.strokeLeftWeight)>0?[paint(clr)]:[];}
  node.opacity=s.opacity===undefined?1:Number(s.opacity);
  if(String(node.name).includes('mix-blend-screen'))node.blendMode='SCREEN';
  const effects=[];
  if(s.boxShadow&&s.boxShadow!=='none')for(const sh of split(s.boxShadow)){
    const m=sh.match(/rgba?\([^)]+\)/);if(!m)continue;const nums=sh.replace(m[0],'').replace('inset','').trim().split(/\s+/).map(number);const c=color(m[0]);if(c.a>0)effects.push({type:sh.includes('inset')?'INNER_SHADOW':'DROP_SHADOW',color:c,offset:{x:nums[0]||0,y:nums[1]||0},radius:Math.max(0,nums[2]||0),spread:nums[3]||0,visible:true,blendMode:'NORMAL'});
  }
  const filter=s.filter|| (String(node.name).includes('glow-blob')?'blur(90px)':'none');const blur=filter.match(/blur\(([^)]+)\)/);if(blur)effects.push({type:'LAYER_BLUR',radius:number(blur[1]),visible:true});
  const backdrop=(s.backdropFilter||'').match(/blur\(([^)]+)\)/);if(backdrop)effects.push({type:'BACKGROUND_BLUR',radius:number(backdrop[1]),visible:true});
  node.effects=effects;
  if(effects.length){let name='Current website / '+JSON.stringify(effects);let es=effectStyles.find(q=>q.name===name);if(!es){es=figma.createEffectStyle();es.name=name;es.effects=effects;effectStyles.push(es);stylesMade.push(es.id);}node.effectStyleId=es.id;}
  if(s.backgroundImage && s.backgroundImage!=='none'){
    if(String(node.name).includes('bg-grid')){
      const grid=track(figma.createVector());node.appendChild(grid);grid.layoutPositioning='ABSOLUTE';grid.name='40 px background grid';grid.fills=[];grid.strokes=[{type:'SOLID',color:{r:1,g:1,b:1},opacity:.06}];grid.strokeWeight=1;let path='';for(let x=0;x<node.width;x+=40)path+='M '+x+' 0 L '+x+' '+node.height+' ';for(let y=0;y<node.height;y+=40)path+='M 0 '+y+' L '+node.width+' '+y+' ';grid.vectorPaths=[{windingRule:'NONZERO',data:path}];grid.x=0;grid.y=0;
    }else {const bg=addSvg(gradientSvg(s.backgroundImage,node.width,node.height),node,'CSS background',node.width,node.height);bg.x=0;bg.y=0;}
  }
}
function place(node,parent,b,pb){parent.appendChild(node);if(parent.layoutMode&&parent.layoutMode!=='NONE')node.layoutPositioning='ABSOLUTE';node.x=b[0]-pb[0];node.y=b[1]-pb[1];}
async function textNode(data,parent,pb,styleOverride){
  const s=styleOverride||CAPTURE.styles[data.s],n=track(figma.createText());n.name=data.text.slice(0,85);n.fontName=font(s);n.fontSize=number(s.fontSize)||14;n.characters=data.text;n.fills=[paint(s.color)];n.lineHeight=s.lineHeight==='normal'?{unit:'AUTO'}:{unit:'PIXELS',value:number(s.lineHeight)||n.fontSize*1.5};n.letterSpacing={unit:'PIXELS',value:number(s.letterSpacing)};n.textAlignHorizontal='LEFT';
  const size=data.b;let lh=s.lineHeight==='normal'?size[3]:number(s.lineHeight);const single=size[3] < (number(s.fontSize)||14)*1.5;
  const tsname='Current website / '+n.fontName.family+'/'+n.fontName.style+'/'+n.fontSize+'/'+(lh||'auto')+'/'+number(s.letterSpacing);
  let ts=textStyles.find(q=>q.name===tsname);if(!ts){ts=figma.createTextStyle();ts.name=tsname;ts.fontName=n.fontName;ts.fontSize=n.fontSize;ts.lineHeight=n.lineHeight;ts.letterSpacing=n.letterSpacing;textStyles.push(ts);stylesMade.push(ts.id);}n.textStyleId=ts.id;
  n.textAutoResize='HEIGHT';n.resize(Math.max(1,size[2]+2),Math.max(1,size[3]));place(n,parent,size,pb);
  if(single && lh>size[3])n.y-=(lh-size[3])/2;
  else if(!single&&lh>0)n.y-=Math.max(0,(lh-(number(s.fontSize)||14)*1.21)/2);
  // Browser fallback emoji remain real emoji, rather than substitute UI icons.
  const emoji=/[\u{1F000}-\u{1FAFF}\u2600-\u27BF]/gu;let match;while((match=emoji.exec(data.text))){let end=match.index+match[0].length;if(data.text[end]==='\uFE0F')end++;n.setRangeFontName(match.index,end,{family:'Noto Color Emoji',style:'Regular'});}
  return n;
}
const target=await figma.getNodeByIdAsync(FRAME_ID);
const resume=typeof STAGE_ID!=='undefined'&&STAGE_ID;
const oldStage=page.children.find(n=>n.name==='SYNC STAGE / '+SCREEN_NAME);if(oldStage&&!resume)throw new Error('Existing stage '+oldStage.id+' must be inspected before retry.');
const root=resume?await figma.getNodeByIdAsync(STAGE_ID):track(figma.createAutoLayout());
if(!resume){root.name='SYNC STAGE / '+SCREEN_NAME;page.appendChild(root);root.resize(CAPTURE.viewport[0],CAPTURE.viewport[1]);root.x=4500;root.y=target.y;root.layoutMode='VERTICAL';root.primaryAxisSizingMode='FIXED';root.counterAxisSizingMode='FIXED';root.fills=[solid('#FFFFFF')];root.clipsContent=true;root.overflowDirection=MODAL_SCREEN?'NONE':'VERTICAL';}
else mutated.push(root.id);
const bodyData=CAPTURE.nodes[0];const body=resume?await figma.getNodeByIdAsync(BODY_ID):track(figma.createAutoLayout());
if(!resume){body.name='Scrollable page content';body.layoutMode='VERTICAL';body.primaryAxisSizingMode='FIXED';body.counterAxisSizingMode='FIXED';body.resize(bodyData.b[2],bodyData.b[3]);place(body,root,bodyData.b,[0,0,root.width,root.height]);body.clipsContent=false;setBox(body,CAPTURE.styles[bodyData.s]);}
mapping[0]=body;
const entries=new Map(CAPTURE.nodes.map(n=>[n.id,n]));const childMap=new Map();for(const n of CAPTURE.nodes)if(n.parent>=0){if(!childMap.has(n.parent))childMap.set(n.parent,[]);childMap.get(n.parent).push(n);}
const components=page.findAllWithCriteria({types:['COMPONENT']}).filter(c=>c.name.startsWith('Current website /'));
let ci=components.length;
const fixed=resume?root.children.filter(n=>n!==body&&n.name!=='Browser scrollbar gutter'&&n.name!=='Browser scrollbar thumb'):[];
async function build(data,parent,pb){
  const s=CAPTURE.styles[data.s];let n;
  if(data.tag==='text'){n=await textNode(data,parent,pb);mapping[data.id]=n;return n;}
  if(data.tag==='pseudo'&&s.transform?.includes('matrix')&&number(s.transform.split(',')[4])<0)return null;
  if(data.svg){try{n=addSvg(data.svg,parent,'SVG / '+String(data.name).slice(0,80),data.b[2],data.b[3]);n.x=data.b[0]-pb[0];n.y=data.b[1]-pb[1];n.opacity=Number(s.opacity)||1;mapping[data.id]=n;return n;}catch(e){warnings.push('SVG '+data.id+': '+e.message);throw e;}}
  if(data.icon){
    const key=(data.icon.includes('far ')?'regular/':'solid/')+(data.icon.match(/\bfa-([\w-]+)/)||[])[1];
    const asset=ICON_ASSETS[key];if(!asset)throw new Error('Missing exact source icon '+key);
    const compName='Current website / Icon / '+key+' / '+(data.iconColor||s.color);
    let comp=components.find(c=>c.name===compName);
    if(!comp){comp=track(figma.createComponent());comp.name=compName;comp.resize(data.b[2],data.b[3]);comp.fills=[];comp.x=7300+(ci%8)*120;comp.y=Math.floor(ci++/8)*130;comp.description='Font Awesome 5.15.4 source icon used by StudentShield.';const v=addSvg(asset.replace(/<path /g,'<path fill="'+xml(data.iconColor||s.color)+'" '),comp,'Source icon',comp.width,comp.height);v.x=0;v.y=0;components.push(comp);}
    n=comp.createInstance();recordTree(n);n.name='Icon / '+key;place(n,parent,data.b,pb);n.resize(data.b[2],data.b[3]);mapping[data.id]=n;return n;
  }
  if(data.tag==='img'||data.canvas){
    n=track(figma.createRectangle());n.name=data.canvas?'Chart.js / '+data.name:'School logo';n.resize(data.b[2],data.b[3]);place(n,parent,data.b,pb);let hash='d3d134e63f72aec0558d746d297cf499d7a12a7a';
    if(data.canvas){if(!data.data)throw new Error('Missing rendered chart '+data.name);const bin=figma.base64Decode(data.data.split(',')[1]);hash=figma.createImage(bin).hash;}
    n.fills=[{type:'IMAGE',imageHash:hash,scaleMode:data.fit==='cover'?'FILL':'FIT'}];n.opacity=Number(s.opacity)||1;mapping[data.id]=n;return n;
  }
  n=track(figma.createAutoLayout());n.name=(data.tag==='pseudo'?'Decoration / ':data.tag+' / ')+String(data.name).slice(0,115);n.layoutMode='VERTICAL';n.primaryAxisSizingMode='FIXED';n.counterAxisSizingMode='FIXED';n.resize(Math.max(.01,data.b[2]),Math.max(.01,data.b[3]));place(n,parent,data.b,pb);setBox(n,s);mapping[data.id]=n;
  const radius=number(s.borderTopLeftRadius);n.clipsContent=s.overflow==='hidden'||s.overflowX==='hidden'||s.overflowY==='hidden'||radius>0;
  const internalScroll=data.scroll&&data.scroll[3]>data.b[3]+2&&['auto','scroll'].includes(s.overflowY);
  let childParent=n,childPb=data.b;
  if(internalScroll){const content=track(figma.createAutoLayout());content.name='Inner scroll content';content.layoutMode='VERTICAL';content.primaryAxisSizingMode='FIXED';content.counterAxisSizingMode='FIXED';content.resize(Math.max(data.b[2],data.scroll[2]),data.scroll[3]);n.appendChild(content);content.layoutPositioning='ABSOLUTE';content.x=-data.scroll[0];content.y=-data.scroll[1];content.fills=[];content.clipsContent=false;childParent=content;childPb=[data.b[0]-data.scroll[0],data.b[1]-data.scroll[1],content.width,content.height];n.overflowDirection='VERTICAL';n.clipsContent=true;}
  if(data.kind){
    if(data.kind==='checkbox'||data.kind==='radio'){
      if(!number(s.borderTopWidth)){n.strokes=[solid('#8ba098')];n.strokeWeight=1.5;n.strokeAlign='INSIDE';n.cornerRadius=data.kind==='radio'?Math.min(n.width,n.height)/2:3;n.fills=[paint(data.checked?'#0F8A3A':s.backgroundColor)];}
      if(data.checked){const check=addSvg('<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="m5 12 4 4L19 6" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',n,'Checked',Math.min(16,n.width),Math.min(16,n.height));check.x=(n.width-check.width)/2;check.y=(n.height-check.height)/2;}
    }else{
      let txt=data.value||data.placeholder||'';
      if(data.kind==='file')txt='Choose file     No file chosen';
      if(data.kind==='date'&&!txt)txt='mm/dd/yyyy';
      if(txt){let ps=data.value?s:CAPTURE.styles[data.placeholderStyle]||s;let pad=number(s.paddingLeft);const h=number(s.lineHeight)||number(s.fontSize)*1.5;let y=data.kind==='textarea'?number(s.paddingTop):(n.height-h)/2;await textNode({text:txt,b:[data.b[0]+pad,data.b[1]+y,n.width-pad-number(s.paddingRight||12)-12,h],s:data.s},n,data.b,ps);}
      if(data.tag==='select'){const arrow=addSvg('<svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg"><path d="m3 5 3 3 3-3" fill="none" stroke="'+xml(s.color)+'" stroke-width="1.5"/></svg>',n,'Native select arrow',12,12);arrow.x=n.width-23;arrow.y=(n.height-12)/2;}
    }
  }
  const children=(childMap.get(data.id)||[]).slice();
  children.sort((a,b)=>{const sa=CAPTURE.styles[a.s],sb=CAPTURE.styles[b.s];const rank=(q,z)=>q.tag==='pseudo'?2:['absolute','fixed','sticky'].includes(z.position)||/\babsolute\b/.test(String(q.name))?1:0;return rank(a,sa)-rank(b,sb)||(number(sa.zIndex)-number(sb.zIndex));});
  for(const c of children)await build(c,childParent,childPb);
  // Controls become reusable local components. Instances retain real text and icons.
  if(['button','input','textarea','select'].includes(data.tag)&&!internalScroll){
    const key='Current website / Control / '+data.tag+' / '+String(data.name).slice(0,90)+' / '+Math.round(n.width)+'x'+Math.round(n.height)+' / '+CAPTURE.title;
    let comp=components.find(q=>q.name===key);
    if(!comp){comp=track(figma.createComponent());comp.name=key;comp.description='Current rendered '+data.tag+' control. Text, icon and state are retained from the local StudentShield page.';comp.layoutMode='VERTICAL';comp.primaryAxisSizingMode='FIXED';comp.counterAxisSizingMode='FIXED';comp.resize(n.width,n.height);comp.fills=n.fills;comp.strokes=n.strokes;comp.strokeWeight=n.strokeWeight;comp.strokeAlign=n.strokeAlign;comp.topLeftRadius=n.topLeftRadius;comp.topRightRadius=n.topRightRadius;comp.bottomRightRadius=n.bottomRightRadius;comp.bottomLeftRadius=n.bottomLeftRadius;comp.effects=n.effects;comp.clipsContent=n.clipsContent;comp.x=7300+(ci%8)*520;comp.y=1300+Math.floor(ci++/8)*250;for(const child of n.children.slice()){comp.appendChild(child);child.layoutPositioning='ABSOLUTE';}components.push(comp);}
    const pos=[n.x,n.y];const idx=n.parent.children.indexOf(n);const holder=n.parent;const instance=comp.createInstance();recordTree(instance);holder.insertChild(idx,instance);instance.layoutPositioning='ABSOLUTE';instance.x=pos[0];instance.y=pos[1];instance.resize(n.width,n.height);instance.name=n.name;n.remove();n=instance;mapping[data.id]=n;
  }
  return n;
}
for(const data of CAPTURE.nodes.filter(n=>n.id!==0&&(n.parent===0||!entries.has(n.parent)))){
  const s=CAPTURE.styles[data.s];const decorative=/pointer-events-none/.test(String(data.name));const isFixed=data.parent===0&&!decorative&&(s.position==='fixed'||s.position==='sticky'||(/\bfixed\b/.test(String(data.name))&&!s.position));
  if(isFixed){const n=await build(data,root,[0,0,root.width,root.height]);if(n)fixed.push(n);}
  else if(data.parent===0)await build(data,body,bodyData.b);
  else {const anchor=PARENT_ANCHORS[data.parent];if(!anchor)throw new Error('Missing parent anchor '+data.parent);let pn=await figma.getNodeByIdAsync(anchor.id);if(pn.type==='INSTANCE')throw new Error('Cannot append a partial control');let pb=anchor.b;const inner=pn.children?.find(n=>n.name==='Inner scroll content');if(inner){pn=inner;pb=[pb[0]-(anchor.scroll?.[0]||0),pb[1]-(anchor.scroll?.[1]||0),pb[2],pb[3]];}await build(data,pn,pb);mutated.push(pn.id);}
}
// Fixed headers / decorative background remain stationary during prototype scroll.
for(const n of fixed){root.appendChild(n);n.layoutPositioning='ABSOLUTE';}root.numberOfFixedChildren=fixed.length;
// Preserve the real browser scrollbar gutter instead of stretching the webpage.
if(body.width<root.width&&!resume){const gutter=track(figma.createRectangle());root.appendChild(gutter);gutter.layoutPositioning='ABSOLUTE';gutter.name='Browser scrollbar gutter';gutter.resize(root.width-body.width,root.height);gutter.x=body.width;gutter.y=0;gutter.fills=[solid('#f3f3f3')];const thumb=track(figma.createRectangle());root.appendChild(thumb);thumb.layoutPositioning='ABSOLUTE';thumb.name='Browser scrollbar thumb';const h=Math.max(30,root.height*root.height/body.height);thumb.resize(8,Math.min(root.height-12,h));thumb.x=body.width+3.5;thumb.y=6+Math.max(0,CAPTURE.scroll[1])/(Math.max(body.height-root.height,1))*(root.height-thumb.height-12);thumb.cornerRadius=4;thumb.fills=[solid('#969696')];root.numberOfFixedChildren=fixed.length+2;}
if(resume&&body.width<root.width){for(const g of root.children.filter(n=>n.name==='Browser scrollbar gutter'||n.name==='Browser scrollbar thumb')){root.appendChild(g);g.layoutPositioning='ABSOLUTE';}root.numberOfFixedChildren=fixed.length+2;}
const all=root.findAll(()=>true);
return {stageId:root.id,bodyId:body.id,targetId:FRAME_ID,createdNodeIds:made,mutatedNodeIds:mutated,collectionId:collection.id,variableIds,styleIds:stylesMade,mapping:Object.fromEntries(Object.entries(mapping).map(([k,n])=>[k,n.id])),count:all.length+1,textCount:all.filter(n=>n.type==='TEXT').length,imageCount:all.filter(n=>'fills'in n&&Array.isArray(n.fills)&&n.fills.some(p=>p.type==='IMAGE')).length,size:[root.width,root.height],contentHeight:body.height,scrollable:root.overflowDirection,warnings};
