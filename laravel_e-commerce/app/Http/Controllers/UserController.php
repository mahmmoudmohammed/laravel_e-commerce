<?php

namespace App\Http\Controllers;


use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{


    /**
     * @OA\Get(
     *      path="/api/admins",
     *      operationId="index",
     *      tags={"Admin"},
     *      summary="Get list of Admins",
     *      description="Returns list of Admin  Data",
     *      security={ {"sanctum": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Property(property="admins", type="object", ref="#/components/schemas/User"),
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
            $admins = User::all();
            return $this->ApiResponse(Response::HTTP_OK, 'success',Null,$admins);
        } catch (Exception $e) {
            return $this->ApiResponse(Response::HTTP_NO_CONTENT,null, 'No data provided');
        }
    }

    /**
     * @OA\Post(
     * path="/api/admins",
     * summary="create admin",
     * description="create new admin ",
     * operationId="store",
     * tags={"Admin"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="store new Admin name",
     *    @OA\JsonContent(
     *       required={"name","email","password","contact","role"},
     *     @OA\Property(property="name", type="string", example="Admin"),
     *     @OA\Property(property="email", type="string", format="email", example="Admin@gmail.com"),
     *     @OA\Property(property="password", type="string",example="password12345"),
     *     @OA\Property(property="contact", type="string", example="01234567891"),
     *     @OA\Property(property="role", type="integer" ,example="user"),
     *        ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Admin created")
     *     )
     *  ),
     * @OA\Response(
     *    response=422,
     *    description="invalid input",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Admin can't be created try later")
     *        )
     *     )
     * )
     *
     */

    public function store(UserRequest $request)
    {
        $user = auth('sanctum')->user();
        $this->authorizeForUser($user,'store',$user);
        $data = $request->all();
        try{
            $admin = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'contact' => $data['contact'],
                'created_at' => Carbon::now(),
            ]);
            $admin->assignRole($data['role']);
        } catch (Exception $e) {
            return $this->ApiResponse(Response::HTTP_NO_CONTENT, 'can not create Admin try later');
        }
        return $this->ApiResponse(Response::HTTP_OK,'Admin Created Successfully',null,$admin);
    }

    /**
     * @OA\Get(
     *      path="/api/admins/{admin}",
     *      operationId="show",
     *      tags={"Admin"},
     *      summary="Get Admin profile",
     *      description="Returns Admins profile Data",
     *      security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="admin",
     *          description="Admin id",
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
     *              @OA\Property(property="admin", type="object", ref="#/components/schemas/User"),
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

    public function show(User $admin)
    {
        $user = auth('sanctum')->user();
        $this->authorizeForUser($user,'view', $admin);
        try{
            $profile = User::find($admin->id);
        }catch (Exception $e) {
            return $this->ApiResponse(Response::HTTP_NOT_FOUND, null, 'can not Find Admin Data');
        }
        return $this->ApiResponse(Response::HTTP_OK,null,null,$profile);
    }


    /**
     * @OA\Put (
     * path="/api/admins/{admin}",
     * summary="update existing admin",
     * description="update admin",
     * operationId="update",
     * tags={"Admin"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="admin",
     *          description="admin id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * @OA\RequestBody(
     *    required=true,
     *    description="update admin ",
     *    @OA\JsonContent(
     *       required={"email", "name", "contact", "role","password"},
     *     @OA\Property(property="name", type="string", example="Admin"),
     *     @OA\Property(property="email", type="string", format="email", example="admin@gmail.com"),
     *     @OA\Property(property="password", type="string",example="password12345"),
     *     @OA\Property(property="contact", type="string", example="01234567891"),
     *     @OA\Property(property="role", type="integer" ,example="admin"),
     *    ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="User updated")
     *     )
     *  ),
     * @OA\Response(
     *    response=422,
     *    description="invalid input",
     *    @OA\JsonContent(
     *       @OA\Property(property="validation error", type="string", example="Sorry, invalid User name")
     *        )
     *     )
     * )
     *
     */

    public function update(User $admin, UserRequest $request)
    {
        $user = auth('sanctum')->user();
        $this->authorize($user,'update');
        try{
            $data = $request->all();
            $admin->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'contact' => $data['contact'],
                'updated_at' => Carbon::now(),
            ]);
            $admin->assignRole($data['role']);
        } catch (Exception $e) {
            return $this->ApiResponse(Response::HTTP_BAD_REQUEST,null,' something error try again later');
        }
        return $this->ApiResponse(Response::HTTP_OK,'Profile Updated successfully',null);
    }


    /**
     * @OA\Delete(
     *      path="/api/admins/{admin}",
     *      operationId="destroy",
     *      tags={"Admin"},
     *      summary="Delete existing Admin",
     *      description="Deletes a Admin and returns no Message",
     *      security={ {"sanctum": {} }},
     *      @OA\Parameter(
     *          name="admin",
     *          description="Admin id",
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
     *              @OA\Property(property="success", type="string", example="Admin Moved to trash")
     *           )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
     *
     */

    public function destroy(User $admin)
    {
        $user = auth('sanctum')->user();
        $this->authorize($user,'delete');
        if ($admin->trashed()) {
            return $this->ApiResponse(Response::HTTP_NOT_FOUND, 'this Admin was deleted previously ');
        }
        try{
            $admin->delete();
        } catch (Exception $e) {
            return $this->ApiResponse(Response::HTTP_BAD_REQUEST,null,' something error try again later');
        }
        return $this->ApiResponse(Response::HTTP_MOVED_PERMANENTLY, 'account Moved to trash...' );
    }


    public function notFound()
    {
        return $this->ApiResponse(Response::HTTP_NOT_FOUND, null, 'THIS ADMIN NOT EXIST.');

    }

}
