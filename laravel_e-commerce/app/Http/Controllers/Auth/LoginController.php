<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiDesignTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    use AuthenticatesUsers, ApiDesignTrait;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * @OA\Post(
     * path="/api/admin/login",
     * summary="Admin",
     * description="Login Admin and Create token",
     * operationId="adminLogin",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="store admin data",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *     @OA\Property(property="email", type="email", format="email", example="admin@gmail.com"),
     *     @OA\Property(property="password", type="password", format="email", example="password12345"),
     *        )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\Property(property="User", type="object", ref="#/components/schemas/User"),
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="Returns when user not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="faild to Logedin"),
     *    )
     * )
     * )
     */

    public function adminLogin(Request $request)
    {
        $this->validateLogin($request);
        $data = $request->all();
        if (auth()->guard()->attempt($data)) {
            $admin = auth()->guard()->user();
            if ($admin->deleted_at != Null) {
                return "validation error";
            } else {
                $token = $admin->createToken('token-name')->plainTextToken;
                return $this->ApiResponse(200, 'Done', null, $token);
            }
        }
        return $this->ApiResponse(401, 'Bad credentials');
    }
    /**
     * @OA\Post(
     * path="/api/customer/login",
     * summary="Customer",
     * description="Login Customer and Create token",
     * operationId="customerLogin",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="store Customer data",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *     @OA\Property(property="email", type="email", format="email", example="customer@gmail.com"),
     *     @OA\Property(property="password", type="password", format="email", example="password12345"),
     *        )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\Property(property="Customer", type="object", ref="#/components/schemas/Customer"),
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="Returns when user not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="faild to Logedin"),
     *    )
     * )
     * )
     */

    public function customerLogin(Request $request)
    {
        $this->validateLogin($request);
        $data = $request->all();
//        var_dump($data);die;
        if (auth()->guard('customer')->attempt($data)) {
            $admin = auth()->guard('customer')->user();
            if ($admin->deleted_at != Null) {
                return "validation error";
            } else {
                $token = $admin->createToken('token-name')->plainTextToken;
                return $this->ApiResponse(200, 'Done', null, $token);
            }
        }
        return $this->ApiResponse(401, 'Bad credentials');
    }


    /**
     * @OA\Post(
     * path="/api/apiLogout",
     * summary="Logout",
     * description="Logout Admin and delete token",
     * operationId="logout",
     * tags={"Auth"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Success"
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Logedout"),
     *    )
     * )
     * )
     */


    public function apiLogout()
    {
        $admin = auth('sanctum')->user();
        $admin->tokens()->where('id', $admin->currentAccessToken()->id)->delete();
        return $this->ApiResponse(Response::HTTP_OK, 'logged out', null);
    }

}
