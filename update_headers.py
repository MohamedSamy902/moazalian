import os
import re

directory = "/home/sharo/Desktop/mo3az3lian"

# Read the header from index.html
with open(os.path.join(directory, "index.html"), "r", encoding="utf-8") as f:
    content = f.read()

# Extract the header block from index.html
header_match = re.search(r'<header.*?</header>', content, re.DOTALL)
if not header_match:
    print("Could not find header in index.html")
    exit(1)

new_header = header_match.group(0)

# Create dawah.html by copying about.html
with open(os.path.join(directory, "about.html"), "r", encoding="utf-8") as f:
    dawah_content = f.read()

# Modify dawah_content for "الدعوة للإسلام"
dawah_content = re.sub(r'<title>.*?</title>', '<title>الدعوة للإسلام | معاذ عليان — باحث في مقارنة الأديان</title>', dawah_content)
dawah_content = re.sub(r'عن معاذ عليان', 'الدعوة للإسلام', dawah_content)
dawah_content = re.sub(r'عن معاذ', 'الدعوة', dawah_content)
dawah_content = re.sub(r'إعلامي وباحث مصري مسلم في مقارنة الأديان منذ عام 2004', 'مشروع دعوي لتعريف غير المسلمين بالإسلام والرد على استفساراتهم', dawah_content)
dawah_content = re.sub(r'<div class="timeline-container">.*?</div>\s*</div>', '</div>', dawah_content, flags=re.DOTALL) # remove timeline

# Add dawah specific content instead of timeline
dawah_specific = """
        <div class="col-lg-5">
          <div class="card-x p-4 h-100" style="text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 1.5rem;">
            <span class="ic ic-lg ic-green mx-auto"><i class="bi bi-whatsapp"></i></span>
            <h3 style="color: var(--text);">تواصل معنا للدعوة</h3>
            <p style="color: var(--text2); font-size: .9rem;">إذا كان لديك أي أسئلة عن الإسلام أو تريد الاستفسار عن تفاصيل الدين الحنيف، فريقنا متاح للإجابة.</p>
            <a href="https://wa.me/201000000000" target="_blank" class="btn btn-gold w-100" style="background:#25d366; color: #fff !important; border:none; padding: .8rem; border-radius: 8px;">
               تواصل عبر واتساب <i class="bi bi-whatsapp me-2"></i>
            </a>
          </div>
        </div>
"""
dawah_content = re.sub(r'<!-- Interactive Timeline -->.*?</div>\s*</div>\s*</div>', f'<!-- Dawah Action -->\n{dawah_specific}\n      </div>', dawah_content, flags=re.DOTALL)

with open(os.path.join(directory, "dawah.html"), "w", encoding="utf-8") as f:
    f.write(dawah_content)

# Update all html files (including dawah.html) with the new header
html_files = [f for f in os.listdir(directory) if f.endswith(".html")]

for filename in html_files:
    if filename == "index.html":
        continue
    
    filepath = os.path.join(directory, filename)
    with open(filepath, "r", encoding="utf-8") as f:
        file_content = f.read()
    
    # Replace header
    updated_content = re.sub(r'<header.*?</header>', new_header, file_content, flags=re.DOTALL)
    
    with open(filepath, "w", encoding="utf-8") as f:
        f.write(updated_content)
    
    print(f"Updated header in {filename}")

print("Done!")
