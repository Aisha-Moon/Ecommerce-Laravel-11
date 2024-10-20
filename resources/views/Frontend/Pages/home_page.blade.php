@extends('Frontend.layout.app')
@section('content')
    @include('Frontend.components.MenuBar')
    @include('Frontend.components.HeroSlider')
    @include('Frontend.components.TopCategories')
    @include('Frontend.components.ExclusiveProducts')
    @include('Frontend.components.TopBrands')
    @include('Frontend.components.Footer') 
    <script>
        (async ()=>{
            await Category();
            await Slider();
            await TopCategory();
            await  TopBrand();
            $(".preloader").delay(90).fadeOut(100).addClass('loaded');
            await loadProductsByCategory('popular', 'PopularItem');   // Popular products
            await loadProductsByCategory('new', 'NewItem');           // New products
            await loadProductsByCategory('top', 'TopItem');           // Top products
            await loadProductsByCategory('special', 'SpecialItem');   // Special products
            await loadProductsByCategory('regular', 'RegularItem');   // Regular products
            await loadProductsByCategory('trending', 'TrendingItem');
            await Trending();
          
          
        })()
    </script>

@endsection

