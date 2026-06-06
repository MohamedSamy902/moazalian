# 📖 Moaz Alian — Platform Documentation

> **آخر تحديث:** يونيو 2026 | **Laravel:** 11 | **PHP:** 8.2+

منصة متكاملة للشيخ **معاذ عليان** تتضمن لوحة تحكم إدارية وواجهة أمامية متعددة اللغات (AR/EN).

---

## 🏗️ البنية المعمارية (Architecture)

يتبع المشروع نمط **Controller → Service → Repository (CSR)** مع تطبيق صارم لمبادئ SOLID.

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Dashboard/          # Dashboard controllers (CRUD + permissions)
│   │   └── Frontend/           # Public-facing controllers
│   ├── Requests/
│   │   ├── Dashboard/          # Unified Form Requests (Store+Update in one file)
│   │   │   ├── Admins/AdminRequest.php
│   │   │   ├── Courses/CourseRequest.php
│   │   │   ├── QuickResponses/QuickResponseRequest.php
│   │   │   ├── Roles/RoleRequest.php
│   │   │   ├── Users/UserRequest.php
│   │   │   └── Videos/VideoRequest.php
│   │   └── Frontend/
│   │       └── SearchRequest.php   # Sanitizes all search inputs
│   └── Resources/              # API Resources (standardized JSON output)
│       ├── AdminResource.php
│       ├── CourseResource.php
│       ├── QuickResponseResource.php
│       ├── UserResource.php
│       └── VideoResource.php
│
├── Models/                     # All models use SoftDeletes + HasTranslations
│   ├── Admin.php
│   ├── Article.php
│   ├── Book.php
│   ├── Course.php
│   ├── Debate.php
│   ├── QuickReply.php
│   ├── QuickResponse.php
│   ├── User.php
│   ├── Video.php
│   └── VideoReference.php
│
├── Services/
│   ├── Core/
│   │   ├── ImageService.php    # GD-based image compression before upload
│   │   ├── SeoService.php
│   │   └── SettingService.php
│   └── Dashboard/
│       ├── AdminService.php
│       ├── DashboardCourseService.php
│       ├── QuickResponseService.php
│       ├── RoleService.php
│       ├── SectionService.php  # Fixes N+1 in section listing
│       ├── UserService.php
│       └── VideoService.php    # DB::transaction on create/update/delete
│
├── Repositories/
│   ├── Interfaces/             # Contracts (ISP)
│   └── Implementations/        # Concrete classes
│
├── Traits/
│   ├── ApiResponse.php         # Standardized JSON { success, message, data }
│   └── ChecksPermissions.php   # Shared permission check for all controllers
│
└── Providers/
    └── RepositoryServiceProvider.php  # Declarative binding array
```

---

## 🛡️ الأمان (Security)

| المجال | الحماية المُطبّقة |
|--------|-----------------|
| **CSRF** | Laravel's built-in CSRF protection on all POST/PUT/DELETE routes |
| **XSS** | `strip_tags()` in all services + Blade auto-escaping `{{ }}` |
| **SQL Injection** | Eloquent parameterized queries exclusively |
| **Search Input** | `SearchRequest` validates/sanitizes with regex + max length |
| **File Uploads** | MIME type + extension validation + size limits in all Form Requests |
| **Auth** | Separate `auth:admin` guard for dashboard |
| **Permissions** | `ChecksPermissions` trait + Spatie Permission on all dashboard routes |
| **Error Handling** | Global Exception Handler hides stack traces in production |

---

## ⚡ الأداء (Performance)

| التحسين | التفاصيل |
|---------|---------|
| **Eager Loading** | `Video::with('references')->withCount('references')` |
| **N+1 Fix** | `SectionService::getGroupedSections()` uses single PHP groupBy instead of loop |
| **DB Transactions** | All multi-table writes wrapped in `DB::transaction()` |
| **Image Compression** | `ImageService` reduces images to max 1200×1200 @ 80% quality via GD |
| **Query Stats** | `UserService::getUsersStats()` uses single `selectRaw` instead of 4 COUNT queries |
| **Cache** | Settings cached, Section cache invalidated on update |
| **Indexes** | Composite indexes on `(category, published_at)` for videos and articles |

---

## 🗄️ قاعدة البيانات (Database)

### Migration Strategy
- كل جدول في ملف Migration **منفصل** تماماً
- يُستخدم `migrate:fresh --seed` في بيئة التطوير
- يُستخدم الـ Additive Migration لإضافة أعمدة على DB الحالي دون فقد بيانات

### SoftDeletes
جميع الموديلات الرئيسية تستخدم `SoftDeletes`:
`Admin`, `User`, `Video`, `Course`, `Lesson`, `Article`, `Book`, `Debate`, `QuickResponse`, `QuickReply`

### ترتيب الجداول
```
users → admins → settings → courses → lessons → lesson_attachments
→ lesson_comments → videos → video_references → articles → books
→ debates → quick_replies → quick_responses → newsletter_subscribers
→ sections → permission_tables (Spatie)
```

---

## 🚀 الإعداد والتشغيل

```bash
# 1. تثبيت الحزم
composer install
npm install

# 2. إعداد البيئة
cp .env.example .env
php artisan key:generate

# 3. إعداد قاعدة البيانات (بيئة التطوير الجديدة)
php artisan migrate:fresh --seed

# 4. إعداد Storage
php artisan storage:link

# 5. تشغيل الخادم
php artisan serve
npm run dev
```

### بيانات الدخول الافتراضية
| الحقل | القيمة |
|-------|--------|
| **Email** | `admin@moazalian.com` |
| **Password** | `Admin@1234` |

---

## 🔑 نظام الصلاحيات (Roles & Permissions)

### الأدوار المتاحة

| الدور | الصلاحيات |
|-------|-----------|
| **Super Admin** | جميع الصلاحيات بدون استثناء |
| **Editor** | قراءة وإنشاء وتعديل المحتوى (فيديوهات، كورسات، ردود سريعة، أقسام) |

### الصلاحيات المتاحة
```
admins.{view,create,edit,delete}
users.{view,create,edit,delete}
roles.{view,create,edit,delete}
videos.{view,create,edit,delete}
courses.{view,create,edit,delete}
sections.{view,edit}
quick-responses.{view,create,edit,delete}
```

---

## 📦 الحزم المُستخدمة

| الحزمة | الغرض |
|--------|--------|
| `spatie/laravel-permission` | نظام الأدوار والصلاحيات |
| `spatie/laravel-translatable` | دعم المحتوى متعدد اللغات |
| `mcamara/laravel-localization` | توجيه وإدارة اللغات |
| `MohamedSamy902/AdvancedFileUpload` | رفع الملفات والفيديوهات |

---

## 🛣️ Routes Structure

```
/                           → Redirect to frontend home
/ar|en/                     → Frontend home
/ar|en/videos               → Videos listing (with search + category filter)
/ar|en/videos/{slug}        → Single video page
/ar|en/courses              → Courses listing
/ar|en/articles             → Articles listing

/login                      → Admin login
/ar|en/admin/               → Dashboard home
/ar|en/admin/videos         → Video management
/ar|en/admin/courses        → Course management
/ar|en/admin/quick-responses → Quick responses management
/ar|en/admin/sections       → CMS sections management
/ar|en/admin/admins         → Admin accounts management
/ar|en/admin/users          → User management
/ar|en/admin/roles          → Roles & permissions management
```
