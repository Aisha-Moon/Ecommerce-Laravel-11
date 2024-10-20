@extends('frontend.layout.app')
@section('content')
    @include('frontend.components.MenuBar')
    @include('frontend.components.PolicyList')
    @include('frontend.components.Footer')
  <script>
      (async () => {
           await Category();
            await Policy();
            $(".preloader").delay(90).fadeOut(100).addClass('loaded');

        })()
  </script>
@endsection
