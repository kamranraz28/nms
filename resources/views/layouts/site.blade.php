<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="author" content="a2i">
    <meta name="csrf-token" content="{{csrf_token()}}"/>
    <title>@yield('title', @ $sitesetting->{'title_'. app()->getLocale()} )</title>
    <meta name="title" content="Digital Platform For People On The Move">
    <meta name="description" content="Digital Platform For People On The Move" />
    <meta property="og:site_name" content="">
    <meta property="og:url" content="https://dp4pm.org">
    <meta name="og:title" content="dp4pm.org">
    <meta property="og:description" content="Digital Platform For People On The Move">
    <meta property="og:image" itemprop="image" content="">
    <meta property="og:updated_time" content="1634902209">
    <meta property="og:type" content="website">
    <meta property="og:image:width" content="300">
    <meta property="og:image:height" content="300">
    <meta property="twitter:image" content="">
    <meta property="twitter:description" content="Digital Platform For People On The Move">
    <meta property="twitter:card" content="summary_large_image">
    @if ($sitesetting->favicon)
    <link href="{{ asset('storage/'.$sitesetting->favicon) }}" type="image/x-icon" rel="shortcut icon" />    
    @else
    <link href="{{ asset('site/assets/images/favicon.png') }}" type="image/x-icon" rel="shortcut icon" />
    @endif


    <link href="https://fonts.googleapis.com/css2?family=Baloo+Da+2:wght@400;500;600;700&family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('site/assets/css/style.css') }}">



    
    <style> 
        .header, .footer{
            background:#ffffff;
        }

        .navbar ul li a:hover, .navbar-brand1:hover, .footer-nav p a:hover {
          color: #282828; 
          font-weight: 1000;
        }

        .navbar-logo1 a img {
            height: 60px;
        }

        .navbar-logo a img {
            height: 60px;
        }



        body .uwy.userway_p1 .uai {
            top: 51%!important;
            transform:translateY(-50%)!important
        }

        figcaption {
            display: none;
        }

        .active {
            background-color: rgb(247, 244, 244)!important;
            font-weight: 300;
        }
    </style>


    <style>
        .dropbtn {
        background-color: none;
        color: #04AA6D;
        font-size: 16px;
        border: none;
        font-weight: 900;
        }
        
        .dropdown {
        position: relative;
        display: inline-block;
        margin: 0 5px;
        }
        
        .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        }
        
        .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        margin-top: 5px
        }
        
        .dropdown-content a:hover {background-color: #ddd!important;}
        
        .dropdown:hover .dropdown-content {display: block;}
        
        .dropdown:hover .dropbtn {background-color: #ddd!important;}
        .dropbtn:hover {margin-bottom: 5px}
    </style>

    {{-- Tree Menu--}}
    <style>
        .tree, .tree ul {
            margin:0;
            padding:0;
            list-style:none
        }
        .tree ul {
            margin-left:1em;
            position:relative
        }
        .tree ul ul {
            margin-left:.5em
        }
        .tree ul:before {
            content:"";
            display:block;
            width:0;
            position:absolute;
            top:0;
            bottom:0;
            left:0;
            border-left:1px solid
        }
        .tree li {
            margin:0;
            padding:0 1em;
            line-height:2em;
            color:#369;
            font-weight:700;
            position:relative
        }
        .tree ul li:before {
            content:"";
            display:block;
            width:10px;
            height:0;
            border-top:1px solid;
            margin-top:-1px;
            position:absolute;
            top:1em;
            left:0
        }
        .tree ul li:last-child:before {
            background:#fff;
            height:auto;
            top:1em;
            bottom:0
        }
        .indicator {
            margin-right:5px;
        }
        .tree li a {
            text-decoration: none;
            color:#369;
        }
        .tree li button, .tree li button:active, .tree li button:focus {
            text-decoration: none;
            color:#369;
            border:none;
            background:transparent;
            margin:0px 0px 0px 0px;
            padding:0px 0px 0px 0px;
            outline: 0;
        }
    </style>
    {{-- Tree Menu--}}


    




    @yield('styles')

</head>

<body>
    
    {{-- @include('layouts.partials.svgload') --}}
    

    @yield('content-top')
    
    @yield('content-bottom')

    
    @include('layouts.partials.footer')

    <script src="{{ asset('site/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('site/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('site/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('site/assets/js/scripts.js') }}"></script>
    <script>
        new WOW().init();
    </script>

    @yield('scripts')

    {{-- <script>(function(d){var s = d.createElement("script");s.setAttribute("data-account", "Gm4AwueaAb");s.setAttribute("src", "https://cdn.userway.org/widget.js");(d.body || d.head).appendChild(s);})(document)</script><noscript>Please ensure Javascript is enabled for purposes of <a href="https://userway.org">website accessibility</a></noscript> --}}
</body>

</html>