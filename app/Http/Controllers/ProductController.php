<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Product;
use App\Models\Comment;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\User;
use App\Models\Condition;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Sort;

class ProductController extends Controller
{
    public function index(Request $request){
        $all = Product::all();
        $products = $request->session()->get('products',$all);
        $search = $request->session()->get('search',"");
        $page = $request->session()->get('page','all');
    
        return view('index',compact('products','search','page'));
    }
    public function all(Request $request){  
        if($request->search !== null){
            $search = $request->search;
        }else{
            $search = "";
        } 
        if(Auth::check()){
            $id = Auth::user()->id;
            $products = Product::query()->where(function ($query) use ($request,$search,$id) {
                $query->where('user_id','!=',$id)
                ->where('name', 'LIKE', '%' . $search . '%');
            })->get();
        }else{
            $products = Product::query()->where(function ($query) use ($request,$search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            })->get();
        }

        $request->session()->put('products',$products);
        $request->session()->put('search',$search);
        $request->session()->put('page','all');

        if($search !== ""){
            return redirect("/?search=$search");
        }else{
            return redirect("/");
        }
    }

    public function mylist(Request $request){
        if(Auth::check()){
            $id = Auth::user()->id; 
            if($request->search !== null){
                $search = $request->search;
            }else{
                $search = "";
            }

            $products = Product::whereHas('likeds',function($query) use($id){
                $query->where('user_id',$id);                   
            })
            ->where('user_id','!=',$id)
            ->where('name', 'LIKE', '%' . $search . '%')
            ->get();

            $request->session()->put('products',$products);
            $request->session()->put('search',$search);
            $request->session()->put('page','mylist');

            if($search !== ""){
                return redirect("/?page=mylist&search=$search");
            }else{
                return redirect("/?page=mylist");
            }
        }else{
            $products = [];
            if($request->search !== null){
                $search = $request->search;
            }else{
                $search = "";
            }

            $request->session()->put('products',$products);
            $request->session()->put('search',$search);
            $request->session()->put('page','mylist');

            if($search !== ""){
                return redirect("/?page=mylist&search=$search");
            }else{
                return redirect("/?page=mylist");
            }
        }
    }

    public function detail(int $id){
        $product = Product::findOrFail($id);
        return view('detail',compact('product'));
    }

    public function exhibit(){
        $categories = Category::all();
        $conditions = Condition::all();
        return view('exhibit',compact('categories','conditions'));
    }
    
    public function goChange(int $id){
        $product = Product::findOrFail($id);
        $user = Auth::user();
        return view('change-address',compact('product','user'));
    }

    public function goPurchase(int $id){
        $user = Auth::user();
        $product = Product::findOrFail($id);
        return view('purchase',compact('user','product'));
    }

    public function purchase(Request $request){
        $methodInput = $request->input('method');
        if($methodInput == "コンビニ払い"){
            $method = 'konbini';
        }
        elseif($methodInput == "カード払い"){
            $method = 'card';
        }

        try{
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $id = $request->input('id');
        $amount = $request->input('amount');
        $name = $request->input('name');

        $session = Session::create([
            'payment_method_types' => [$method],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $name,
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('success',['id'=>$id]),
            'cancel_url' => route('cancel',['id'=>$id]),
        ]);

        return response()->json(['id' => $session->id]);
        }catch(Exception $e){
        return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function success(int $id){
        $userId = Auth::user()->id;
        Product::findOrFail($id)
        ->update([
            'sold' => 'yes',
            'buyer_id' => $userId,
        ]);
        return redirect(route('detail',['id'=>$id]))->with('success_message','購入しました');
    }

    public function cancel(int $id){
        return redirect(route('detail',['id'=>$id]))->with('cancel_message','購入がキャンセルされました');
    }

    public function sell(ExhibitionRequest $request){
        $userId = Auth::user()->id;
        $image = $request->file('image');
        $fileName = time() . '_' . uniqid() . ".png";
        $filePath = $image->storeAs('product_images', $fileName,'public');

        $product = Product::create([
            'user_id' => $userId,
            'condition_id' => $request->condition,
            'name' => $request->name,
            'imgPath' => $fileName,
            'price' => $request->price,
            'description' => $request->description,
            'sold' => 'no',
        ]);

        $selectedCategories = $request->input('options',[]);
        foreach ($selectedCategories as $category) {
            Sort::create([
                'product_id' => $product->id,
                'category_id' => $category,        
            ]);
        }

        return redirect(route('index'))->with('put_completed','商品が出品されました');
    }
}
