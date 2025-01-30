@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/done.css')}}">
@endsection
@section('content')
<main class="main">
    <div class="content">
        <div class="box__verify">
            <p class="verify-message">メール認証が済んでいません。認証メールをご確認ください。</p>
            <div class="buttons">
                <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                    <button class="button__verify" type="submit">認証メールを再送</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection