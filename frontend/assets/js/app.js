/* ============================================================
   UIU Research Portal — Global App Logic
   ============================================================ */

/*  Theme Toggle  */
const ThemeManager = {
  init() {
    const saved = localStorage.getItem('uiu-theme') || 'light';
    document.documentElement.setAttribute('data-theme', saved);
    this.updateIcon();
  },
  toggle() {
    const current = document.documentElement.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('uiu-theme', next);
    this.updateIcon();
  },
  updateIcon() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    document.querySelectorAll('[data-theme-icon]').forEach(el => {
      el.innerHTML = isDark
        ? `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>`
        : `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>`;
    });
  }
};

/*  Sidebar  */
const Sidebar = {
  init() {
    const toggle = document.querySelector('.sidebar-toggle');
    const overlay = document.querySelector('.sidebar-overlay');
    const sidebar = document.querySelector('.sidebar');
    if (!toggle || !sidebar) return;
    toggle.addEventListener('click', () => this.open());
    overlay?.addEventListener('click', () => this.close());
    // Mark active link
    const path = window.location.pathname;
    document.querySelectorAll('.nav-item').forEach(link => {
      const href = link.getAttribute('href') || '';
      if (path.includes(href.replace('../', '').split('/')[0]) && href !== '#') {
        link.classList.add('active');
      }
    });
  },
  open() {
    document.querySelector('.sidebar')?.classList.add('open');
    document.querySelector('.sidebar-overlay')?.classList.add('show');
    document.body.style.overflow = 'hidden';
  },
  close() {
    document.querySelector('.sidebar')?.classList.remove('open');
    document.querySelector('.sidebar-overlay')?.classList.remove('show');
    document.body.style.overflow = '';
  }
};

/*  Modal Manager  */
const Modal = {
  open(id) {
    const el = document.getElementById(id);
    if (el) { el.classList.add('show'); document.body.style.overflow = 'hidden'; }
  },
  close(id) {
    const el = document.getElementById(id);
    if (el) { el.classList.remove('show'); document.body.style.overflow = ''; }
  },
  closeAll() {
    document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('show'));
    document.body.style.overflow = '';
  },
  init() {
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
      btn.addEventListener('click', () => Modal.open(btn.dataset.modalOpen));
    });
    document.querySelectorAll('[data-modal-close]').forEach(btn => {
      btn.addEventListener('click', () => Modal.close(btn.dataset.modalClose || btn.closest('.modal-overlay')?.id));
    });
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
      overlay.addEventListener('click', e => { if (e.target === overlay) Modal.close(overlay.id); });
    });
  }
};

/*  Toast Notifications  */
const Toast = {
  container: null,
  init() {
    this.container = document.querySelector('.toast-container');
    if (!this.container) {
      this.container = document.createElement('div');
      this.container.className = 'toast-container';
      document.body.appendChild(this.container);
    }
  },
  show(message, type = 'info', duration = 3500) {
    if (!this.container) this.init();
    const labels = { info: 'Info', success: 'Done', error: 'Error', warning: 'Warn' };
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `<span style="font-size:.7rem;font-weight:700;letter-spacing:.04em;color:var(--text-muted);font-family:'Inter',sans-serif;">${labels[type]||'Info'}</span><span style="flex:1;font-size:.875rem;">${message}</span><span onclick="this.parentElement.remove()" style="cursor:pointer;color:var(--text-muted);font-size:1.1rem;line-height:1;">&times;</span>`;
    this.container.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(20px)'; toast.style.transition = 'all 0.3s'; setTimeout(() => toast.remove(), 300); }, duration);
  }
};

/*  Tabs  */
const Tabs = {
  init(wrapperSelector = '[data-tabs]') {
    document.querySelectorAll(wrapperSelector).forEach(wrapper => {
      const items = wrapper.querySelectorAll('.tab-item, .tab-line-item');
      items.forEach(item => {
        item.addEventListener('click', () => {
          items.forEach(i => i.classList.remove('active'));
          item.classList.add('active');
          const target = item.dataset.tab;
          if (target) {
            const panels = wrapper.closest('[data-tabs-parent]') || document;
            panels.querySelectorAll('[data-tab-panel]').forEach(p => {
              p.style.display = p.dataset.tabPanel === target ? '' : 'none';
            });
          }
          if (item.dataset.onTab) { window[item.dataset.onTab]?.(); }
        });
      });
    });
  }
};

/*  Dropdown  */
const Dropdown = {
  init() {
    document.querySelectorAll('[data-dropdown-toggle]').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const menuId = btn.dataset.dropdownToggle;
        const menu = document.getElementById(menuId);
        if (!menu) return;
        const isOpen = menu.classList.contains('show');
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
        if (!isOpen) menu.classList.add('show');
      });
    });
    document.addEventListener('click', () => {
      document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
    });
  }
};

/*  Scroll Reveal  */
const ScrollReveal = {
  init() {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
  }
};

/*  Number counter animation  */
const Counter = {
  animate(el, target, duration = 1500) {
    const start = performance.now();
    const update = (time) => {
      const elapsed = time - start;
      const progress = Math.min(elapsed / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      el.textContent = Math.floor(eased * target).toLocaleString();
      if (progress < 1) requestAnimationFrame(update);
    };
    requestAnimationFrame(update);
  },
  initAll() {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.dataset.counted) {
          entry.target.dataset.counted = true;
          const target = parseInt(entry.target.dataset.count, 10);
          this.animate(entry.target, target);
        }
      });
    }, { threshold: 0.5 });
    document.querySelectorAll('[data-count]').forEach(el => observer.observe(el));
  }
};

/*  Avatar helper  */
function buildAvatar(student, size = 'md') {
  const colors = ['accent', 'purple', 'green', 'orange'];
  const color = student.color || colors[student.id % colors.length];
  return `<div class="avatar-placeholder avatar-${size} ${color}" style="width:${size==='sm'?32:size==='lg'?64:44}px;height:${size==='sm'?32:size==='lg'?64:44}px;">${student.initials}</div>`;
}

/*  Search filter  */
function filterCards(inputEl, cardSelector, textSelector) {
  inputEl.addEventListener('input', () => {
    const q = inputEl.value.toLowerCase();
    document.querySelectorAll(cardSelector).forEach(card => {
      const text = card.querySelector(textSelector)?.textContent.toLowerCase() || card.textContent.toLowerCase();
      card.style.display = text.includes(q) ? '' : 'none';
    });
  });
}

/*  Format date  */
function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

/*  SVG Icon library  */
const IC = {
  s: (d, extra='') => `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" width="17" height="17" ${extra}>${d}</svg>`,
  dashboard:      () => IC.s('<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>'),
  profile:        () => IC.s('<circle cx="12" cy="8" r="4"/><path d="M4 20c0-3.87 3.58-7 8-7s8 3.13 8 7"/>'),
  messages:       () => IC.s('<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'),
  bell:           () => IC.s('<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>'),
  users:          () => IC.s('<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'),
  folder:         () => IC.s('<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>'),
  flask:          () => IC.s('<path d="M9 3h6M10 3v6l-4 10a1 1 0 0 0 .9 1.4h10.2A1 1 0 0 0 18 19L14 9V3"/><path d="M7.5 14h9"/>'),
  book:           () => IC.s('<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>'),
  database:       () => IC.s('<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>'),
  pen:            () => IC.s('<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>'),
  filetext:       () => IC.s('<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>'),
  calendar:       () => IC.s('<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'),
  barchart:       () => IC.s('<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>'),
  award:          () => IC.s('<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>'),
  gear:           () => IC.s('<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>'),
  search:         () => IC.s('<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>'),
  menu:           () => IC.s('<line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>'),
  logo:           () => IC.s('<path d="M10 2a2 2 0 0 0-2 2v1H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2V4a2 2 0 0 0-2-2h-4z"/><path d="M10 10h4M10 14h4M10 6h4" stroke-width="1.5"/>'),
};

/*  Build sidebar HTML  */
function buildSidebar(activePage = '') {
  const u = typeof DATA !== 'undefined' ? DATA.currentUser : { name: 'Rafsan Ahmed', initials: 'RA', role: 'Graduate Student' };
  const unread = typeof DATA !== 'undefined' ? DATA.notifications.filter(n => !n.read).length : 0;
  const base = getBase();
  const nav = (href, page, icon, label, count='') => `
    <a href="${base}${href}" class="nav-item ${activePage===page?'active':''}">
      <span class="icon">${icon}</span>${label}${count?`<span class="badge-count">${count}</span>`:''}
    </a>`;
  return `
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
      <div class="sidebar-logo-icon">${IC.logo()}</div>
      <div>
        <div class="sidebar-logo-text">UIU Research</div>
        <div class="sidebar-logo-sub">Collaboration Portal</div>
      </div>
    </div>
    <nav class="sidebar-nav">
      <div class="sidebar-section-label">Overview</div>
      ${nav('dashboard/index.html','dashboard', IC.dashboard(), 'Dashboard')}
      ${nav('profile/index.html',  'profile',   IC.profile(),   'My Profile')}
      ${nav('messages/index.html', 'messages',  IC.messages(),  'Messages', 3)}
      ${nav('notifications/index.html','notifications', IC.bell(), 'Notifications', unread||'')}

      <div class="sidebar-section-label">Collaboration</div>
      ${nav('collaborators/index.html','collaborators', IC.users(),  'Find Collaborators')}
      ${nav('projects/index.html',     'projects',      IC.folder(), 'Projects')}
      ${nav('ideas/index.html',        'ideas',         IC.flask(),  'Innovation Hub')}

      <div class="sidebar-section-label">Research</div>
      ${nav('resources/index.html','resources', IC.book(),     'Resource Hub')}
      ${nav('datasets/index.html', 'datasets',  IC.database(), 'Dataset Hub')}
      ${nav('editor/index.html',   'editor',    IC.pen(),      'Research Editor')}

      <div class="sidebar-section-label">Community</div>
      ${nav('blog/index.html',   'blog',   IC.filetext(),  'Blog')}
      ${nav('events/index.html', 'events', IC.calendar(),  'Events')}

      <div class="sidebar-section-label">Analytics</div>
      ${nav('contributions/index.html','contributions', IC.barchart(), 'Contributions')}
      ${nav('reputation/index.html',   'reputation',    IC.award(),    'Reputation')}
    </nav>
    <div class="sidebar-footer">
      <div class="sidebar-user" onclick="window.location='${base}profile/index.html'">
        <div class="avatar-placeholder avatar-sm accent" style="width:34px;height:34px;">${u.initials}</div>
        <div class="sidebar-user-info">
          <div class="sidebar-user-name">${u.name}</div>
          <div class="sidebar-user-role">${u.role}</div>
        </div>
        <span style="color:var(--text-muted);display:flex;align-items:center;">${IC.gear()}</span>
      </div>
    </div>
  </aside>
  <div class="sidebar-overlay" id="sidebarOverlay"></div>
  <button class="sidebar-toggle" onclick="Sidebar.open()">${IC.menu()}</button>`;
}

/*  Build topbar HTML  */
function buildTopbar(title = 'Dashboard', activePage = '') {
  const base = getBase();
  return `
  <header class="topbar">
    <div class="topbar-title">${title}</div>
    <div class="topbar-spacer"></div>
    <div class="search-bar">
      <span class="icon">${IC.search()}</span>
      <input type="text" placeholder="Search anything…" id="globalSearch">
    </div>
    <div class="topbar-actions">
      <button class="topbar-icon-btn" onclick="ThemeManager.toggle()" data-tooltip="Toggle theme"><span data-theme-icon></span></button>
      <a href="${base}notifications/index.html" class="topbar-icon-btn" data-tooltip="Notifications">
        ${IC.bell()}<span class="notif-dot"></span>
      </a>
      <a href="${base}profile/index.html" class="topbar-icon-btn" data-tooltip="My Profile">${IC.profile()}</a>
    </div>
  </header>`;
}

/*  Get base path  */
function getBase() {
  const path = window.location.pathname.replace(/\\/g, '/');
  const subdirs = [
    'dashboard', 'profile', 'messages', 'notifications', 
    'collaborators', 'projects', 'ideas', 'resources', 
    'datasets', 'editor', 'blog', 'events', 'contributions', 
    'reputation', 'auth'
  ];
  for (const dir of subdirs) {
    if (path.includes('/' + dir + '/') || path.endsWith('/' + dir)) {
      return '../';
    }
  }
  return './';
}

/*  App Init  */
document.addEventListener('DOMContentLoaded', () => {
  ThemeManager.init();
  Sidebar.init();
  Modal.init();
  Dropdown.init();
  ScrollReveal.init();
  Counter.initAll();
  Toast.init();
  Tabs.init('[data-tabs]');
});
