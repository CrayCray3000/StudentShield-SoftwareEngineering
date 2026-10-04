(full = false) => {
    const styles = [], styleMap = new Map(), nodes = [];
    const num = value => Number(String(value).replace(/[^0-9.\-]/g,''));
    const rect = e => { const r=e.getBoundingClientRect(); return [r.x,r.y,r.width,r.height].map(v=>Math.round(v*100)/100); };
    const sid = s => {
        const props=['color','backgroundColor','backgroundImage','borderTopColor','borderTopWidth','borderRightColor','borderRightWidth','borderBottomColor','borderBottomWidth','borderLeftColor','borderLeftWidth','borderTopLeftRadius','borderTopRightRadius','borderBottomRightRadius','borderBottomLeftRadius','opacity','boxShadow','filter','backdropFilter','transform','fontFamily','fontSize','fontWeight','fontStyle','lineHeight','letterSpacing','textTransform','textAlign','overflow','overflowX','overflowY','position','zIndex','display','paddingLeft','paddingRight','paddingTop','paddingBottom'];
        const o={}; for(const k of props) if(s[k])o[k]=s[k];
        const key=JSON.stringify(o);if(styleMap.has(key))return styleMap.get(key);
        const id=styles.length;styles.push(o);styleMap.set(key,id);return id;
    };
    const walk = (el, parent) => {
        const s=getComputedStyle(el), b=rect(el);
        if(s.display==='none'||s.visibility==='hidden'||Number(s.opacity)===0||['SCRIPT','STYLE','META','LINK','NOSCRIPT','HEAD'].includes(el.tagName))return;
        if(b[2]===0||b[3]===0)return;
        // Keep containers whose content intersects the requested viewport.
        if(!full&&(b[1]+b[3]<0||b[1]>innerHeight||b[0]+b[2]<0||b[0]>innerWidth))return;
        const id=nodes.length, tag=el.tagName.toLowerCase();
        const n={id,parent,tag,name:el.id||el.getAttribute('aria-label')||el.className?.baseVal||el.className||tag,b,s:sid(s)};
        n.scroll=[el.scrollLeft,el.scrollTop,el.scrollWidth,el.scrollHeight];
        if(tag==='body')n.document=[document.documentElement.scrollWidth,document.documentElement.scrollHeight];
        if(el.matches('a'))n.href=el.getAttribute('href');
        if(el.matches('button'))n.disabled=el.disabled;
        if(tag==='img'){n.src=el.currentSrc||el.src;n.fit=s.objectFit;}
        if(tag==='svg'){
            n.svg=el.outerHTML.replace(/currentColor/g,s.color).replace(/<svg\b/,'<svg width="'+b[2]+'" height="'+b[3]+'"');
            nodes.push(n);return;
        }
        if(tag==='i'&&/\bfa[srb]?\b/.test(el.className)){n.icon=el.className;n.iconColor=s.color;nodes.push(n);return;}
        if(tag==='canvas'){n.canvas=true;n.data=el.toDataURL('image/png');nodes.push(n);return;}
        if(tag==='input'||tag==='textarea'||tag==='select'){
            n.kind=el.type||tag;
            if(n.kind==='checkbox'||n.kind==='radio')n.checked=el.checked;
            else {n.value=n.kind==='password'?'':el.value;n.placeholder=el.getAttribute('placeholder');if(tag==='select')n.value=el.options[el.selectedIndex]?.text||'';n.placeholderStyle=sid(getComputedStyle(el,'::placeholder'));}
        }
        nodes.push(n);
        for(const child of el.childNodes){
            if(child.nodeType===1)walk(child,id);
            else if(child.nodeType===3&&child.textContent.trim()){
                const r=document.createRange();r.selectNodeContents(child);const rb=r.getBoundingClientRect();
                if(rb.width&&rb.height){let t=child.textContent.replace(/\s+/g,' ').trim();if(child.nextSibling?.nodeType===1&&['SPAN','A','B','STRONG','EM'].includes(child.nextSibling.tagName)&&/\s$/.test(child.textContent))t+=' ';if(s.textTransform==='uppercase')t=t.toUpperCase();
                    const emojis=[];for(const m of child.textContent.matchAll(/[\u{1F000}-\u{1FAFF}\u23F3\u26A0]\uFE0F?/gu)){const er=document.createRange();er.setStart(child,m.index);er.setEnd(child,m.index+m[0].length);const eb=er.getBoundingClientRect();emojis.push({ch:m[0],b:[eb.x,eb.y,eb.width,eb.height].map(v=>Math.round(v*100)/100)});}
                    nodes.push({id:nodes.length,parent:id,tag:'text',text:t,b:[rb.x,rb.y,rb.width,rb.height].map(v=>Math.round(v*100)/100),rects:Array.from(r.getClientRects()).map(q=>[q.x,q.y,q.width,q.height].map(v=>Math.round(v*100)/100)),emojis,s:sid(s)});}
            }
        }
        for(const pseudo of ['::before','::after']){
            const ps=getComputedStyle(el,pseudo);
            if(ps.content==='none'||ps.content==='normal'||ps.display==='none'||Number(ps.opacity)===0)continue;
            const w=num(ps.width),h=num(ps.height);if(!(w>0&&h>0))continue;
            const x=ps.left!=='auto'?b[0]+num(ps.left):b[0]+b[2]-num(ps.right)-w;
            const y=ps.top!=='auto'?b[1]+num(ps.top):b[1]+b[3]-num(ps.bottom)-h;
            if(Number.isFinite(x)&&Number.isFinite(y))nodes.push({id:nodes.length,parent:id,tag:'pseudo',name:pseudo,b:[x,y,w,h],s:sid(ps)});
        }
    };
    walk(document.body,-1);
    return {url:location.href,title:document.title,viewport:[innerWidth,innerHeight],scroll:[scrollX,scrollY],full,styles,nodes};
}
