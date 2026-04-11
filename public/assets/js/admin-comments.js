/* =============================================================
   admin-comments.js — Modale de visualisation des commentaires
   Utilisé par : admin/comments/index.php
 
   Prérequis (inline dans la vue avant ce script) :
     window.BASE_URL = '<?= BASE_URL ?>';
     const COMMENTS_DATA = { ... };
============================================================= */
 
function openCommentModal(id) {
    const c = COMMENTS_DATA[id]; if (!c) return;
    document.getElementById('modal-username').textContent = c.username;
    document.getElementById('modal-date').textContent     = c.date;
    document.getElementById('modal-content').textContent  = c.content;
    const lnk = document.getElementById('modal-post-link');
    lnk.textContent = c.post_title || 'Voir'; lnk.href = 'window.BASE_URL/article/' + c.post_slug;
    const b = document.getElementById('modal-status-badge');
    const ls = { approved:'Approuvé', rejected:'Refusé', pending:'En attente' };
    const ss = { approved:'background:#dcfce7;color:#166534;', rejected:'background:#fee2e2;color:#991b1b;', pending:'background:#fef9c3;color:#854d0e;' };
    b.textContent = ls[c.status] || c.status; b.style.cssText = ss[c.status] || '';
    const a = document.getElementById('modal-actions'); let h = '';
    if (c.status !== 'approved') h += `<form method="POST" action="window.BASE_URL/admin/comments/${id}/approve"><input type="hidden" name="_csrf" value="${c.csrf}"><button style="padding:.5rem 1rem;font-size:.875rem;font-weight:600;border-radius:9999px;border:1.5px solid #16a34a;color:#16a34a;background:transparent;cursor:pointer;" onmouseover="this.style.background='#16a34a';this.style.color='white'" onmouseout="this.style.background='transparent';this.style.color='#16a34a'">✓ Approuver</button></form>`;
    if (c.status !== 'rejected') h += `<form method="POST" action="window.BASE_URL/admin/comments/${id}/reject"><input type="hidden" name="_csrf" value="${c.csrf}"><button style="padding:.5rem 1rem;font-size:.875rem;font-weight:600;border-radius:9999px;border:1.5px solid #d97706;color:#d97706;background:transparent;cursor:pointer;" onmouseover="this.style.background='#d97706';this.style.color='white'" onmouseout="this.style.background='transparent';this.style.color='#d97706'">✗ Refuser</button></form>`;
    h += `<form method="POST" action="window.BASE_URL/admin/comments/${id}/delete" onsubmit="return confirm('Supprimer ?')"><input type="hidden" name="_csrf" value="${c.csrf}"><button style="padding:.5rem 1rem;font-size:.875rem;font-weight:600;border-radius:9999px;border:1.5px solid #dc2626;color:#dc2626;background:transparent;cursor:pointer;" onmouseover="this.style.background='#dc2626';this.style.color='white'" onmouseout="this.style.background='transparent';this.style.color='#dc2626'">🗑 Supprimer</button></form>`;
    a.innerHTML = h;
    document.getElementById('comment-modal').style.display = 'flex';
}
function closeCommentModal() { document.getElementById('comment-modal').style.display = 'none'; }
document.getElementById('comment-modal').addEventListener('click', function(e) { if (e.target === this) closeCommentModal(); });
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeCommentModal(); });