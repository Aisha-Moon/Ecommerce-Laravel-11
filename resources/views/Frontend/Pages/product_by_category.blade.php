@extends('frontend.layout.app')
@section('content')
    @include('frontend.components.MenuBar')
    @include('frontend.components.ByCategoryList')
    @include('frontend.components.TopBrands')
    @include('frontend.components.Footer')
    <script>
        (async () => {
            await Category();
            await fetchProductsByCategory();
            $(".preloader").delay(90).fadeOut(100).addClass('loaded');

            await TopBrands();
        })()
    </script>
@endsection





