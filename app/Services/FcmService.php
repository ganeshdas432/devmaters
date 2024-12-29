<?php
namespace App\Services;

use App\Models\FcmConfig;

class FcmService
{
    public static function getConfig()
    {
        return FcmConfig::first();
    }

    public static function getApiKey()
    {
        return optional(self::getConfig())->api_key;
    }

    public static function getProjectId()
    {
        return optional(self::getConfig())->project_id;
    }

    // Add other methods as needed
}
