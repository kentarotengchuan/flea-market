@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection
@section('content')
<main class="main">
    <div class="content">
        <div class="form__login">
            <h2 class="form__ttl">ログイン</h2>        
            <form action="{{route('login')}}" method="post">
            @csrf
                <p class="ttl__name">ユーザー名/メールアドレス</p>
                <input class="form__name" type="text" name="email" id="name">
                @error('email')
                <p class="error-message">
                    {{$errors->first('email')}}
                </p>
                @enderror
                <p class="ttl__password">パスワード</p>
                <input class="form__password" type="password" name="password" id="password">
                @error('password')
                <p class="error-message">
                    {{$errors->first('password')}}
                </p>
                @enderror
                <div class="buttons">
                    <button class="button__post" type="submit">ログインする</button>
                    <a href="{{route('register')}}" class="button__change">会員登録はこちら</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection