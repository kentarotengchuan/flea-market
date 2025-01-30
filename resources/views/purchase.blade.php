@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('css/purchase.css')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
<main class="main">
    <div class="content">
        @if (session('address_changed'))
            <p class="changed-message">{{ session('address_changed') }}</p>
        @endif     
        <div class="flex">
            <div class="left">
                <div class="box__product">
                    <div class="box__left">
                        <div class="img__inner">
                            <img src="{{ asset('storage/product_images/'.$product->img_path) }}" alt="" class="img__product">
                        </div>
                    </div>
                    <div class="box__right">
                        <p class="name__product">{{$product->name}}</p>
                        <p class="price__product">
                        ￥{{$product->price}}
                        </p>
                    </div>
                </div>
                <div class="box__method">
                    <h2 class="ttl__method">支払い方法</h2>
                    <select class="select__method" name="method" id="method" form="purchase-form">
                        <option value="" selected hidden>選択してください</option>
                        <option value="コンビニ払い">コンビニ払い</option>
                        <option value="カード払い">カード払い</option>
                    </select>
                    @error('method')
                    <p class="error-message">
                    {{$errors->first('method')}}
                    </p>
                    @enderror
                </div>
                <div class="box__forward">
                    <div class="flex__forward">
                        <h2 class="ttl__forward">配送先</h2>
                        <a href="{{route('goChange',['id'=>$product->id])}}" class="link__change">変更する</a>
                    </div>
                    <div class="content__forward">
                        <p class="content__postnumber">
                        〒{{$user->postnumber}}
                        </p>
                        <p class="content__address">
                        {{$user->address.$user->building}}
                        </p>
                    </div>
                    
                </div>
            </div>
            <div class="right">
                <div class="box__purchase">
                    <div class="box__confirm">
                        <div class="price__confirm">
                            <span class="ttl__confirm">商品代金</span>
                            <span class="price">￥{{$product->price}}</span>
                        </div>
                        <div class="method__confirm">
                            <span class="ttl__confirm">支払い方法</span>
                            <span class="method" id="output-method"></span>
                        </div>
                    </div>
                    <form id="purchase-form">
                    @csrf                       
                        <button class="button__purchase" type="button" id="purchase-button"　
                        @if($product->sold == "yes")
                        disabled
                        @endif>
                            @if($product->sold == "no")
                                購入する
                            @endif
                            @if($product->sold == "yes")
                                購入済
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe(`{{ env('STRIPE_KEY') }}`);

    document.getElementById('purchase-button').addEventListener('click', function () {
        const id = `{{$product->id}}`;
        const amount = `{{$product->price}}`;
        const name = `{{$product->name}}`;
        const method = $('#method').val();

        if (method === '') {
        alert('支払い方法を選択してください');
        return;
    }

        fetch('/purchase/product/sold', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            body: JSON.stringify({
                id: id,
                amount: amount,
                name: name,
                method: method,
            })
        })
        .then(response => response.json())
        .then(session => {
            return stripe.redirectToCheckout({ sessionId: session.id });
        })
        .catch(error => {
            console.error('エラー:', error);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#method').on('input', function() {
            const method = $(this).val().trim(); 
            $('#output-method').text(method);
            });
        });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const selectElement = document.getElementById("method");

    function updateOptions() {
        for (let option of selectElement.options) {
            option.text = option.text.replace(/^✓ /, "");
        }

        const selectedOption = selectElement.options[selectElement.selectedIndex];
        selectedOption.text = "✓ " + selectedOption.text;
    }

    selectElement.addEventListener("click", updateOptions);

    selectElement.addEventListener("blur", function () {
        for (let option of selectElement.options) {
            option.text = option.text.replace(/^✓ /, "");
        }
    });
});
</script>
@endsection