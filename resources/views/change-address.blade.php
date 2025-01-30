@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/change.css')}}">
@endsection
@section('content')
<main class="main">
    <div class="content">
        <div class="box__change">
            <h2 class="ttl__change">住所の変更</h2>
            <form class="form__change" action="{{route('change')}}" method="post">
            @csrf
                <input type="hidden" name="product_id" value="{{$product->id}}">
                <p class="ttl__input">郵便番号</p>
                <input type="text" name="postnumber" id="postnumber" class="input__postnumber" value="{{$user->postnumber}}">
                @error('postnumber')
                <p class="error-message">
                    {{$errors->first('postnumber')}}
                </p>
                @enderror
                <p class="ttl__input">住所</p>
                <input type="text" name="address" id="address" class="input__address" value="{{$user->address}}">
                @error('address')
                <p class="error-message">
                    {{$errors->first('address')}}
                </p>
                @enderror
                <p class="ttl__input">建物名</p>
                <input type="text" name="building" id="building" class="input__building" value="{{$user->building}}">
                @error('building')
                <p class="error-message">
                    {{$errors->first('building')}}
                </p>
                @enderror
                <button class="button__change" type="submit">
                    更新する
                </button>
            </form>
        </div>
    </div>
</main>
@endsection