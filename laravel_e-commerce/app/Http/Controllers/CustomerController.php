<?php

namespace App\Http\Controllers;


use Exception;
use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/customers",
     *      operationId="index",
     *      tags={"Customer"},
     *      summary="Get list of customers",
     *      description="Returns list of customer  Data",
     *      security={ {"sanctum": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Property(property="customers", type="object", ref="#/components/schemas/Customer"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     *     )
     */

    public function index()
    {
        $user = auth('sanctum')->user();
        $this->authorize($user,'viewAny');
        try {
            $customers = Customer::all();
            return $this->ApiResponse(Response::HTTP_OK, 'success',Null,$customers);
        } catch (Exception $e) {
            return $this->ApiResponse(Response::HTTP_NO_CONTENT,null, 'No data provided');
        }
    }

    /**
     * @OA\Post(
     * path="/api/customers",
     * summary="create customer",
     * description="create new customer ",
     * operationId="store",
     * tags={"Customer"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="store new customer name",
     *    @OA\JsonContent(
     *       required={"name","email","password", "role"},
     *     @OA\Property(property="name", type="string", example="customer"),
     *     @OA\Property(property="email", type="string", format="email", example="customer@gmail.com"),
     *     @OA\Property(property="password", type="string",example="password12345"),
     *     @OA\Property(property="role", type="integer" ,example="customer"),
     *        ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="customer created")
     *     )
     *  ),
     * @OA\Response(
     *    response=422,
     *    description="invalid input",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="customer can't be created try later")
     *        )
     *     )
     * )
     *
     */

    public function store(CustomerRequest $request)
    {
        $user = auth('sanctum')->user();
        $this->authorize( 'store', Customer::class);
        $data = $request->all();
        try{
            $customer = Customer::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'created_at' => Carbon::now(),
            ]);
            $customer->assignRole($data['role']);
        } catch (Exception $e) {
            return $this->ApiResponse(Response::HTTP_NO_CONTENT, 'can not create customer try later');
        }
        return $this->ApiResponse(Response::HTTP_OK,'customer Created Successfully',null,$customer);
    }

    /**
     * @OA\Get(
     *      path="/api/customers/{customer}",
     *      operationId="show",
     *      tags={"Customer"},
     *      summary="Get customer profile",
     *      description="Returns customers profile Data",
     *      security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="customer",
     *          description="customer id",
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
     *              @OA\Property(property="customer", type="object", ref="#/components/schemas/Customer"),
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
     * )
     */

    public function show(Customer $customer)
    {
        $user = auth('sanctum')->user();
        $this->authorizeForUser($user,'view', $customer);
        try{
            $profile = Customer::find($customer->id);
        }catch (Exception $e) {
            return $this->ApiResponse(Response::HTTP_NOT_FOUND, null, 'can not Find customer Data');
        }
        return $this->ApiResponse(Response::HTTP_OK,null,null,$profile);
    }


    /**
     * @OA\Put (
     * path="/api/customers/{customer}",
     * summary="update existing customer",
     * description="update customer",
     * operationId="update",
     * tags={"Customer"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="customer",
     *          description="customer id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * @OA\RequestBody(
     *    required=true,
     *    description="update customer ",
     *    @OA\JsonContent(
     *       required={"email", "name", "role","password"},
     *     @OA\Property(property="name", type="string", example="customer"),
     *     @OA\Property(property="email", type="string", format="email", example="customer@gmail.com"),
     *     @OA\Property(property="password", type="string",example="password12345"),
     *     @OA\Property(property="role", type="integer" ,example="customer"),
     *    ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Customer updated")
     *     )
     *  ),
     * @OA\Response(
     *    response=422,
     *    description="invalid input",
     *    @OA\JsonContent(
     *       @OA\Property(property="validation error", type="string", example="Sorry, invalid Customer name")
     *        )
     *     )
     * )
     *
     */

    public function update(Customer $customer, CustomerRequest $request)
    {
        $user = auth('sanctum')->user();
        $this->authorize($user,'update');
        try{
            $data = $request->all();
            $customer->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'updated_at' => Carbon::now(),
            ]);
            $customer->assignRole($data['role']);
        } catch (Exception $e) {
            return $this->ApiResponse(Response::HTTP_BAD_REQUEST,null,' something error try again later');
        }
        return $this->ApiResponse(Response::HTTP_OK,'Profile Updated successfully',null);
    }


    /**
     * @OA\Delete(
     *      path="/api/customers/{customer}",
     *      operationId="destroy",
     *      tags={"Customer"},
     *      summary="Delete existing customer",
     *      description="Deletes a customer and returns no Message",
     *      security={ {"sanctum": {} }},
     *      @OA\Parameter(
     *          name="customer",
     *          description="customer id",
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
     *              @OA\Property(property="success", type="string", example="customer Moved to trash")
     *           )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
     *
     */

    public function destroy(Customer $customer)
    {
        $user = auth('sanctum')->user();
        $this->authorize($user,'delete');
        if ($customer->trashed()) {
            return $this->ApiResponse(Response::HTTP_NOT_FOUND, 'this customer was deleted previously ');
        }
        try{
            $customer->delete();
        } catch (Exception $e) {
            return $this->ApiResponse(Response::HTTP_BAD_REQUEST,null,' something error try again later');
        }
        return $this->ApiResponse(Response::HTTP_MOVED_PERMANENTLY, 'account Moved to trash...' );
    }


    public function notFound()
    {
        return $this->ApiResponse(Response::HTTP_NOT_FOUND, null, 'THIS CUSTOMER NOT EXIST.');

    }

}
