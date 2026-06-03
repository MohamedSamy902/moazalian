import os
import re

html_files = ['index.html', 'about.html', 'live.html', 'videos.html', 'debates.html', 'references.html', 'articles.html', 'books.html', 'contact.html']
base_dir = '/home/sharo/Desktop/mo3az3lian'

font_new = r'<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;900&display=swap" rel="stylesheet"/>'

anti_flash = """<script>
  (function(){
    const savedTheme = localStorage.getItem('theme');
    if(savedTheme){
      document.documentElement.setAttribute('data-theme', savedTheme);
    }else{
      document.documentElement.setAttribute('data-theme', 'dark');
    }
  })();
</script>
</head>"""

toggle_html = """</a>
        <button id="theme-toggle" class="theme-toggle ms-2" aria-label="تبديل المظهر">
          <i class="bi bi-moon-fill dark-icon"></i>
          <i class="bi bi-sun-fill light-icon"></i>
        </button>
      </div>
    </div>
  </nav>"""

for file in html_files:
    filepath = os.path.join(base_dir, file)
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Replace font
    content = re.sub(r'<link href="https://fonts.googleapis.com/css2\?family=Cairo:.*?rel="stylesheet"/>', font_new, content)
    
    # Add anti-flash before </head>
    if 'savedTheme' not in content:
        content = content.replace('</head>', anti_flash)
        
    # Add toggle button after ادعم القناة
    if 'theme-toggle' not in content:
        content = re.sub(r'</a>\s*</div>\s*</div>\s*</nav>', toggle_html, content)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

css_append = """
/* ═══════════════ THEME TOGGLE ═══════════════ */
.theme-toggle {
  width: 44px; height: 44px;
  border-radius: 50%;
  border: 1px solid var(--border);
  background: var(--card);
  color: var(--text);
  display: inline-flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: transform 0.4s ease, background 0.3s, border-color 0.3s;
  padding: 0;
}
.theme-toggle:hover {
  transform: rotate(360deg);
  border-color: var(--gold);
  color: var(--gold);
}
.theme-toggle .light-icon { display: none; }
[data-theme="light"] .theme-toggle .dark-icon { display: none; }
[data-theme="light"] .theme-toggle .light-icon { display: inline-block; color: #f59e0b; }
"""

with open(os.path.join(base_dir, 'style.css'), 'r', encoding='utf-8') as f:
    css_content = f.read()
if '.theme-toggle' not in css_content:
    with open(os.path.join(base_dir, 'style.css'), 'a', encoding='utf-8') as f:
        f.write(css_append)

js_append = """
/* ── Theme Toggle ── */
const themeToggleBtn = document.getElementById('theme-toggle');
if (themeToggleBtn) {
  themeToggleBtn.addEventListener('click', () => {
    const root = document.documentElement;
    const currentTheme = root.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    root.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
  });
}
"""

with open(os.path.join(base_dir, 'script.js'), 'r', encoding='utf-8') as f:
    js_content = f.read()
if 'themeToggleBtn' not in js_content:
    with open(os.path.join(base_dir, 'script.js'), 'a', encoding='utf-8') as f:
        f.write(js_append)

print("Phase 1 update script executed.")
