@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/exhibit.css')}}">
@endsection
@section('content')
<main class="main">
    <div class="content">
        <div class="box__exhibit">
            <h2 class="ttl__exhibit">商品の出品</h2>
            <form class="form__sell" action="{{route('sell')}}" method="post" enctype="multipart/form-data">
            @csrf
                <div class="box__img">
                    <p class="ttl__img">商品画像</p>
                    <div class="form__inner-img">
                        <label for="image" class="file-label">画像を選択する</label>
                        <input class="img__input" type="file" name="image" id="image" hidden>
                    </div>
                    @error('image')
                    <p class="error-message">
                    {{$errors->first('image')}}
                    </p>
                    @enderror
                </div>
                <div class="box__detail">
                    <h3 class="ttl__detail">商品の詳細</h3>
                    <div class="box__category">
                        <p class="ttl__category">カテゴリー</p>
                        <div class="flex__category">
                        @foreach ($categories as $category)
                            <label class="label__category">
                                <input type="checkbox" id="{{$category->id}}" name="options[]" value="{{$category->id}}" class="input__category">
                                <span class="content__category">{{$category->content}}</span>
                            </label>
                        @endforeach
                        </div>
                        @error('options')
                        <p class="error-message">
                        {{$errors->first('options')}}
                        </p>
                        @enderror
                    </div>
                    <div class="box__condition">
                        <p class="ttl__condition">商品の状態</p>
                        <select class="select__condition" name="condition" id="condition">
                            <option value="">選択してください</option>
                            @foreach ($conditions as $condition)
                            <option value="{{$condition->id}}">{{$condition->content}}</option>    
                            @endforeach
                        </select>
                        @error('condition')
                        <p class="error-message">
                        {{$errors->first('condition')}}
                        </p>
                        @enderror
                    </div>
                    <div class="box__description">
                        <h3 class="ttl__name-and-description">商品名と説明</h3>
                        <p class="ttl__name">商品名</p>
                        <input class="input__name" type="text" name="name" id="name">
                        @error('name')
                        <p class="error-message">
                        {{$errors->first('name')}}
                        </p>
                        @enderror
                        <p class="ttl__description">商品の説明</p>
                        <textarea class="input__description" name="description" id="description" cols="30" rows="10"></textarea>
                        @error('description')
                        <p class="error-message">
                        {{$errors->first('description')}}
                        </p>
                        @enderror
                        <p class="ttl__price">販売価格</p>
                        <div class="box__price">
                            <span class="currency">¥</span>
                            <input type="text" class="input__price" name="price" id="price">
                        </div>                       
                        @error('price')
                        <p class="error-message">
                        {{$errors->first('price')}}
                        </p>
                        @enderror
                    </div>
                    <div class="box__submit">
                        <button class="button__submit" type="submit">出品する</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection