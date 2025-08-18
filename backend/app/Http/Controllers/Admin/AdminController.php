<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Admin paneli için base controller
     */
    public function __construct()
    {
        $this->middleware('admin.auth')->except(['showLogin', 'login']);
    }

    /**
     * Admin paneli için success mesajı
     */
    protected function successResponse(string $message, $data = null)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Admin paneli için error mesajı
     */
    protected function errorResponse(string $message, $errors = null)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $errors
            ], 422);
        }

        return back()->with('error', $message)->withErrors($errors ?? []);
    }

    /**
     * Exception handling için helper
     */
    protected function handleException(\Exception $e, string $action = 'perform this action')
    {
        Log::error('Admin panel error: ' . $e->getMessage(), [
            'user_id' => auth()->id(),
            'url' => request()->url(),
            'action' => $action,
            'trace' => $e->getTraceAsString()
        ]);

        return $this->errorResponse("Failed to {$action}. Please try again.");
    }

    /**
     * Sayfalama için default değerler
     */
    protected function getPaginationData(Request $request): array
    {
        return [
            'per_page' => $request->get('per_page', 15),
            'search' => $request->get('search'),
            'sort' => $request->get('sort', 'created_at'),
            'direction' => $request->get('direction', 'desc')
        ];
    }
}