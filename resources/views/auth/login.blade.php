@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection
@section('content')
<main class="main">
    <div class="content">
        <div class="form__login">
            <h2 class="form__ttl">ログイン</h2> 
            @if(session('not_authorized'))
            <div class="box__verify">
                <p class="verify-message">{{session('not_authorized')}}</p>
                <div class="buttons__verify">
                    <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button class="button__verify" type="submit">認証メールを再送</button>
                </form>
                </div>
            </div>
            @endif       
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