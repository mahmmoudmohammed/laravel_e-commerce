<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/shopping_cart/{product}/{quantity}",
     *      operationId="setTempCart",
     *      tags={"Cart"},
     *      summary="set specific product in shopping cart",
     *      description="Return sproduct Data",
     *     @OA\Parameter(
     *          name="product",
     *          description="product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="quantity",
     *          description="quantity",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Property(property="product", type="object", ref="#/components/schemas/Product"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *
     *  )
     */

    public function setTempCart(Product $product,$quantity = 1) {
        $expireDate = 2222;
        $cartProducts = array();
        $newProduct[] = $product->attributesToArray();
        $newProduct[0]["quantity"] = $quantity;
        if (cookie::has('cart:')) {
            $cartProducts = json_decode(Cookie::get('cart:'));
            $cartProducts = json_decode(json_encode($cartProducts), true);
        }else{
            $response = new Response('cart:');
            return $response->setContent('product added to Cart')->withCookie(cookie('cart:', json_encode($newProduct), $expireDate));
        }
        $cartProducts [] = array_push($cartProducts,$newProduct);
//        print_r($cartProducts);die;
        $response = new Response('cart:');
         return $response->setContent('product added to Cart')->withCookie(cookie('cart:', json_encode($cartProducts), $expireDate));
    }

    /**
     * @OA\Get(
     *      path="/api/shopping_cart",
     *      operationId="getTempCart",
     *      tags={"Cart"},
     *      summary="get products from cart",
     *      description="Returns products Data",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Property(property="product", type="object", ref="#/components/schemas/Product"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */

    public function getTempCart() {
        if (cookie::has('cart:')) {
            $cartProducts = json_decode(Cookie::get('cart:'));
            return response()->json($cartProducts);
        }
        return response()->json(['message' => 'Cart empty']);
    }

    public function deleteTempCart() {
        Cookie::forget('cart:');
    }

    /**
     * @OA\Post(
     *      path="/api/cart/{product}",
     *      operationId="setProduct",
     *      tags={"Cart"},
     *      security={ {"sanctum": {} }},
     *      summary="set specific product in cart",
     *      description="Returns specific product Data",
     *     @OA\Parameter(
     *          name="product",
     *          description="product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Property(property="product", type="object", ref="#/components/schemas/Product"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */

    public function createCart (Request $request, Product $product) {
        $customer = auth('sanctum')->user();
        $this->mergeCart($this->getTempCart());
        $cachedProduct = Redis::get('cart_' .$customer->id);
        return $this->ApiResponse(200,'product exist',null,json_decode($cachedProduct));
    }

    public function mergeCart (JsonResponse $cart) {
        $customer = auth('sanctum')->user();
        $redis = Redis::connection();
        $final ="";
        $items_1 = array();
        $items_2 = array();
        $cart = $cart->getContent();
        $redisCart = Redis::get('cart_' .$customer->id);
        $final = $cart;
        if (isset($redisCart)){$final = $redisCart;}

        if ( isset($redisCart) && cookie::has('cart:')) {
            $items_1 = json_decode($cart);
            $items_2 = json_decode($redisCart);
           $final = $this->check($items_1,$items_2);
        }
        Redis::del('cart_' .$customer->id);
        $redis->set('cart_' .$customer->id, $final);
        $this->deleteTempCart();
        return Redis::get('cart_' .$customer->id);
    }

    public function viewCart(Request $request, Product $product) {
        $customer = auth('sanctum')->user();
        $redis = Redis::connection();$cachedProducts = Redis::get('cart_' .$customer->id);
        return $this->ApiResponse(200,'product exist',null,json_decode($redis));

    }

    public function addProduct (Product $product) {
        $customer = auth('sanctum')->user();
        $cachedProducts = Redis::get('cart_' .$customer->id);
        $final = $this->check($product->attributesToArray(),json_decode($cachedProducts));
        return $this->ApiResponse(200,'product exist',null,json_decode($final));
    }

    public function check($items_1, $items_2) {
        foreach ($items_1 as $key_1 => $item_1) {
            foreach ($items_2 as $key_2 => $item_2) {
                if ($item_1->name == $item_2->name ) {
                    unset($items_1[$key_1]);
                    array_values($items_1);
                }
            }
        }
        return ltrim(json_encode($items_1),'[').substr_replace(json_encode($items_2),',','-1');
    }
}
