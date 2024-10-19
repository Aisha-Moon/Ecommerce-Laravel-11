@extends('Frontend.layout.app')
@section('content')
    @include('Frontend.components.MenuBar')
    @include('Frontend.components.HeroSlider')
    @include('Frontend.components.TopCategories')
    @include('Frontend.components.ExclusiveProducts')
    @include('Frontend.components.TopBrands')
    @include('Frontend.components.Footer') 

@endsection

