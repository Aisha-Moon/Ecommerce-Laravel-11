<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Anil z">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords" content="ecommerce, electronics store, Fashion store, furniture store, bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">

    <title>Apple Shop - eCommerce</title>
    <link type="image/x-icon" href="{{ asset('frontend/images/favicon.png') }}" rel="shortcut icon">

    <link href="{{ asset('frontend/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- Icon Font CSS -->
    <link href="{{ asset('frontend/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/linearicons.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/flaticon.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/simple-line-icons.css') }}" rel="stylesheet">

    <link href="{{ asset('frontend/css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/slick-theme.css') }}" rel="stylesheet">

    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/responsive.css') }}" rel="stylesheet">

    <script src="{{ asset('frontend/js/jquery-3.6.0.min.js') }}"></script>
        <!-- Add Toastify CSS -->
        <link href="{{asset('admin/css/toastify.min.css')}}" rel="stylesheet" />
        <script src="{{asset('admin/js/toastify-js.js')}}"></script>
    
    <script src="{{ asset('frontend/js/axios.min.js') }}"></script>
    <script src="{{asset('admin/js/config.js')}}"></script>



</head>

<body>

    <div class="preloader">
        <div class="lds-ellipsis">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div>
        @yield('content')
    </div>

    <script src="{{ asset('frontend/js/scripts.js') }}"></script>
    <script src="{{ asset('frontend/js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('frontend/js/popper.min.js') }}"></script>
    <script src="{{ asset('frontend/bootstrap/js/bootstrap.min.js') }}"></script>

</body>

</html>
