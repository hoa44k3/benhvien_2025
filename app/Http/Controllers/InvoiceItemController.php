<?php

namespace App\Http\Controllers;
use App\Models\InvoiceItem;
use App\Models\Invoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
     public function index()
    {
        $items = InvoiceItem::with('invoice')->get();
        return view('invoice_items.index', compact('items'));
    }

    public function create()
    {
        $invoices = Invoice::all();
        return view('invoice_items.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'item_type' => 'nullable|in:service,medicine,package,other',
            'item_id' => 'nullable|integer',
            'description' => 'required|string|max:255',
            'quantity' => 'nullable|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
        ]);

        InvoiceItem::create($data);
        return redirect()->route('invoice_items.index')->with('success','Item added.');
    }

    public function edit(InvoiceItem $invoiceItem)
    {
        $invoices = Invoice::all();
        return view('invoice_items.edit', compact('invoiceItem','invoices'));
    }

    public function update(Request $request, InvoiceItem $invoiceItem)
    {
        $data = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'item_type' => 'nullable|in:service,medicine,package,other',
            'item_id' => 'nullable|integer',
            'description' => 'required|string|max:255',
            'quantity' => 'nullable|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
        ]);

        $invoiceItem->update($data);
        return redirect()->route('invoice_items.index')->with('success','Item updated.');
    }

    public function destroy(InvoiceItem $invoiceItem)
    {
        $invoiceItem->delete();
        return redirect()->route('invoice_items.index')->with('success','Item deleted.');
    }
}
