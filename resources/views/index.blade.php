@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/index.css')}}">
@endsection
@section('content')
<main class="main">
    <div class="content">
        @if (session('put_completed'))
            <p class="completed-message">{{ session('put_completed') }}</p>
        @endif 
        <div class="nav">
            <a href="{{route('all',array_merge(request()->query(),['page'=>'all']))}}" class="button__index 
                @if(isset($page))
                @if($page == 'all')
                red
                @endif
                @endif">おすすめ</a>
            <a href="{{route('mylist',array_merge(request()->query(),['page'=>'mylist']))}}" class="button__mylist 
                @if(isset($page))
                @if($page == 'mylist')
                red
                @endif
                @endif                
                ">マイリスト</a>
        </div>
        <div class="box__products">
        @foreach($products as $product)
        <a href="{{route('detail',['id'=>$product->id])}}" class="link__shop" id="link-{{$product->id}}">
            <div class="box__product">
                <div class="box__img" id="product-{{$product->id}}">
                    @if($product->sold == 'yes')
                    <p class="sold">sold</p>
                    @else
                    <img class="img" src="{{ asset('storage/product_images/'.$product->img_path) }}" alt="">
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