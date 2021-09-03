<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Exception;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/products",
     *      operationId="index",
     *      tags={"Product"},
     *      summary="Get list of products",
     *      description="Returns list of product Data",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Property(property="products", type="object", ref="#/components/schemas/Product"),
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
    public function index()
    {
        try {
            $products = Product::all()->simplePaginate(10);
            return $this->ApiResponse(Response::HTTP_OK, null, Null, $products);
        } catch (Exception $e) {
            return $this->ApiResponse(Response::HTTP_NO_CONTENT, null, 'No provided data ');
        }
    }

    /**
     * @OA\Post(
     * path="/api/search",
     * summary="search",
     * description="Search for product",
     * operationId="search",
     * tags={"Product"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Search for product",
     *    @OA\JsonContent(
     *           required={"term"},
     *          @OA\Property(property="term", type="string", example = "iphone"),
     *    ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *              @OA\Property(property="products", type="object", ref="#/components/schemas/Product"),
     *     )
     *  ),
     * @OA\Response(
     *    response=422,
     *    description="invalid input",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="no such this product match try later")
     *        )
     *     )
     * )
     *
     */

    public function search(Request $request)
    {
        try {
            $products = Product::where('name', 'Like', '%' . $request->term . '%')
                ->orderBy('id', 'DESC')
                ->simplePaginate(10);
            if (is_null($products)) {
                return $this->ApiResponse(Response::HTTP_NOT_FOUND, null, "No Products match");
            }
            return $this->ApiResponse(Response::HTTP_CREATED, "most relevant products", null, $products);
        } catch (Exception $e) {
            return $this->ApiResponse(Response::HTTP_BAD_REQUEST, null, "Data format is invalid try again");
        }

    }

    /**
     * @OA\Post(
     * path="/api/products",
     * summary="new product",
     * description="store new product",
     * operationId="store",
     * tags={"Product"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Store new product",
     *    @OA\JsonContent(
     *           required={"name","description","price","in_stock","price_after","vendor_id","specs"},
     *          @OA\Property(property="name", type="string", example = "iphone 12 pro"),
     *          @OA\Property(property="description", type="string", example = "Main Characteristics: ......"),
     *          @OA\Property(property="in_stock", type="integer", example = "111"),
     *          @OA\Property(property="price", type="double", example = "111.00"),
     *          @OA\Property(property="price_after", type="double", example = "111.05"),
     *          @OA\Property(property="has_offer", type="boolean", example = "false"),
     *      ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="product created")
     *     )
     *  ),
     * @OA\Response(
     *    response=422,
     *    description="invalid input",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="product can't be created try later")
     *        )
     *     )
     * )
     *
     */
    public function store(ProductRequest $request)
    {
        $user = auth('sanctum')->user();
        $this->authorize( 'store', Product::class);
//        $this->authorize($user,'store');
        try {
            $data = $request->all();
            $product = Product::create($data);
            return $this->ApiResponse(Response::HTTP_CREATED, "Product created successfully", null, $product);
        } catch (Exception $e) {
            return $this->ApiResponse(500, null, 'Product can\'t be created, try later');
        }
    }

    /**
     * @OA\Get(
     *      path="/api/products/{product}",
     *      operationId="show",
     *      tags={"Product"},
     *      summary="Get specific product ",
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
    public function show(Product $product)
    {
        if ($product->trashed()) {
            return $this->ApiResponse(Response::HTTP_NOT_FOUND, 'Product was deleted', null);
        }
        $product = Product::find($product->id);
        return $this->ApiResponse(Response::HTTP_OK, null, null, $product);
    }

    /**
     * @OA\Put (
     * path="/api/products/{product}",
     * summary="update existing product",
     * description="update product",
     * operationId="update",
     * tags={"Product"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="product",
     *          description="product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * @OA\RequestBody(
     *    required=true,
     *    description="update product name",
     *    @OA\JsonContent(
     *           required={"name","description","price","in_stock","price_after"},
     *          @OA\Property(property="name", type="string", example = "iphone 12 pro"),
     *          @OA\Property(property="description", type="string", example = "Main Characteristics: ......"),
     *          @OA\Property(property="in_stock", type="integer", example = "111"),
     *          @OA\Property(property="price", type="double", example = "111.00"),
     *          @OA\Property(property="price_after", type="double", example = "111.05"),
     *          @OA\Property(property="has_offer", type="boolean", example = "false"),
     *      ),
     *  ),
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *         @OA\Property(property="product", type="object", ref="#/components/schemas/Product"),
     *         @OA\Property(property="message", type="string", example="product updated")
     *     )
     *  ),
     * @OA\Response(
     *    response=422,
     *    description="invalid input",
     *    @OA\JsonContent(
     *       @OA\Property(property="validation error", type="string", example="Sorry, invalid product name")
     *        )
     *     )
     * )
     *
     */

    public function update(Product $product, ProductRequest $request)
    {
        $user = auth('sanctum')->user();
        $this->authorize($user,'update');
        try {
            $product->update($request->all());
            return $this->ApiResponse(Response::HTTP_ACCEPTED, 'Product updated', null, $product);
        } catch (Exception $e) {
            return $this->ApiResponse(500, 'Update process can not be complete, try later');
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/products/{product}",
     *      operationId="destroy",
     *      tags={"Product"},
     *      summary="Delete existing product",
     *      description="Delete existing product ",
     *      security={ {"sanctum": {} }},
     *      @OA\Parameter(
     *          name="product",
     *          description="product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="string", example="product Moved to trash")
     *           )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
     *
     */

    public function destroy(Product $product)
    {
        $user = auth('sanctum')->user();
        $this->authorize($user,'delete');
        if ($product->trashed()) {
            return $this->ApiResponse(Response::HTTP_NOT_FOUND, 'Product already deleted');
        }
        $product->delete();
        return $this->ApiResponse(Response::HTTP_MOVED_PERMANENTLY, 'Product Moved to trash...');
    }

    public function notFound()
    {
        return $this->ApiResponse(404, null, 'THIS PRODUCT NOT EXIST.');
    }
}

