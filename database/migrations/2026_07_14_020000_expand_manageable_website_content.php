<?php

use App\Services\WebsiteContentService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const ORIGINAL_KEYS = [
        'hero_title',
        'hero_subtitle',
        'hero_description',
        'facilities_title',
        'facilities_description',
        'about_title',
        'about_description',
        'contact_title',
        'contact_description',
        'footer_text',
        'welcome_message',
    ];

    public function up(): void
    {
        foreach (WebsiteContentService::definitions() as $key => $definition) {
            $query = DB::table('tb_content')->where('content_key', $key);

            if ($query->exists()) {
                $query->update([
                    'section' => $definition['section'],
                    'content_type' => $definition['type'],
                ]);

                continue;
            }

            DB::table('tb_content')->insert([
                'section' => $definition['section'],
                'content_key' => $key,
                'content_value' => $definition['value'],
                'content_type' => $definition['type'],
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $managedKeys = array_keys(WebsiteContentService::definitions());
        $addedKeys = array_values(array_diff($managedKeys, self::ORIGINAL_KEYS));

        DB::table('tb_content')->whereIn('content_key', $addedKeys)->delete();
        DB::table('tb_content')->whereIn('content_key', self::ORIGINAL_KEYS)->update([
            'section' => '',
            'content_type' => 'text',
        ]);
    }
};
