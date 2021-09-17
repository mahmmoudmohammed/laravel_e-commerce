<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class ShopingController extends Controller
{

    public function index()
    {
        return view('home');
    }

    public function setTempCart(Product $product,$quantity = 1) {
        $expireDate = 2222;
        $cartProducts = array();
        $newProduct[] = $product->attributesToArray();
        $newProduct[0]["quantity"] = $quantity;
        if (cookie::has('cart:')) {
            $cartProducts = json_decode(Cookie::get('cart:'),true);
        }else{
            $response = new Response('cart:');
            $cartProducts [] = array_push($cartProducts,$newProduct);
            return $response->setContent('product added to Cart')->withCookie(cookie('cart:',  json_encode($cartProducts), $expireDate));
        }
        $cartProducts [] = array_push($cartProducts,$newProduct);
        $response = new Response('cart:');
        return $response->setContent('product added to Cart')->withCookie(cookie('cart:', json_encode($cartProducts), $expireDate));
    }

    public function getTempCart() {
        if (cookie::has('cart:')) {
            $products = json_decode(Cookie::get('cart:'));
            $total_price = $this->getSubTotal($products);
            return view('home')->with('Products',$products)->with('total',$total_price);
        }
        return redirect('shopping')->with('message','your cart is empty');
    }

    public function tempCart() {
        if (cookie::has('cart:')) {
            $cartProducts = json_decode(Cookie::get('cart:'));
            return response()->json($cartProducts);
        }
        return '';
    }

    public function getSubTotal($products) {
        $total_price = 0;
        foreach ($products as $item) {
            if (is_array($item)) {
                $total_price += $item['price'] * $item['quantity'];
            }
        }
        return $total_price;
    }

    public function cart () {
        $customer = auth()->user();
        $redis = Redis::connection();
        $cached_products = json_decode($redis->get('cart_' . $customer->id),true);
        if (isset($cached_products)) {
            $total_price = $this->getSubTotal($cached_products);
            return view('home')->with('Products',$cached_products)->with('total',$total_price);
        }
        return redirect('shopping')->with('message','your cart is empty');
    }

    public function addProduct (Product $product, $quantity) {
        $customer = auth()->user();
        $redis = Redis::connection();

        $cached_products = $redis->get('cart_' .$customer->id);
        $new_product[] = $product->attributesToArray();
        $new_product[0]["quantity"] = $quantity;
        $new_product [] = array_push($new_product);

        if (isset($cached_products)) {
            $redis_products = $this->check(array_shift($new_product),json_decode($cached_products,true));
            $redis->set('cart_' .$customer->id, json_encode($redis_products));
        }else {
            $redis->set('cart_' . $customer->id, json_encode($new_product));
            $redis->get('cart_' . $customer->id);
        }

        $products = json_decode($redis->get('cart_' . $customer->id),true);
        $total_price = $this->getSubTotal($products);
        return view('home')->with('Products',$products)->with('total',$total_price);
    }

    public function check($product, $cached_products) {
        $redis = array(); $iteration = 0; $count = count($cached_products);
        foreach ($cached_products as  $key => $cached_product) {
            if (is_array($cached_product)) {
                $iteration++;
                if ($product['id'] == $cached_product['id']) {
                    return $cached_products;
                }else {
                    $redis [] = array_push($redis,$cached_product);
                }
            }else {
                unset($cached_products[$key]);
                array_values($cached_products);
            }
        }
        $redis [] = array_push($redis,$product);
        return $redis;
    }

    public function deleteProduct (Product $product)
    {
        $customer = auth()->user();
        $redis = Redis::connection();
        $cached_products = json_decode($redis->get('cart_' . $customer->id),true);
        foreach ($cached_products as  $key => $cached_product) {
            if (is_array($cached_product)) {
                if ($product->id == $cached_product['id']) {
                    unset($cached_products[$key]);
                    array_values($cached_products);
                    $redis->set('cart_' . $customer->id, json_encode($cached_products));
                    return redirect(route('cart'));
                }
            }
        }
    }

    public function deleteCart() {
       $customer = auth()->user();
       Redis::del('cart_' .$customer->id);
       return redirect('shopping')->with('message','your cart is empty');
   }
    public function deleteTempCart() {
        Cookie::forget('cart:');
    }

    /**
    public function mergeCart ($cart) {
    $customer = auth()->user();
    $redis = Redis::connection();
    $cart = $cart->getContent();
    $redisCart = Redis::get('cart_' .$customer->id);
    $final = $cart;
    if (isset($redisCart)){$final = $redisCart;}
    if ( isset($redisCart) && cookie::has('cart:')) {
    $items_1 = json_decode($cart,true);
    $items_2 = json_decode($redisCart,true);
    $final = $this->check($items_1,$items_2);
    }
    Redis::del('cart_' .$customer->id);
    $redis->set('cart_' .$customer->id, $final);
    $this->deleteTempCart();
    }
     */

}
