<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class UserController extends Controller
{
    public function done(){
        return view('auth.done');
    }

    public function profile(){
        $user = User::latest()->first();
        return view('auth.profile',compact('user'));
    }

    public function storeProfile(ProfileRequest $request){
        $id = $request->id;
        if($request->file('image') !== null){
        $image = $request->file('image');
        $fileName = time() . '_' . uniqid() . ".png";
        $filePath = $image->storeAs('user_images', $fileName,'public');
        User::findOrFail($id)
        ->update([
            'imgPath' => $fileName,
        ]);
        }

        User::findOrFail($id)
        ->update([
            'name' => $request->name,
            'postnumber' => $request->postnumber,
            'address' => $request->address,    
        ]);
        if(isSet($request->building)){
            User::findOrFail($id)
            ->update([
                'building' => $request->building,
            ]);        
        }

        return redirect(route('login'))->with('not_authorized','認証メールが送信されました');
    }

    public function like(Request $request,$id){
        $user = Auth::user();

        if ($user->likes()->where('product_id', $id)->exists()) {
            $user->likes()->detach($id);
        } else {
            $user->likes()->attach($id);
        }
        return redirect(route('detail',['id'=>$id]));
    }
    public function detail(int $id){
        $product = Product::findOrFail($id);
        return view('detail',compact('product'));
    }

    public function postComment(CommentRequest $request){
        Comment::create([
            "user_id" => Auth()->user()->id,
            "product_id" => $request->id,
            "content" => $request->content,
        ]);
        return redirect(route('detail',['id'=>$request->id]));
    }

    public function mypage(Request $request){
        $user = Auth::user();

        $session = $request->session()->get('product-list','sell');
        if($session == 'sell'){
            $products = Product::where('user_id',"$user->id")->get();
        }
        elseif($session == 'buy'){
            $products = Product::where('buyer_id',"$user->id")->get();
        }
        
        return view('mypage',compact('user','products','session'));
    }

    public function mypageSell(Request $request){
        $request->session()->put('product-list','sell');
        return redirect('/mypage?page=sell');
    }

    public function mypageBuy(Request $request){
        $request->session()->put('product-list','buy');
        return redirect('/mypage?page=buy');
    }

    public function change(AddressRequest $request){
        Auth::user()
        ->update([
            'postnumber' => $request->postnumber,
            'address' => $request->address,
            'building' => $request->building,
        ]);
        return redirect(route('goPurchase',['id'=>$request->product_id]))->with('address_changed','住所が変更されました');
    }
}
