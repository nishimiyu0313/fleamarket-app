<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use App\Models\Payment;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function  index(Request $request)
    {
        $user = Auth::user();

        return view('profile.profile', compact('user'));
    }
    public function store(ProfileRequest $request)
    {
        $user = auth()->user();


        $profileData = $request->only([
            'name',
            'postal_code',
            'address',
            'building',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $profileData['image'] = $path;
        }

        $profileData['user_id'] = $user->id;
        Profile::create($profileData);
        return redirect('/');
    }
    public function  profile(Request $request)
    {
        $profile = Auth::user()->profile;


        return view('profile.edit', compact('profile'));
    }
    public function updateProfile(ProfileRequest $request)
        
    {       
        $profile = Auth::user()->profile;

        $profile->name = $request->input('name');
        $profile->postal_code = $request->input('postal_code');
        $profile->address =  $request->input('address');
        $profile->building =  $request->input('building');
       

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $profile->image = $path;
        }
        
        $profile->save();
        
        return redirect('/mypage/sell');
    }
    public function  address($id)
    {
        $item = Item::find($id);
        $profile = Auth::user()->profile;
        return view('payment.address', compact('profile', 'item'));
    }
    public function  updateAddress(Request $request, $id)
    {
        $user = Auth::user();
        $profile = $user->profile;
        $item = Item::find($id);


        $address = $request->only([
            'postal_code',
            'address',
            'building',
            'content',
        ]);
        $address['user_id'] = $user->id;
        $address['item_id'] = $id;
       

        $lastPayment = Payment::where('user_id', $user->id)
            ->where('item_id', $id)
            ->latest()
            ->first();
        

        $payment = Payment::create($address);
        return view('payment.address', compact('item', 'profile', 'payment'));
    }
}
