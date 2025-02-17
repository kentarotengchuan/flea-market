@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/mypage.css')}}">
@endsection
@section('content')
<main class="main">
    <div class="content">
        <div class="box__profile">
            <div class="profile__flex">
                <div class="img__inner">
                    <img class="img__user" 
                    @if(isset($user->imgPath))
                    src="{{ asset('storage/user_images/'.$user->imgPath) }}"
                    @else
                    src="{{ asset('storage/user_images/emp.png') }}"
                    @endif >
                </div>
                <span class="name__profile">{{$user->name}}</span>
                <div class="button__inner">
                    <form action="{{route('profile')}}" method="get">
                        <button class="button__change" type="submit">プロフィールを編集</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="nav">
            <a href="{{route('mypage-sell')}}" class="button__sell 
            @if(isset($session))
            @if($session == 'sell')
            red
            @endif
            @endif">出品した商品</a>
            <a href="{{route('mypage-buy')}}" class="button__buy 
            @if(isset($session))
            @if($session == 'buy')
            red
            @endif
            @endif">購入した商品</a>
        </div>
        <div class="box__products">
        @foreach ($products as $product)
            <a href="{{route('detail',['id'=>$product->id])}}" class="link__shop">
            <div class="box__product" id="box-{{$product->id}}">
                <div class="box__img">
                    @if($product->sold == 'no' || $session == 'buy')                    
                    <img class="img__product" id="product-{{$product->id}}" src="{{ asset('storage/product_images/'.$product->imgPath) }}" alt="">
                    @else
                    <p class="sold">sold</p>
                    @endif
                </div>
                <p class="name__product">{{$product->name}}</p>    
            </div>
            </a>         
        @endforeach
        </div>
    </div>
</main>
@endsection