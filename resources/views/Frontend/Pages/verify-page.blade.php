@extends('frontend.layout.app')
@section('content')
    @include('frontend.components.MenuBar')
    @include('frontend.components.Verify')
    @include('frontend.components.TopBrands')
    @include('frontend.components.Footer')
    <script>
        (async () => {
            $(".preloader").delay(90).fadeOut(100).addClass('loaded');
        })()
    </script>
@endsection

