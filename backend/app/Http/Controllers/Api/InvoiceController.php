<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    private $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index()
    {
        $invoices = auth()->user()->invoices()
                         ->with('subscription.membershipPlan')
                         ->latest()
                         ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }

    public function show($id)
    {
        $invoice = auth()->user()->invoices()
                        ->with('subscription.membershipPlan')
                        ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $invoice
        ]);
    }

    public function downloadPDF($id)
    {
        $invoice = auth()->user()->invoices()
                        ->with('subscription.membershipPlan')
                        ->findOrFail($id);

        $pdf = $this->invoiceService->generatePDF($invoice);

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="fatura-' . $invoice->invoice_number . '.pdf"');
    }
}