<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Auth;

class WishlistController extends Controller
{
    public function viewWishlist(){
        $wishlist = Wishlist::with('product')->where('user_id',Auth::id())->latest()->get();

        return view('frontend.wishlist.view_wishlist',compact('wishlist'));
    }

    public function GetWishlistProduct(){

		$wishlist = Wishlist::with('product')->where('user_id',Auth::id())->latest()->get();
		return response()->json($wishlist);
	}

    public function RemoveWishlistProduct($id){

		Wishlist::where('user_id',Auth::id())->where('id',$id)->delete();
		return response()->json(['success' => 'Successfully Product Remove']);
	}
}
