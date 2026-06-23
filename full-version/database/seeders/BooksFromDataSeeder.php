<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * BooksFromDataSeeder
 * Imports 9 printed books from /data/الكتب المطبوعة/ into the books table.
 */
class BooksFromDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateTimeString();

        $books = [
            [
                'title'      => json_encode(['ar' => 'عبادة مريم في المسيحية والظهورات المريمية', 'en' => 'Worship of Mary in Christianity and Marian Apparitions'], JSON_UNESCAPED_UNICODE),
                'author'     => json_encode(['ar' => 'معاذ عليان', 'en' => 'Moaz Alian'], JSON_UNESCAPED_UNICODE),
                'year'       => null,
                'category'   => 'doctrine',
                'cover_image'=> null,
                'pdf_path'   => 'books/pdfs/عبادة مريم في المسيحية والظهورات المريمية - معاذ عليان.pdf',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => json_encode(['ar' => 'البيان شبهات وردود', 'en' => 'Al-Bayan: Doubts and Responses'], JSON_UNESCAPED_UNICODE),
                'author'     => json_encode(['ar' => 'معاذ عليان ومحمود عليان ومحمد شاهين', 'en' => 'Moaz Alian, Mahmoud Alian & Mohammad Shaheen'], JSON_UNESCAPED_UNICODE),
                'year'       => null,
                'category'   => 'critique',
                'cover_image'=> null,
                'pdf_path'   => 'books/pdfs/البيان شبهات وردود - معاذ ومحمود عليان ومحمد شاهين.pdf',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => json_encode(['ar' => 'المجهول في حياة البتول', 'en' => 'The Unknown in the Life of the Virgin'], JSON_UNESCAPED_UNICODE),
                'author'     => json_encode(['ar' => 'معاذ عليان ومحمود عليان', 'en' => 'Moaz Alian & Mahmoud Alian'], JSON_UNESCAPED_UNICODE),
                'year'       => null,
                'category'   => 'doctrine',
                'cover_image'=> null,
                'pdf_path'   => 'books/pdfs/المجهول في حياة البتول - معاذ عليان ومحمود عليان.pdf',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => json_encode(['ar' => 'من كتب التوراة', 'en' => 'From the Books of the Torah'], JSON_UNESCAPED_UNICODE),
                'author'     => json_encode(['ar' => 'معاذ عليان ومحمود عليان', 'en' => 'Moaz Alian & Mahmoud Alian'], JSON_UNESCAPED_UNICODE),
                'year'       => null,
                'category'   => 'critique',
                'cover_image'=> null,
                'pdf_path'   => 'books/pdfs/من كتب التوراة - معاذ عليان ومحمود عليان.pdf',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => json_encode(['ar' => 'صفات الإله والأنبياء في كتب اليهود والنصارى', 'en' => 'Attributes of God and Prophets in Jewish and Christian Scriptures'], JSON_UNESCAPED_UNICODE),
                'author'     => json_encode(['ar' => 'معاذ عليان', 'en' => 'Moaz Alian'], JSON_UNESCAPED_UNICODE),
                'year'       => null,
                'category'   => 'doctrine',
                'cover_image'=> null,
                'pdf_path'   => 'books/pdfs/صفات الإله والأنبياء في كتب اليهود والنصارى - معاذ عليان.pdf',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => json_encode(['ar' => 'غزوات الإسلام تتحدى البهتان', 'en' => 'Islamic Conquests Challenge the Slander'], JSON_UNESCAPED_UNICODE),
                'author'     => json_encode(['ar' => 'معاذ عليان', 'en' => 'Moaz Alian'], JSON_UNESCAPED_UNICODE),
                'year'       => null,
                'category'   => 'other',
                'cover_image'=> null,
                'pdf_path'   => 'books/pdfs/غـزوات الإسـلام تتحـدى البهتـان.pdf',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => json_encode(['ar' => 'معجزات سيد المرسلين تتحدى المشككين', 'en' => 'Miracles of the Prophet Challenge the Skeptics'], JSON_UNESCAPED_UNICODE),
                'author'     => json_encode(['ar' => 'معاذ عليان', 'en' => 'Moaz Alian'], JSON_UNESCAPED_UNICODE),
                'year'       => null,
                'category'   => 'other',
                'cover_image'=> null,
                'pdf_path'   => 'books/pdfs/معجزات سيد المرسلين تتحدى المشككين - معاذ عليان.pdf',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => json_encode(['ar' => 'مقدمات في حوار النصرانيات', 'en' => 'Introductions to Christian Dialogue'], JSON_UNESCAPED_UNICODE),
                'author'     => json_encode(['ar' => 'معاذ عليان', 'en' => 'Moaz Alian'], JSON_UNESCAPED_UNICODE),
                'year'       => null,
                'category'   => 'critique',
                'cover_image'=> null,
                'pdf_path'   => 'books/pdfs/مقدمات في حوار النصرانيات.pdf',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => json_encode(['ar' => 'هل رسول الإسلام أشرف الخلق؟ وهل يستحق هذا اللقب؟', 'en' => 'Is the Messenger of Islam the Best of Creation?'], JSON_UNESCAPED_UNICODE),
                'author'     => json_encode(['ar' => 'معاذ عليان', 'en' => 'Moaz Alian'], JSON_UNESCAPED_UNICODE),
                'year'       => null,
                'category'   => 'other',
                'cover_image'=> null,
                'pdf_path'   => 'books/pdfs/هل رسول الإسلام أشرف الخلق ؟ وهل يستحق هذا اللقب ؟.pdf',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $existingTitles = DB::table('books')->pluck('pdf_path')->toArray();
        $toInsert = array_filter($books, fn($b) => !in_array($b['pdf_path'], $existingTitles));

        if (empty($toInsert)) {
            $this->command->info('✅ All books already exist — nothing to insert.');
            return;
        }

        DB::table('books')->insert(array_values($toInsert));
        $this->command->info('✅ Inserted ' . count($toInsert) . ' books from data folder.');
    }
}
