<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FooterSetting;
use App\Models\FooterLink;

class FooterController extends Controller
{
    public function getFooterData()
    {
        $settings = FooterSetting::active()->ordered()->get();
        $links = FooterLink::active()->ordered()->get();
        
        $footerData = [
            'general' => [],
            'contact' => [],
            'social' => [],
            'links' => [
                'quick_links' => [],
                'category_links' => []
            ]
        ];
        
        // Process settings
        foreach ($settings as $setting) {
            $value = $setting->value;
            
            if ($setting->type === 'json' && !empty($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                } else {
                    $value = [];
                }
            }
            
            $footerData[$setting->group][$setting->key] = $value;
        }
        
        // Process links
        foreach ($links as $link) {
            $footerData['links'][$link->section][] = [
                'name' => $link->name,
                'url' => $link->url
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $footerData
        ]);
    }
}