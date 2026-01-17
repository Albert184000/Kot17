{{-- resources/views/admin/utilities/print.blade.php --}}
@extends('layouts.print')

@section('content')
<div class="print-invoice">
  <div class="header">
    <h1>វិក័យប័ត្រ{{ $type === 'electricity' ? 'ភ្លើង' : 'ទឺក' }}</h1>
    <div>ខែ: {{ $month }}</div>
    <div>ថ្ងៃបោះពុម្ព: {{ now()->format('d/m/Y') }}</div>
  </div>

  <table class="invoice-table">
    <thead>
      <tr>
        <th>ល.រ</th>
        <th>បន្ទប់</th>
        <th>ចំនួននាក់</th>
        <th>គីឡូប្រើ</th>
        <th>ត្រូវបង់/បន្ទប់</th>
        <th>ត្រូវបង់/មនុស្ស</th>
        <th>ហត្ថលេខា</th>
      </tr>
    </thead>
    <tbody>
      @foreach($bill->readings as $index => $reading)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $reading->room_name }}</td>
        <td>{{ $reading->people_count ?? 1 }}</td>
        <td>{{ number_format($reading->total_units, 2) }}</td>
        <td>{{ number_format($reading->amount_final, 0) }}៛</td>
        <td>{{ number_format($reading->amount_per_person, 0) }}៛</td>
        <td class="signature-cell"></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<style>
.invoice-table { width: 100%; border-collapse: collapse; }
.invoice-table th, .invoice-table td { 
  border: 1px solid #000; 
  padding: 8px; 
  text-align: center; 
}
.signature-cell { height: 40px; }
@media print {
  @page { size: A4; margin: 15mm; }
}
</style>
@endsection
