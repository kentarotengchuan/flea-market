<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/sanitize.css')}}">
    @yield('css')
    <title>Document</title>
</head>
<body class="body">
    
        <input type="hidden" id="page" name="page" value=
        @if(isset($page))
         {{$page}} 
        @else 
         'all' 
        @endif>
    <header class="header">
        <div class="header__inner">
            <a href="{{route('all')}}" class="header__icon">
                <img class="header__img" src="data:image/svg+xml,%3c?xml%20version='1.0'%20encoding='UTF-8'?%3e%3csvg%20id='_レイヤー_2'%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20370%2036'%3e%3cdefs%3e%3cstyle%3e.cls-1,.cls-2{fill:%23fff;}.cls-2{opacity:0;}%3c/style%3e%3c/defs%3e%3cg%20id='_レイヤー_1-2'%3e%3cg%3e%3crect%20class='cls-2'%20width='370'%20height='36'/%3e%3cg%3e%3cpath%20class='cls-1'%20d='m99.11,25.05c-1.32,6.33-6.08,10.95-14.56,10.95-10.74,0-15.79-7.61-15.79-17.23S74.01,1.19,84.89,1.19c9.02,0,13.38,5.25,14.22,10.95h-7.3c-.74-2.65-2.55-5.25-7.16-5.25-6.03,0-8.33,5.45-8.33,11.64,0,5.7,2.01,11.73,8.53,11.73,4.8,0,6.27-3.19,6.91-5.2h7.35Z'/%3e%3cpath%20class='cls-1'%20d='m136.46,18.47c0,9.43-5.64,17.53-16.62,17.53s-16.18-7.71-16.18-17.43S109.79,1.19,120.28,1.19c9.9,0,16.18,6.92,16.18,17.28Zm-25.25-.05c0,6.63,2.89,11.64,8.87,11.64,6.52,0,8.82-5.45,8.82-11.49,0-6.43-2.65-11.44-8.92-11.44s-8.78,4.71-8.78,11.29Z'/%3e%3cpath%20class='cls-1'%20d='m205.24,25.05c-1.32,6.33-6.08,10.95-14.56,10.95-10.74,0-15.79-7.61-15.79-17.23s5.25-17.58,16.13-17.58c9.02,0,13.38,5.25,14.22,10.95h-7.3c-.74-2.65-2.55-5.25-7.16-5.25-6.03,0-8.33,5.45-8.33,11.64,0,5.7,2.01,11.73,8.53,11.73,4.8,0,6.27-3.19,6.91-5.2h7.35Z'/%3e%3cpath%20class='cls-1'%20d='m211.46,1.68h7.3v13.16h13.63V1.68h7.3v33.83h-7.3v-14.63h-13.63v14.63h-7.3V1.68Z'/%3e%3cpath%20class='cls-1'%20d='m254.5,7.67h-10.29V1.68h27.85v5.99h-10.25v27.84h-7.3V7.67Z'/%3e%3cpath%20class='cls-1'%20d='m299.85,20.93h-16.18v8.59h17.84l-.88,5.99h-24.07V1.68h23.97v5.99h-16.86v7.22h16.18v6.04Z'/%3e%3cpath%20class='cls-1'%20d='m335.54,25.05c-1.32,6.33-6.08,10.95-14.56,10.95-10.74,0-15.79-7.61-15.79-17.23s5.25-17.58,16.13-17.58c9.02,0,13.38,5.25,14.22,10.95h-7.3c-.74-2.65-2.55-5.25-7.16-5.25-6.03,0-8.33,5.45-8.33,11.64,0,5.7,2.01,11.73,8.53,11.73,4.8,0,6.27-3.19,6.91-5.2h7.35Z'/%3e%3cpath%20class='cls-1'%20d='m341.76,1.68h7.3v13.16h13.63V1.68h7.3v33.83h-7.3v-14.63h-13.63v14.63h-7.3V1.68Z'/%3e%3cpath%20class='cls-1'%20d='m159.99,1.68h-9.17l-11.47,33.83h7.21c1.91-6.14,7.75-25.24,8.58-28.58h.05c.83,3.04,6.72,21.41,9.12,28.58-.03-.07,0,0,0,0h7.7L159.99,1.68Z'/%3e%3c/g%3e%3cpath%20class='cls-1'%20d='m56.69,0H22.26C12.33,0,2.55,8.06.42,18H.42c-2.14,9.94,4.18,18,14.1,18h10.89c1.47,0,2.92-1.19,3.24-2.67l1.52-7.06c.32-1.47-.62-2.67-2.09-2.67h-12.11c-2.76,0-4.71-2.13-4.2-4.89.53-2.86,3.32-5.2,6.16-5.2h19.04c1.47,0,2.41,1.19,2.09,2.67l-3.69,17.16c-.32,1.47.62,2.67,2.09,2.67h8.83c1.47,0,2.92-1.19,3.24-2.67l3.69-17.16c.32-1.47,1.77-2.67,3.24-2.67h0c1.47,0,2.92-1.19,3.24-2.67l2.33-10.84h-5.33Z'/%3e%3c/g%3e%3c/g%3e%3c/svg%3e" alt="COACHTECH" data-v-9e7c25fb="">
            </a>
            <form id="search-form" class="form__search">
                <input class="input__search" type="text" name="search" id="search" 
                @if(isset($search))
                    value="{{ $search }}"
                @else
                    value=""                 
                @endif
                placeholder="なにをお探しですか？">
            </form>
            <div class="buttons__header">
                @if(Auth::check())
                <form action="{{route('logout')}}" method="post">
                @csrf
                    <button class="button__logout" type="submit">ログアウト</button>
                </form>
                @endif
                @if(!Auth::check())
                <a href="{{route('login')}}" class="button__login">ログイン</a>
                @endif
                <a href="{{route('mypage')}}" class="button__mypage">マイページ</a>
                <a href="{{route('exhibit')}}" class="button__exhibit">出品</a>
            </div>
        </div>
    </header>
    @yield('content')
</body> 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
        if (settings.type !== 'GET') {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        }
        }
    });
    </script>
    <script>
    $(document).ready(function() {
    $('#search').on('input', function() {
        const search = $(this).val().trim();
        const page = $('#page').val().trim();
        const url = page === 'all' ? '/all' : '/mylist';

        
        $.ajax({
            url: url,
            method: 'GET',
            data: { search: search, page: page },
            success: function(response) {
                console.log('Success:', response);
                const cleanUrl = url;
                window.history.replaceState({}, '', cleanUrl);
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });
    });
    </script>

</html>