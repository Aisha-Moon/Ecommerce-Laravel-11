@extends('frontend.layout.app')
@section('content')
    @include('frontend.components.MenuBar')
    @include('frontend.components.PaymentMethodList')
    @include('frontend.components.CartList')
    @include('frontend.components.TopBrands')
    @include('frontend.components.Footer')
    <script>
        (async () => {
            await CartList();
            $(".preloader").delay(90).fadeOut(100).addClass('loaded');
        })()
    </script>
@endsection
