# خطة دمج واجهات المستخدم (UI Integration Plan)

تم الانتهاء بنجاح من الهيكلة البرمجية (Backend Architecture) لمنصة "معاذ عليان". هذه الخطة مخصصة لعملية دمج واجهات لوحة التحكم (Vuexy) وواجهات الموقع الأمامي (Frontend) مع الكود البرمجي.

## [ ] المرحلة الأولى: تجهيز القوالب الأساسية (Master Layouts)
- [ ] **لوحة التحكم (Admin Layout):**
  - استخراج الـ Layout الأساسي لثيم Vuexy ليصبح `resources/views/layouts/admin.blade.php`.
  - تنظيف القائمة الجانبية (Sidebar) من روابط الـ Demo القديمة (Fix-It) وإضافة روابط مشروعنا (كورسات، مقالات، فيديوهات، إعدادات).
  - التأكد من عمل الدارك مود وتغيير اللغة (RTL/LTR) في الداشبورد بسلاسة.
- [ ] **الموقع الأمامي (Frontend Layout):**
  - نقل ملفات (HTML, CSS, JS) التي صممناها سابقاً إلى مجلدات لارافيل (`public/frontend/css`, `public/frontend/js`).
  - إنشاء `resources/views/layouts/frontend.blade.php`.
  - تحويل الـ Navbar والـ Footer إلى مكونات (Blade Components: `@include('components.navbar')`).

## [ ] المرحلة الثانية: واجهات لوحة التحكم (Dashboard Views)
- [ ] **نظام الإدخال المترجم (Translatable Tabs):**
  - برمجة مكوّن Vuexy (Tabs) لعرض حقول الإدخال باللغتين (AR/EN) لضمان تجربة مستخدم سلسة للإدارة.

### التفاصيل الدقيقة لصفحات الداشبورد (CRUDs List & Inputs):
- [ ] **1. الكورسات (Courses):**
  - **Index:** جدول بـ Pagination (الصورة، العنوان، عدد الدروس، حالة النشر، الإجراءات).
  - **Create/Edit:** 
    - (Tabs AR/EN): `العنوان (Title)`, `الوصف (Description)`
    - (ثوابت): `الصورة المصغرة (Thumbnail)`, `حالة النشر (is_published) Checkbox`
- [ ] **2. الدروس (Lessons):**
  - **Index:** تابعة للكورس، جدول (رقم الدرس، العنوان، المشاهدات، تاريخ النشر).
  - **Create/Edit:** 
    - (Tabs AR/EN): `العنوان (Title)`
    - (ثوابت): `رقم الدرس (Number)`, `رابط اليوتيوب (Youtube URL)`, `المدة (Duration)`
- [ ] **3. المقالات (Articles):**
  - **Index:** جدول بـ Pagination (الصورة، العنوان، التصنيف، وقت القراءة، الإجراءات).
  - **Create/Edit:**
    - (Tabs AR/EN): `العنوان (Title)`, `المقتطف (Excerpt)`, `المحتوى (Body - CKEditor)`
    - (ثوابت): `التصنيف (Category - Select)`, `وقت القراءة (Read Time)`, `الصورة (Thumbnail)`
- [ ] **4. الفيديوهات (Videos):**
  - **Index:** جدول بـ Pagination (العنوان، التصنيف، رابط اليوتيوب، الإجراءات).
  - **Create/Edit:**
    - (Tabs AR/EN): `العنوان (Title)`
    - (ثوابت): `التصنيف (Category - Select)`, `الرابط (Youtube URL)`, `الصورة (Thumbnail)`
- [ ] **5. الكتب (Books):**
  - **Index:** جدول بـ Pagination (الغلاف، العنوان، المؤلف، التصنيف، الإجراءات).
  - **Create/Edit:**
    - (Tabs AR/EN): `العنوان (Title)`, `المؤلف (Author)`
    - (ثوابت): `التصنيف (Category)`, `سنة النشر (Year)`, `صورة الغلاف (Cover)`, `ملف الكتاب (PDF File)`
- [ ] **6. المناظرات (Debates):**
  - **Index:** جدول بـ Pagination (العنوان، المشاهدات، مميز؟، الإجراءات).
  - **Create/Edit:**
    - (Tabs AR/EN): `العنوان (Title)`, `الوصف (Description)`
    - (ثوابت): `رابط اليوتيوب (Youtube URL)`, `المدة (Duration)`, `تثبيت/تمييز (is_featured) Checkbox`
- [ ] **7. الردود السريعة (Quick Replies):**
  - **Index:** جدول بـ Pagination (السؤال، وقت القراءة، الإجراءات).
  - **Create/Edit:**
    - (Tabs AR/EN): `السؤال (Question)`, `الرد (Answer - CKEditor)`
    - (ثوابت): `وقت القراءة (Read Time)`
- [ ] **8. الإعدادات العامة (Settings):**
  - **Index/Edit (One Page):**
    - (Tabs AR/EN): `وصف الموقع (Site Description)`, `عناوين الـ SEO الافتراضية`
    - (ثوابت): `روابط السوشيال ميديا (Facebook, Youtube, Twitter)`, `اللوجو (Logo)`
- [ ] **9. التحكم بالصفحات الثابتة (Static Pages Content):**
  - شاشة مخصصة للتحكم الديناميكي بمحتوى الصفحات (الرئيسية، من نحن، تواصل معنا، المراجع، البث المباشر).
  - استخدام نظام `Settings` أو جدول `PageSections` للتحكم في (النصوص، العناوين، الصور) لكل سيكشن في الموقع (مثل قسم الإحصائيات في الرئيسية، وقسم "عن الشيخ").
  - كل سيكشن سيحتوي على `Tabs` للترجمة (عربي/إنجليزي) ومكان مخصص لرفع الصورة أو الأيقونة الخاصة به.

## [ ] المرحلة الثالثة: واجهات الموقع الأمامي (Frontend Views)
- [ ] **الصفحة الرئيسية (Home Page):**
  - تحويل `index.html` إلى `home.blade.php`.
  - ربط المتغيرات القادمة من `HomeController` (مثل أحدث الكورسات والمقالات) لتعمل بـ `@foreach`.
- [ ] **صفحات المحتوى المجمعة (Lists Pages):**
  - ربط صفحة الكورسات `courses.blade.php` بالبيانات من الداتا بيز وتفعيل الـ Pagination.
  - ربط صفحة المقالات والفيديوهات والكتب.
- [ ] **صفحات التفاصيل (Single Pages):**
  - تحويل `article-details.html` إلى `article-show.blade.php`.
  - ربط السيو (`x-seo-head`) وتمرير بيانات المقال/الكورس الفردي إلى الـ View.

## [ ] المرحلة الرابعة: اللمسات النهائية وتفعيل الأدوات تفاعلية (Interactivity)
- [ ] **الفلاتر والبحث (AJAX / Pipeline):** تفعيل البحث المباشر في الداشبورد والموقع باستخدام الـ Pipeline Pattern التي أعددناها.
- [ ] **رفع الملفات (File Upload):** ربط أزرار رفع الصور والملفات (Thumbnails, PDFs) لتعمل عبر نظام Storage لارافيل.
- [ ] **الإشعارات والرسائل:** إضافة رسائل (SweetAlert/Toastr) عند إتمام عملية في الداشبورد (مثل "تم حفظ المقال بنجاح").

---

### طريقة العمل (Workflow):
1. سيتم تحديث هذه الخطة (وضع علامة `[x]`) عند إتمام كل جزء.
2. سيتم البدء بملفات **الموقع الأمامي (Frontend)** أولاً أو **لوحة التحكم (Dashboard)** حسب اختيارك.
