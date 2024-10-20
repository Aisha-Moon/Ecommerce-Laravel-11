@extends('frontend.layout.app')
@section('content')
    @include('frontend.components.MenuBar')
    @include('frontend.components.ByBrandList')
    @include('frontend.components.TopBrands')
    @include('frontend.components.Footer')
    <script>
        (async () => {
           await Category();
            await fetchProductsByBrand();
            $(".preloader").delay(90).fadeOut(100).addClass('loaded');

            await TopBrand();
        })()
    </script>
@endsection





