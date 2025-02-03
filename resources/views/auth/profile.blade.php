@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/profile.css')}}">
@endsection
@section('content')
<main class="main">
    <div class="content">
        <div class="form__profile">
            <h2 class="form__ttl">プロフィール設定</h2>       
            <form action="{{route('storeProfile')}}" method="post" enctype="multipart/form-data">
            @csrf
                <input type="hidden" name="id" value={{$user->id}}>
                <div class="box__image">
                    <img class="img" 
                    @if(isset($user->img_path))
                    src="{{ asset('storage/user_images/'.$user->img_path) }}"
                    @else
                    src="{{ asset('storage/user_images/emp.png') }}"
                    @endif >
                    <label for="image" class="file-label">画像を選択する</label>
                    <input class="form__image" type="file" name="image" id="image" hidden>
                </div>
                @error('image')
                <p class="error-message">
                {{$errors->first('image')}}
                </p>
                @enderror
                <p class="ttl__name">ユーザー名</p>
                <input class="form__name" type="text" name="name" id="name" value="{{$user->name}}">
                @error('name')
                <p class="error-message">
                    {{$errors->first('name')}}
                </p>
                @enderror
                <p class="ttl__postnumber">郵便番号</p>
                <input class="form__postnumber" type="text" name="postnumber" id="postnumber" value="{{$user->postnumber}}">
                @error('postnumber')
                <p class="error-message">
                    {{$errors->first('postnumber')}}
                </p>
                @enderror
                <p class="ttl__address">住所</p>
                <input class="form__address" type="text" name="address" id="address" value="{{$user->address}}">
                @error('address')
                <p class="error-message">
                    {{$errors->first('address')}}
                </p>
                @enderror
                <p class="ttl__building">建物名</p>
                <input class="form__building" type="text" name="building" id="building" value="{{$user->building}}">
                @error('building')
                <p class="error-message">
                    {{$errors->first('building')}}
                </p>
                @enderror
                <button class="button__post" type="submit">更新する</button>               
            </form>
        </div>
    </div>
</main>
@endsection