/* =============================================================
   admin-editor.js — Éditeur de blocs de contenu
   Utilisé par : admin/posts/create.php et admin/posts/edit.php
   
   Variable attendue (inline dans la vue avant ce script) :
     window.SECTION_COUNT = <?= count($sections ?? []) ?>;
============================================================= */
 
let sectionCount = (typeof window.SECTION_COUNT !== 'undefined') ? window.SECTION_COUNT : 0;
 
function addSection(type) {
    const container = document.getElementById('sections-container');
    const index     = sectionCount++;
    const div       = document.createElement('div');
    div.className   = 'section-block rounded-xl p-4 relative';
    div.style.cssText = 'background:var(--bg2);border:1px solid var(--border);';
    const labels    = {text:'¶ Texte',title:'H Titre',image:'🖼 Image',video:'▶ Vidéo',quote:'" Citation'};
    const is        = "width:100%;border-radius:.5rem;padding:.6rem .75rem;font-size:.875rem;font-family:'Source Sans 3',sans-serif;background:var(--bg);border:1.5px solid var(--border);color:var(--text);outline:2px solid transparent;outline-offset:2px;";
    let html = `<input type="hidden" name="section_type[${index}]" value="${type}">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
            <span style="font-size:.7rem;font-weight:700;text-transform:uppercase;color:var(--muted);font-family:'Source Sans 3',sans-serif;">${labels[type]||type}</span>
            <div style="display:flex;gap:.5rem;">
                <button type="button" onclick="moveSection(this,-1)" style="font-size:.75rem;color:var(--muted);background:none;border:none;cursor:pointer;">↑</button>
                <button type="button" onclick="moveSection(this,1)"  style="font-size:.75rem;color:var(--muted);background:none;border:none;cursor:pointer;">↓</button>
                <button type="button" onclick="removeSection(this)"  style="font-size:.75rem;color:#dc2626;background:none;border:none;cursor:pointer;">✕</button>
            </div>
        </div>`;
    if(type==='text')  html+=`<textarea name="section_content[${index}]" rows="5" style="${is}" placeholder="Votre texte…"></textarea><input type="hidden" name="section_media_url[${index}]" value="">`;
    if(type==='title') html+=`<input type="text" name="section_content[${index}]" style="${is}font-weight:600;" placeholder="Titre de section…"><input type="hidden" name="section_media_url[${index}]" value="">`;
    if(type==='image') html+=`<input type="text" name="section_media_url[${index}]" style="${is}margin-bottom:.5rem;" placeholder="URL image"><input type="text" name="section_content[${index}]" style="${is}" placeholder="Légende">`;
    if(type==='video') html+=`<input type="text" name="section_media_url[${index}]" style="${is}" placeholder="URL vidéo"><input type="hidden" name="section_content[${index}]" value="">`;
    if(type==='quote') html+=`<textarea name="section_content[${index}]" rows="2" style="${is}font-style:italic;" placeholder="Citation…"></textarea><input type="hidden" name="section_media_url[${index}]" value="">`;
    div.innerHTML = html;
    container.appendChild(div);
}
function removeSection(btn){btn.closest('.section-block').remove();}
function moveSection(btn,dir){const b=btn.closest('.section-block'),c=document.getElementById('sections-container'),bl=[...c.querySelectorAll('.section-block')],i=bl.indexOf(b),t=bl[i+dir];if(!t)return;dir===-1?c.insertBefore(b,t):c.insertBefore(t,b);reindexSections();}
function reindexSections(){document.querySelectorAll('.section-block').forEach((b,i)=>{b.querySelectorAll('[name]').forEach(e=>{e.name=e.name.replace(/\[\d+\]/,`[${i}]`);});});}