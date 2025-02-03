@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/register.css')}}">
@endsection
@section('content')
<main class="main">
    <div class="content">
        <div class="form__login">
            <h2 class="form__ttl">会員登録</h2>        
            <form action="{{route('register')}}" method="post">
            @csrf
                <p class="ttl__name">ユーザー名</p>
                <input class="form__name" type="text" name="name" id="name">
                @error('name')
                <p class="error-message">
                    {{$errors->first('name')}}
                </p>
                @enderror
                <p class="ttl__email">メールアドレス</p>
                <input class="form__email" type="email" name="email" id="email">
                @error('email')
                <p class="error-message">
                    {{$errors->first('email')}}
                </p>
                @enderror
                <p class="ttl__password">パスワード</p>
                <input class="form__password" type="password" name="password" id="password">
                @if(session('password_error'))
                    <p class="error-message">{{ session('password_error') }}</p>
                @endif
                <p class="ttl__password-confirm">確認用パスワード</p>
                <input class="form__password-confirm" type="password" name="password_confirmation" id="password-confirm">
                @if(session('password_confirmation_error'))
                <p class="error-message">
                    {{ session('password_confirmation_error') }}
                </p>
                @endif
                <div class="buttons">
                    <button class="button__post" type="submit">登録する</button>
                    <a href="{{route('login')}}" class="button__change">ログインはこちら</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection