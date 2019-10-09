<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

<title>System</title>

    <!-- Scripts -->
    <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>



  <script src="{{ asset('js/app.js') }}"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">



    <!-- Styles -->
    <link href="{{ asset('css/new.css') }}" rel="stylesheet">
     <link href="{{ asset('css/app.css') }}" rel="stylesheet">
     <!-- <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" > -->

</head>
<body>
<a href="/" class="logo">


    <img src="/img/logo.png"></a>



 <header class="header">
        @include('blocks.header')
</header>

<nav class="sidebar">
    @include('blocks.menu', [
        'items'  => [
            'customers-list' => 'Wyszukaj klienta',
            'create-customer' => 'Dodaj klienta',
            'products-list' => 'Produkty',
            'add-incoming-documents' => 'Dokumenty zakupu',
            'users-list' => 'Użytkownicy',
            'create-user' => 'Dodaj użytkownika',




        ],
        'active' => $sidebar_item ?? null
    ])
</nav>

<content class="content">
            @yield('before')
            @yield('content')
            @yield('after')
</content>




 <footer class="footer">
 @include('blocks.footer')
 </footer>

    @yield('scripts')

    <script src="{{ asset('js/crm.js') }}"></script>


</body>
</html>
