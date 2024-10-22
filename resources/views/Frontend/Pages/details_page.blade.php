@extends('frontend.layout.app')
@section('content')
    @include('frontend.components.MenuBar')
    @include('frontend.components.ProductDetails')
    @include('frontend.components.ProductSpecification')
    @include('frontend.components.TopBrands')
    @include('frontend.components.Footer')
  <script>
      (async () => {
        await productDetails();
        await productReview();
            $(".preloader").delay(90).fadeOut(100).addClass('loaded');

        })()
  </script>
@endsection
