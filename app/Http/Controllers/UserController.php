<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
            'img_path' => $fileName,
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

        return redirect(route('all'));
    }
}
