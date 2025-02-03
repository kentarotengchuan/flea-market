@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/detail.css')}}">
@endsection
@section('content')
<main class="main">
    <div class="content">
        @if (session('success_message'))
            <p class="success-message">{{ session('success_message') }}</p>
        @endif
        @if (session('cancel_message'))
            <p class="cancel-message">{{ session('cancel_message') }}</p>
        @endif
        @error('content')
            <p class="error-message">
            {{$errors->first('content')}}
            </p>
        @enderror
        <div class="flex">
            <div class="left">
                <div class="img__inner">
                    <img class="img" src="{{ asset('storage/product_images/'.$product->imgPath) }}" alt="">
                </div>
            </div>
            <div class="right">
                <div class="box__name">
                    <h2 class="name">{{$product->name}}</h2>
                    <span class="brand">{{$product->brand}}</span>
                </div>
                <div class="box__price">
                    <label for="price" class="yen">￥</label>
                    <span class="price">{{$product->price}}</span>
                    <label for="price" class="tax-include">(税込)</label>
                </div>
                <div class="box__counts">
                    <div class="counts__favorite">
                        <form class="form__favorite" action="{{ route('like',['id'=>$product->id]) }}" method="post">
                        @csrf
                            <button id="like" type="submit">
                                <svg class="{{$product->likedOrNot(auth()->user())? 'liked' : '' }}" width="32px" height="32px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M11.2691 4.41115C11.5006 3.89177 11.6164 3.63208 11.7776 3.55211C11.9176 3.48263 12.082 3.48263 12.222 3.55211C12.3832 3.63208 12.499 3.89177 12.7305 4.41115L14.5745 8.54808C14.643 8.70162 14.6772 8.77839 14.7302 8.83718C14.777 8.8892 14.8343 8.93081 14.8982 8.95929C14.9705 8.99149 15.0541 9.00031 15.2213 9.01795L19.7256 9.49336C20.2911 9.55304 20.5738 9.58288 20.6997 9.71147C20.809 9.82316 20.8598 9.97956 20.837 10.1342C20.8108 10.3122 20.5996 10.5025 20.1772 10.8832L16.8125 13.9154C16.6877 14.0279 16.6252 14.0842 16.5857 14.1527C16.5507 14.2134 16.5288 14.2807 16.5215 14.3503C16.5132 14.429 16.5306 14.5112 16.5655 14.6757L17.5053 19.1064C17.6233 19.6627 17.6823 19.9408 17.5989 20.1002C17.5264 20.2388 17.3934 20.3354 17.2393 20.3615C17.0619 20.3915 16.8156 20.2495 16.323 19.9654L12.3995 17.7024C12.2539 17.6184 12.1811 17.5765 12.1037 17.56C12.0352 17.5455 11.9644 17.5455 11.8959 17.56C11.8185 17.5765 11.7457 17.6184 11.6001 17.7024L7.67662 19.9654C7.18404 20.2495 6.93775 20.3915 6.76034 20.3615C6.60623 20.3354 6.47319 20.2388 6.40075 20.1002C6.31736 19.9408 6.37635 19.6627 6.49434 19.1064L7.4341 14.6757C7.46898 14.5112 7.48642 14.429 7.47814 14.3503C7.47081 14.2807 7.44894 14.2134 7.41394 14.1527C7.37439 14.0842 7.31195 14.0279 7.18708 13.9154L3.82246 10.8832C3.40005 10.5025 3.18884 10.3122 3.16258 10.1342C3.13978 9.97956 3.19059 9.82316 3.29993 9.71147C3.42581 9.58288 3.70856 9.55304 4.27406 9.49336L8.77835 9.01795C8.94553 9.00031 9.02911 8.99149 9.10139 8.95929C9.16534 8.93081 9.2226 8.8892 9.26946 8.83718C9.32241 8.77839 9.35663 8.70162 9.42508 8.54808L11.2691 4.41115Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                            </button>
                        </form>
                        <span class="count__favorite">{{$product->countLike()}}</span>
                    </div>
                    <div class="counts__comment">
                        <div class="icon__comment">
                            <svg width="32px" height="32px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g clip-path="url(#clip0_429_11233)"> <path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 13.4876 3.36093 14.891 4 16.1272L3 21L7.8728 20C9.10904 20.6391 10.5124 21 12 21Z" stroke="#292929" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path> </g> <defs> <clipPath id="clip0_429_11233"> <rect width="24" height="24" fill="white"></rect> </clipPath> </defs> </g></svg>
                        </div>
                        <span class="count__comment">{{$product->countComment()}}</span>
                        
                    </div>
                </div>
                <div class="form__purchase">
                    <form action="{{route('goPurchase',['id'=>$product->id])}}" method="get">
                        <button class="button__purchase" type="submit" 
                        @if($product->sold == "yes")
                        disabled 
                        @endif>
                            @if($product->sold == "no")
                            購入手続きへ
                            @endif
                            @if($product->sold == "yes")
                            購入済
                            @endif
                        </button>
                    </form>
                </div>
                <div class="box__description">
                    <h3 class="ttl__description">商品説明</h3>
                    <p class="content__description">{{$product->description}}</p>
                </div>
                <div class="box__information">
                    <h3 class="ttl__information">商品の情報</h3>                       
                    <div class="box__category">
                        <span class="ttl__category">カテゴリー</span>
                        @foreach ($product->getCategories() as $category)
                        <span class="content__category">{{$category->content}}</span>
                        @endforeach
                    </div>
                    <div class="box__condition">
                        <span class="ttl__condition">商品の状態</span>
                        <span class="content__condition">{{$product->condition->content}}</span>
                    </div>
                    <div class="box__comments">
                        <h3 class="ttl__comment">コメント{{'('.$product->countComment().')'}}</h3>
                        @foreach($product->comments as $comment)
                            <div class="box__admin">
                                <div class="img__admin">
                                    <img class="img__user"
                                    @if(isset($comment->user->imgPath))
                                    src="{{ asset('storage/user_images/'.$comment->user->imgPath) }}"
                                    @else
                                    src="{{ asset('storage/user_images/emp.png') }}"
                                    @endif>
                                    <span class="name__admin">{{$comment->user->name}}</span>
                                </div> 
                                <div class="content__comment">
                                {{$comment->content}}
                                </div>
                            </div>                           
                        @endforeach
                        <div class="form__comment">
                            <p class="ttl__form">商品へのコメント</p>
                            <form class="form" action="{{route('comment')}}" method="post">
                            @csrf
                                <input type="hidden" name="id" value="{{$product->id}}">
                                <textarea class="input__content" name="content" id="content"></textarea>
                                <button class="button__form" type="submit">コメントを送信する</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection