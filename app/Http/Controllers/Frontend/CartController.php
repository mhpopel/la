<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use Gloudemans\Shoppingcart\Facades\Cart;
use Auth;
use Carbon\Carbon;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use App\Models\ShipDivision;


class CartController extends Controller
{
    public function AddToCart(Request $request, $id){
        $product = Product::findOrFail($id);

        if($product->discount_price == NULL){
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thumbnail,
                    'color' => $request->color,
                    'size' => $request->size
                    ]
            ]);

            return response()->json(['success'=>'Successfully Added on your cart']);
        }else{
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thumbnail,
                    'color' => $request->color,
                    'size' => $request->size
                    ]
            ]);
            return response()->json(['success'=>'Successfully Added on your cart']);
        }
    }


    public function addMiniCart(){
        $cart_data = Cart::content();
        $cartQty = Cart::count();
        $cartTotal = Cart::total();

        return response()->json(array(
            'carts' => $cart_data,
            'cartQty' => $cartQty,
            'cartTotal' => round($cartTotal),
        ));
    }

    public function removeMiniCart($rowId){
        Cart::remove($rowId);
        return response()->json(['success' => 'Product removed from cart']);
    }


    public function AddToWishlist(Request $request,$product_id){
        if(Auth::check()){
            $exists = Wishlist::where('user_id',Auth::id())->where('product_id',$product_id)->first();
            if($exists){
                return response()->json(['error' => 'Product already added to Wishlist!']);
            }else{
                Wishlist::insert([
                    'user_id' => Auth::id(),
                    'product_id' => $product_id,
                    'created_at' => Carbon::now(),
                ]);
                return response()->json(['success' => 'Product added to Wishlist!']);
            }

        }else{
            return response()->json(['error' => 'You must log in!']);
        }
    }


    public function CouponApply(Request $request){
        $coupon = Coupon::where('coupon_name',$request->coupon_name)->where('coupon_validity','>=',Carbon::now()->format('Y-m-d'))->first();
        if ($coupon) {

            Session::put('coupon',[
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100),
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100)
            ]);

            return response()->json(array(

                'success' => 'Coupon Applied Successfully'
            ));

        }else{
            return response()->json(['error' => 'Invalid Coupon']);
        }
    }

    public function CouponCalculation(){
        // Session::forget('coupon');
        if (Session::has('coupon')) {
            return response()->json(array(
                'subtotal' => Cart::total(),
                'coupon_name' => session()->get('coupon')['coupon_name'],
                'coupon_discount' => session()->get('coupon')['coupon_discount'],
                'discount_amount' => session()->get('coupon')['discount_amount'],
                'total_amount' => session()->get('coupon')['total_amount'],
            ));
        }else{
            return response()->json(array(
                'total' => Cart::total(),
            ));

        }
    } // end method

    public function CouponRemove(){
        Session::forget('coupon');
        return response()->json(['success' => 'Coupon Remove Successfully']);
    }

    public function CheckoutCreate(){
        $carts = Cart::content();
        $cartQty = Cart::count();
        $cartTotal = Cart::total();
        $divisions = ShipDivision::orderBy('division_name','ASC')->get();
        if(Auth::check()){
            if(Cart::total() > 0){
                return view('frontend.checkout.checkout_view',compact('carts','cartQty','cartTotal','divisions'));
            }else{
                $notification = array(
                    'message' => 'You must have at least one product in Cart',
                    'alertType' => 'error'
                );
                return redirect()->route('index')->with($notification);
            }
        }else{
            $notification = array(
                'checkout' => 'You Need to Login First',
                'alertType' => 'error'
            );
            return redirect()->route('login')->with($notification);
        }
    }
}
