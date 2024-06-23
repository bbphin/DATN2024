<form action="{{ route('payment.vnpay') }}" method="post">
    @csrf
    <button type="submit">thanh toan vnpay</button>
</form>
{{-- <form action="{{ route('payment.zalo') }}" method="post">
    @csrf
     <button type="submit">thanh toan zalo</button> 
</form> --}}



