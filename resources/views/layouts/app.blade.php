<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>


    <!-- CDN Bootstrap   -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


    <!-- CDN font-awesome  -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"
        integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
        
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <style>
       
        #app
        {
            background-color:#EAF2FF;
        }
        .sidebar
        {
            width:25%;
            height: 80vh;
        }
        .sidebar img{
            border-radius:50px
        }
        .posts
        {
            width:70%;
            margin-left:2%;
        }
        .posts table
        {
            width: 95% ;
            margin:20px auto ;
        }
        .posts thead{
            background-color:#f0f4f7;
            color:#8d98a3 ;
        }
        .posts table td ,  .posts table th{
            padding : 10px
        }
        .posts table a {
            width:90% ;
        }
        .hovering a{
            color:#828282;
            font-size:18px;
            text-decoration:none;
        }
        .hovering:hover
        {
            background-color:#eaf2ff;
            border-radius:20px ;
            transition: 0.3s ease-in-out;
            /* color : #5589d9 ; */
        }
         .portfolio-menu ul li {
            display: inline-block;
            margin: 0;
            list-style: none;
            padding: 10px 15px 10px 0px;
            color: gray;
            cursor: pointer;
            transition: all .5 ease;
        }
        .portfolio-menu ul {
            padding: 0;
        }
        .portfolio-menu ul li a:hover {
           color: #fff;
           background-color: #50575d;
           border-radius : 8px;
           padding:10px
        }

        .portfolio-menu ul li a.active {
           color: #fff;
           background-color: #50575d;
           border-radius : 8px;
           padding:10px
        }
        
        .portfolio-menu ul li a{
            text-decoration:none;
            color:gray
        }



                
        @media screen and (max-width: 599px )  
        {
            .sidebar
            {
                width:92%;
                margin-bottom: 20px;
                height: 40vh;
            }
            .posts
            {
                width:92% ;
                margin-left:0px
            }
            .posts table
            {
                width: 99% ;
            }
            .posts table .need 
            {
                display:none;
            }
        }


        @media screen and (min-width: 600px )  and ( max-width: 767px )
        {
        
            .sidebar
            {
                width:25%
            }
            .sidebar img{
                margin-bottom : 10px
            }
            .sidebar i 
            {
                display:none;
            }
            .hovering {
                margin-bottom : 7px ;
                padding : 10px
            }
            .posts
            {
                width:73%;
            }
            .posts table
            {
                width: 97% ;
            }
            .posts table .need 
            {
                display:none;
            }
        }

        
        @media screen and (min-width: 768px )  and ( max-width: 991px )
        {
            .posts table
            {
                width: 99% ;
            }
            .posts table .need 
            {
                display:none;
            }
        }



    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <!--{{ config('app.name', 'Your Place') }}--> Your Place
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>





</body>
</html>
