<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BabyName;
use Illuminate\Http\Request;

class BabyNameController extends Controller
{
    public function index(Request $request)
    {
        $query = BabyName::query();

        if ($request->has('gender') && $request->gender !== 'all') {
            $query->byGender($request->gender);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('popular') && $request->popular) {
            $query->popular();
        }

        $names = $query->orderBy('name')->paginate(20);

        return response()->json($names);
    }

    public function show($id)
    {
        $name = BabyName::findOrFail($id);
        return response()->json($name);
    }

    public function random(Request $request)
    {
        $query = BabyName::query();

        if ($request->has('gender') && $request->gender !== 'all') {
            $query->byGender($request->gender);
        }

        $names = $query->inRandomOrder()->limit(10)->get();

        return response()->json($names);
    }
}