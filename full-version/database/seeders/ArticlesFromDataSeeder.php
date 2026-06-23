<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * ArticlesFromDataSeeder
 * Imports 19 small research articles (PDF) from /data/مقالات صغيرة/
 * Each article has a pdf_path as its content — body points to the PDF URL.
 */
class ArticlesFromDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();

        // Each article: [ar_title, en_title, category, pdf_filename]
        $articles = [
            [
                'ar' => 'نهاية إنجيل مرقس وتلاعب القمص — رداً على القس بسيط',
                'en' => 'The End of Mark\'s Gospel and the Priest\'s Manipulation',
                'cat' => 'critique',
                'pdf' => 'نهاية إنجيل مرقس وتلاعب القمص . . رداً على القس بسيط.pdf',
            ],
            [
                'ar' => 'الفاصلة اليوحناوية أم سلامات بولس — يوحنا الأولى 5:7 — نظرات في الترجمات',
                'en' => 'The Johannine Comma or Paul\'s Greetings — 1 John 5:7 — A Study in Translations',
                'cat' => 'critique',
                'pdf' => 'الفاصلة اليوحناوية أم سلامات بولس .. يوحنا الأولى 5-7 .. نظرات في الترجمات ) بالعربية والإنجليزية.pdf',
            ],
            [
                'ar' => 'الفاصلة اليوحناوية (1يو5:7) — نظرات في أقوال العلماء',
                'en' => 'The Johannine Comma (1 John 5:7) — Scholars\' Statements',
                'cat' => 'critique',
                'pdf' => 'الفاصلة اليوحناوية (1يو5-7) نظرات في أقوال العلماء.pdf',
            ],
            [
                'ar' => 'ملكات اليمين في الكتاب المقدس بعهديه وحسب التفاسير المسيحية',
                'en' => 'Concubines in the Bible According to Christian Commentaries',
                'cat' => 'critique',
                'pdf' => 'ملكات  اليمين في الكتاب المقدس بعهديه وحسب التفاسير المسيحية  - بصور المراجع المسيحية.pdf',
            ],
            [
                'ar' => 'هل المسيح إله كامل بحسب أقوال الآباء',
                'en' => 'Is Christ Fully God According to the Church Fathers?',
                'cat' => 'doctrine',
                'pdf' => 'هل المسيح إله كامل بحسب أقوال الآباء.pdf',
            ],
            [
                'ar' => 'عبادة النار وتمجيدها في المسيحية',
                'en' => 'Fire Worship and Glorification in Christianity',
                'cat' => 'comparative',
                'pdf' => 'عبادة النار وتمجيدها في المسيحية.pdf',
            ],
            [
                'ar' => 'هل تعبد الملاك والأعمى كما أمرك الكتاب — رداً على القس بسيط',
                'en' => 'Do You Worship the Angel and the Blind as the Bible Commands?',
                'cat' => 'critique',
                'pdf' => 'هل تعبد الملاك والأعمي كما أمرك الكتاب ؟ ؟ .. رداً علي القس بسيط . . نظرات في المخطوطات.pdf',
            ],
            [
                'ar' => 'علماء المسيحية يصرخون بإتهام اليهود لمريم بالزنا',
                'en' => 'Christian Scholars Shout: Jews Accused Mary of Adultery',
                'cat' => 'history',
                'pdf' => 'علماء المسيحية يصرخون بإتهام اليهود لمريم بالزنا - مراجع مسيحية  عربية مصورة.pdf',
            ],
            [
                'ar' => 'قصة الملاك في المخطوطة السينائية والكذبة الباباوية — رداً على البابا شنودة الثالث',
                'en' => 'The Angel Story in the Sinai Codex and the Papal Lie',
                'cat' => 'critique',
                'pdf' => 'قصة الملاك في المخطوطة السينائية والكذبة الباباوية ..  رداً على البابا شنودة الثالث .. نظرات في المخطوطات.pdf',
            ],
            [
                'ar' => 'كل الكتاب وكشف الكذاب — رداً على القس بسيط',
                'en' => 'All Scripture and Exposing the Liar — A Response to the Simple Priest',
                'cat' => 'critique',
                'pdf' => 'كل الكتاب .! وكشف الكذاب . . رداً علي القس بسيط . نظرات في المخطوطات.pdf',
            ],
            [
                'ar' => 'الزانية تصرخ لم أرَ المسيح أبداً — رداً على القس بسيط',
                'en' => 'The Adulteress Cries: I Never Saw Christ — A Response',
                'cat' => 'critique',
                'pdf' => 'الزانية تصرخ لم آري المسيح أبداً . . رداً علي القس بسيط . . نظرات في المخطوطات والمراجع العربية المصورة.pdf',
            ],
            [
                'ar' => 'مسحة زيت الميرون — حقيقة مؤلمة — الشرح بالصور من المراجع المسيحية',
                'en' => 'The Unction of Myron Oil — A Painful Truth',
                'cat' => 'doctrine',
                'pdf' => 'مسحة زيت الميرون .. حقيقة مؤلمة .. الشرح بالصور من المراجع المسيحية.pdf',
            ],
            [
                'ar' => 'هل الإيمان من يسوع كلام يسوع فقط أم بولس أيضاً؟ نظرات في المخطوطات',
                'en' => 'Is Faith from Jesus Only or Also from Paul? A Manuscript Study',
                'cat' => 'critique',
                'pdf' => 'هل الإيمان من يسوع كلام يسوع فقط أم بولس أيضاً ؟ نظرات في المخطوطات.pdf',
            ],
            [
                'ar' => 'هل آمن أريوس بلاهوت يسوع؟ رداً على القس بسيط',
                'en' => 'Did Arius Believe in the Divinity of Jesus?',
                'cat' => 'history',
                'pdf' => 'هل آمن أريوس بلاهوت يسوع ؟ رداً علي القس بسيط . . مراجع عربية مصورة.pdf',
            ],
            [
                'ar' => 'علماء المسيحية يصرخون بنكاح العذراء في الثانية عشر من عمرها',
                'en' => 'Christian Scholars: The Virgin Was Married at Twelve',
                'cat' => 'history',
                'pdf' => 'علماء المسيحية يصرخون بنكاح العذراء في الثانية عشر من عمرها.pdf',
            ],
            [
                'ar' => 'ملكي صادق يتحدى يسوع — رداً على البابا شنودة',
                'en' => 'Melchizedek Challenges Jesus — A Response to Pope Shenouda',
                'cat' => 'critique',
                'pdf' => 'ملكي صادق يتحدى يسوع .. رداً على البابا شنودة.pdf',
            ],
            [
                'ar' => 'نصارى أم مسيحيين — بحث من مخطوطات وآباء وعلماء المسيحية',
                'en' => 'Nasara or Christians? A Study from Manuscripts and Church Fathers',
                'cat' => 'history',
                'pdf' => 'نصاري أم مسيحيين , بحث من مخطوطات وآباء وعلماء المسيحية.pdf',
            ],
            [
                'ar' => 'المسيح لم يقل يا أبتاه اغفر لهم — نظرات في المخطوطات — رداً على الأنبا بيشوي',
                'en' => 'Christ Never Said "Father Forgive Them" — Manuscript Analysis',
                'cat' => 'critique',
                'pdf' => 'المسيح لم يقل يا ابتاه اغفر لهم .نظرات في المخطوطات - رداً على الأنبا بيشوي.pdf',
            ],
            [
                'ar' => 'هل آمن بولس بأن المسيح هو الخالق؟ نظرات في نصوص وتفاسير الكتاب المقدس',
                'en' => 'Did Paul Believe Christ is the Creator? A Textual Study',
                'cat' => 'critique',
                'pdf' => 'هل آمن بولس بأن المسيح هو الخالق؟ نظرات في نصوص  وتفاسير الكتاب المقدس - مصور من المراجع المسيحية.pdf',
            ],
        ];

        $existingSlugs = DB::table('articles')->pluck('slug')->toArray();
        $inserted = 0;

        foreach ($articles as $a) {
            $slug = 'article-' . Str::slug($a['ar'], '-', 'ar');
            // Fallback slug from English if Arabic slug fails
            if (empty(trim($slug, '-'))) {
                $slug = 'article-' . Str::slug($a['en']);
            }
            // Ensure uniqueness
            $baseSlug = $slug;
            $counter  = 1;
            while (in_array($slug, $existingSlugs)) {
                $slug = $baseSlug . '-' . $counter++;
            }
            $existingSlugs[] = $slug;

            // Skip if pdf_path already registered
            $exists = DB::table('articles')
                ->where('excerpt->ar', 'like', '%' . $a['pdf'] . '%')
                ->exists();
            if ($exists) continue;

            DB::table('articles')->insertOrIgnore([
                'title'        => json_encode(['ar' => $a['ar'], 'en' => $a['en']], JSON_UNESCAPED_UNICODE),
                'slug'         => $slug,
                'body'         => json_encode(['ar' => '', 'en' => ''], JSON_UNESCAPED_UNICODE),
                'excerpt'      => json_encode([
                    'ar'      => $a['ar'],
                    'en'      => $a['en'],
                    'pdf_path'=> 'articles/pdfs/' . $a['pdf'],
                ], JSON_UNESCAPED_UNICODE),
                'category'     => $a['cat'],
                'read_time'    => 10,
                'thumbnail'    => null,
                'published_at' => now()->toDateTimeString(),
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
            $inserted++;
        }

        $this->command->info("✅ Inserted {$inserted} articles from data folder.");
    }
}
