<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partnership;
use Illuminate\Http\Request;

class PartnershipController extends Controller
{
    public function index()
    {
        $partnerships = Partnership::where('status', 'active')
            ->select('company_name', 'partnership_type', 'description')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $partnerships
        ]);
    }

    public function stats()
    {
        $stats = [
            'total_partners' => Partnership::where('status', 'active')->count(),
            'total_revenue' => Partnership::where('status', 'active')->sum('total_revenue'),
            'partnership_types' => Partnership::where('status', 'active')
                ->selectRaw('partnership_type, COUNT(*) as count')
                ->groupBy('partnership_type')
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}