<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\BudgetEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function getCategories()
    {
        $categories = BudgetCategory::orderBy('type')->orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function getEntries(Request $request)
    {
        $query = BudgetEntry::with('category')
            ->where('user_id', auth()->id())
            ->orderBy('entry_date', 'desc');

        if ($request->month && $request->year) {
            $query->whereMonth('entry_date', $request->month)
                  ->whereYear('entry_date', $request->year);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $entries = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $entries
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'budget_category_id' => 'required|exists:budget_categories,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'entry_date' => 'required|date',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurring_type' => 'nullable|in:daily,weekly,monthly,yearly'
        ]);

        $entry = BudgetEntry::create([
            'user_id' => auth()->id(),
            'budget_category_id' => $request->budget_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'type' => $request->type,
            'entry_date' => $request->entry_date,
            'is_recurring' => $request->is_recurring ?? false,
            'recurring_type' => $request->recurring_type
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kayıt başarıyla eklendi',
            'data' => $entry->load('category')
        ]);
    }

    public function update(Request $request, $id)
    {
        $entry = BudgetEntry::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'budget_category_id' => 'required|exists:budget_categories,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'entry_date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $entry->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kayıt başarıyla güncellendi',
            'data' => $entry->load('category')
        ]);
    }

    public function destroy($id)
    {
        $entry = BudgetEntry::where('user_id', auth()->id())->findOrFail($id);
        $entry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kayıt başarıyla silindi'
        ]);
    }

    public function getStats(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $entries = BudgetEntry::where('user_id', auth()->id())
            ->whereMonth('entry_date', $month)
            ->whereYear('entry_date', $year)
            ->with('category')
            ->get();

        $totalIncome = $entries->where('type', 'income')->sum('amount');
        $totalExpense = $entries->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $expensesByCategory = $entries->where('type', 'expense')
            ->groupBy('budget_category_id')
            ->map(function ($items) {
                return [
                    'category' => $items->first()->category->name,
                    'color' => $items->first()->category->color,
                    'total' => $items->sum('amount'),
                    'count' => $items->count()
                ];
            })->values();

        $incomesByCategory = $entries->where('type', 'income')
            ->groupBy('budget_category_id')
            ->map(function ($items) {
                return [
                    'category' => $items->first()->category->name,
                    'color' => $items->first()->category->color,
                    'total' => $items->sum('amount'),
                    'count' => $items->count()
                ];
            })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'balance' => $balance,
                'expenses_by_category' => $expensesByCategory,
                'incomes_by_category' => $incomesByCategory,
                'month' => $month,
                'year' => $year
            ]
        ]);
    }
}