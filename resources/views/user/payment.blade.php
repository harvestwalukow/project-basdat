@extends('layouts.user')

@section('title', 'Pembayaran - PawsHotel')

@section('body-style', 'style=background-image:url("/img/bg2.png");background-size:cover;background-attachment:fixed;background-repeat:no-repeat;')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="bg-white shadow-md rounded-lg border p-6" style="background-color:#fff9f2; border-color:#f9a826;">
    <div class="text-center mb-6">
      <h1 class="text-3xl font-bold mb-2" style="color:#4b3d2a;">Pembayaran Reservasi</h1>
      <p class="text-gray-600">Nomor Transaksi: <span class="font-semibold" style="color:#f9a826;">{{ $orderId }}</span></p>
    </div>

    <div class="text-center mb-6">
      <div class="inline-block p-8 bg-white rounded-lg border-2" style="border-color:#f9a826;">
        <svg class="w-24 h-24 mx-auto mb-4" style="color:#f9a826;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
        </svg>
        <p class="text-gray-700 mb-4">Klik tombol di bawah untuk melanjutkan pembayaran</p>
        <button id="pay-button" class="px-8 py-3 rounded-lg text-white font-semibold hover:opacity-90 transition shadow-lg" style="background-color:#f9a826;">
          Bayar Sekarang
        </button>
      </div>
    </div>

    <div class="mt-6 p-4 rounded-lg" style="background-color:#ffe2b9;">
      <h3 class="font-semibold mb-2" style="color:#4b3d2a;">📝 Informasi Pembayaran</h3>
      <ul class="text-sm space-y-2" style="color:#5c4b35;">
        <li>• Anda akan diarahkan ke halaman pembayaran Midtrans</li>
        <li>• Pilih metode pembayaran yang tersedia (Bank Transfer, E-Wallet, Kartu Kredit, dll)</li>
        <li>• Ikuti instruksi pembayaran yang diberikan</li>
        <li>• Status pembayaran akan diperbarui secara otomatis</li>
        <li>• Reservasi Anda akan dikonfirmasi setelah pembayaran berhasil</li>
      </ul>
    </div>

    <div class="mt-4 text-center">
      <a href="{{ route('dashboard') }}" class="text-sm hover:underline" style="color:#7b6650;">
        Kembali ke Dashboard
      </a>
    </div>
  </div>
</div>

@push('scripts')
<!-- Midtrans Snap JS -->
@if(config('midtrans.is_production'))
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif

<script type="text/javascript">
  document.getElementById('pay-button').onclick = function(){
    // Trigger snap popup
    snap.pay('{{ $snapToken }}', {
      // Optional
      onSuccess: function(result){
        console.log('Payment Success:', result);
        window.location.href = '{{ route('payment.finish') }}' + 
          '?order_id=' + result.order_id + 
          '&status_code=' + result.status_code + 
          '&transaction_status=' + result.transaction_status +
          '&payment_type=' + (result.payment_type || 'unknown');
      },
      // Optional
      onPending: function(result){
        console.log('Payment Pending:', result);
        window.location.href = '{{ route('payment.finish') }}' + 
          '?order_id=' + result.order_id + 
          '&status_code=' + result.status_code + 
          '&transaction_status=' + result.transaction_status +
          '&payment_type=' + (result.payment_type || 'unknown');
      },
      // Optional
      onError: function(result){
        console.log('Payment Error:', result);
        window.location.href = '{{ route('payment.finish') }}' + 
          '?order_id=' + result.order_id + 
          '&status_code=' + result.status_code + 
          '&transaction_status=error' +
          '&payment_type=' + (result.payment_type || 'unknown');
      },
      onClose: function(){
        console.log('Customer closed the popup without finishing the payment');
        // You can add custom behavior here if needed
        // For example, show a message or redirect
      }
    });
  };
</script>
@endpush
@endsection

