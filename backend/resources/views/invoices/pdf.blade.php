<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fatura #{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-info { float: left; width: 50%; }
        .invoice-info { float: right; width: 50%; text-align: right; }
        .clear { clear: both; }
        .customer-info { margin: 20px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items-table th { background-color: #f2f2f2; }
        .total { text-align: right; margin-top: 20px; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $company['name'] }}</h1>
        <p>Premium Üyelik Faturası</p>
    </div>

    <div class="company-info">
        <h3>Şirket Bilgileri</h3>
        <p>{{ $company['name'] }}<br>
        {{ $company['address'] }}<br>
        Vergi No: {{ $company['tax_number'] }}<br>
        Tel: {{ $company['phone'] }}<br>
        E-posta: {{ $company['email'] }}</p>
    </div>

    <div class="invoice-info">
        <h3>Fatura Bilgileri</h3>
        <p>Fatura No: {{ $invoice->invoice_number }}<br>
        Tarih: {{ $invoice->issued_at->format('d.m.Y') }}<br>
        Vade: {{ $invoice->due_at ? $invoice->due_at->format('d.m.Y') : '-' }}<br>
        Durum: {{ $invoice->status === 'paid' ? 'Ödendi' : 'Beklemede' }}</p>
    </div>

    <div class="clear"></div>

    <div class="customer-info">
        <h3>Müşteri Bilgileri</h3>
        <p>{{ $invoice->user->name }}<br>
        {{ $invoice->user->email }}</p>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Hizmet</th>
                <th>Adet</th>
                <th>Birim Fiyat</th>
                <th>Toplam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>₺{{ number_format($item['unit_price'], 2) }}</td>
                <td>₺{{ number_format($item['total'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Ara Toplam: ₺{{ number_format($invoice->amount, 2) }}</p>
        <p>KDV (%18): ₺{{ number_format($invoice->tax_amount, 2) }}</p>
        <h3>Genel Toplam: ₺{{ number_format($invoice->total_amount, 2) }}</h3>
    </div>

    <div class="footer">
        <p>Bu fatura elektronik olarak oluşturulmuştur.</p>
        <p>{{ $company['name'] }} - {{ now()->year }}</p>
    </div>
</body>
</html>