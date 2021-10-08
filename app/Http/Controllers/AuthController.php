<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserCreate;
use App\Http\Requests\User\UserLogin;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/login",
     *      summary="Sign in",
     *      description="Login by email, password",
     *      operationId="authLogin",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", ref="#/components/schemas/User/properties/email"),
     *              @OA\Property(property="password", ref="#/components/schemas/User/properties/password"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Wrong credentials response",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Unauthorized")
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Succesfully authorized",
     *          @OA\JsonContent(ref="#/components/schemas/UserLoginResponse")
     *     ),
     * )
     */
    function login(UserLogin $request)
    {
        $response = $request->perform();
        return response()->json($response, $response['status_code']);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     tags={"Auth"},
     *     operationId="registerUser",
     *     summary="Creates a new user",
     *     description="Creates a new user",
     *     @OA\RequestBody(
     *          required=true,
     *          description="Pass user registration data",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *              required={"first_name", "last_name", "email", "phone_number", "password", "role"},
     *              @OA\Property(property="first_name", ref="#/components/schemas/User/properties/first_name"),
     *              @OA\Property(property="last_name", ref="#/components/schemas/User/properties/last_name"),
     *              @OA\Property(property="email", ref="#/components/schemas/User/properties/email"),
     *              @OA\Property(property="role", type="string", enum={"trainer", "client"}, example="trainer"),
     *              @OA\Property(property="phone_number", ref="#/components/schemas/User/properties/phone_number"),
     *              @OA\Property(property="password", ref="#/components/schemas/User/properties/password"),
     *          ),
     *      )),
     *      @OA\Response(
     *          response=201,
     *          description="Successfully created",
     *          @OA\JsonContent(
     *              @OA\Property(property="status_code", type="integer", example=200),
     *              @OA\Property(property="access_token", type="string", example="eyJ0iOiJIUzI1NiJ9.eyJpc3In0.brg-LiJskiu_rRCx40S00DXqcQA"),
     *              @OA\Property(property="token_type", type="string", example="bearer"),
     *              @OA\Property(property="expires_in", type="integer", example=3600),
     *              @OA\Property(property="user", type="object",
     *              	@OA\Property(property="id", type="integer", example=5),
     *              	@OA\Property(property="email", type="string", example="user5@gmail.com"),
     *              	@OA\Property(property="role", type="string", example="client"),
     *              	@OA\Property(property="phone_number", type="string", example="0990619645"),
     *              	@OA\Property(property="first_name", type="string", example="Harry"),
     *              	@OA\Property(property="last_name", type="string", example="Potter"),
     *              	@OA\Property(property="email_verified_at", type="object", example=null),
     *              	@OA\Property(property="created_at", type="datetime", example="2021-10-08T19:41:31.000000Z"),
     *              	@OA\Property(property="updated_at", type="datetime", example="2021-10-08T19:41:31.000000Z"),
     *              ),
     *          ),
     *     )
     *    )
     *
     */
    function register(UserCreate $request)
    {
        $response = $request->perform();
        return response()->json($response, $response['status_code']);
    }


    /**
     * @OA\Get(
     *     path="/api/v1/profile",
     *     operationId="getUserProfile",
     *     summary="Get current profile",
     *     tags={"Auth"},
     *     security={ {"bearer": {} }},
     *     description="Returns user's profile",
     *     @OA\Response(
     *          response=200,
     *          description="Returns user's profile (also in user can be additional fields if user is customer or executor)",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="email", type="string", example="user@gmail.com"),
     *              @OA\Property(property="role", type="string", example="trainer"),
     *              @OA\Property(property="phone_number", type="string", example="0990619649"),
     *              @OA\Property(property="first_name", type="string", example="Harry"),
     *              @OA\Property(property="last_name", type="string", example="Potter"),
     *              @OA\Property(property="email_verified_at", type="object", example=null),
     *              @OA\Property(property="created_at", type="datetime", example="2021-10-08T14:46:50.000000Z"),
     *              @OA\Property(property="updated_at", type="datetime", example="2021-10-08T14:46:50.000000Z"),
     *          ),
     *     ),
     * )
     */
    function profile(Request $request)
    {
        return response()->json(auth()->user(), 200);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     operationId="logout",
     *     description="Loggind out current user",
     *     summary="Logging out",
     *     tags={"Auth"},
     *     security={ {"bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successfully logged out",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Succesfully logged out")
     *          )
     *      )
     * )
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
