<?php
namespace App\Services\Core;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /**
     * Get a setting by its key, cached forever.
     */
    public function get(string $key, mixed $default = null)
    {
        $settings = Cache::rememberForever('system.settings', function () {
            return Setting::all()->keyBy('key_name');
        });

        if ($settings->has($key)) {
            $setting = $settings->get($key);
            // Returns translatable value if applicable
            return $setting->value ?? $default;
        }

        return $default;
    }

    /**
     * Set a setting and flush the cache.
     */
    public function set(string $key, $value, string $type = 'text'): void
    {
        Setting::updateOrCreate(
            ['key_name' => $key],
            ['value' => $value, 'type' => $type]
        );

        Cache::forget('system.settings');
    }
}
