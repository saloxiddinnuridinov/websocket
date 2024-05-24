<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\TelegramManager;
use App\Http\Helpers\TransactionManage;
use App\Http\Resources\LoginResource;
use App\Http\ValidatorResponse;
use App\Jobs\ProcessEmail;
use App\Jobs\RevokePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Psr\SimpleCache\InvalidArgumentException;
use Tymon\JWTAuth\Facades\JWTAuth;
use function Sodium\randombytes_uniform;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'activateUser', 'login', 'revokePassword', 'resetPasswordVerify']]);
    }

    /**
     * @OA\Post(
     * path="/register",
     * summary="Post a new data",
     * description="Register",
     * tags={"Auth"},
     * @OA\RequestBody(required=true, description="data  credentials",
     *   @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema(type="object", required={"name","surname","email","password", "user_bio", "address"},
     *          @OA\Property(property="name", type="string", format="text", example="Salohiddin"),
     *          @OA\Property(property="surname", type="string", format="text", example="Nuriddinov"),
     *          @OA\Property(property="email", type="string", format="email", example="nuridinovsaloxiddin@gmail.com"),
     *          @OA\Property(property="password", type="string", format="password", example="admin123"),
     *          @OA\Property(property="user_bio", type="text", format="text", example="User haqida malumot"),
     *          @OA\Property(property="address", type="text", format="text", example="Address: Samarqand"),
     *      ),
     *    ),
     * ),
     *    @OA\Response(response=200,description="Successful operation",
     *        @OA\JsonContent(ref="#/components/schemas/User"),
     *      ),
     *    @OA\Response(response=404,description="Not found",
     *        @OA\JsonContent(ref="#/components/schemas/Error"),
     *      ),
     * )
     */
    public function register(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'user_bio' => ['required', 'string'],
            'address' => ['required', 'string', 'max:255'],
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }
        $student = User::where('email', $request->email)->first();
        if ($student) {
            return response()->json([
                'success' => false,
                'message' => "Avval Ro'yxatdan o'tgansiz",
                'data' => null,
                'code' => 400
            ]);
        } else {
            $data = [
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'password' => $request->password,
                'user_bio' => $request->user_bio,
                'address' => $request->address,
            ];
        }

        dispatch(new ProcessEmail($data));

        $text = "Yangi: User ro'yhatdan o'tdi " . "%0D%0A" . "Full name: <b>" . $data['name'] . $data['surname'] . "</b>%0D%0A" .
            "Email: <b>" . $data['email'] . "</b>";

        TelegramManager::sendTelegram($text);

        return response()->json([
            'success' => true,
            'message' => $request->email . " ga faollashtirish kodi yuborildi",
            'data' => null,
            'code' => 200
        ]);
    }

    /**
     * @OA\Post (
     *      path="/activate-user",
     *      tags={"Auth"},
     *      summary="Verification",
     *      description="Ro'yxatdan o'tgandan so'ng userni aktiv qilish",
     *       @OA\RequestBody(required=true,description="Verify",
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(type="object",
     *                  required={"email","verify_code"},
     *                  @OA\Property(property="email", type="string", format="email", example="nuridinovsaloxiddin@gmail.com"),
     *                  @OA\Property(property="verify_code", type="integer", format="number", example=123456),
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200,description="Success",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="SUCCESS")),
     *      )
     * )
     * @throws InvalidArgumentException
     */

    public function activateUser(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:users,email',
            'verify_code' => 'required|numeric',
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }

        $email = trim($request->email);

        $getCache = Cache::store('database')->get($email);

        if (Cache::store('database')->has($email)) {

            if ($getCache['verify_code'] == $request->verify_code) {
                try {
                    $user = User::where('email', $email)->first();
                    if (!$user) {
                        $user = new User();
                        $user->name = $getCache['name'];
                        $user->surname = $getCache['surname'];
                        $user->email = $email;
                        $user->password = Hash::make($getCache['password']);
                        $user->username = env('APP_URL') . "/@". strtolower($getCache['name']) .'-'.strtolower($getCache['surname']) .'-'. random_int(1, 9999);
                        $user->bio = $getCache["user_bio"];
                        $user->address = $getCache["address"];
                        $user->save();
                    } else {
                        $user->name = $getCache['name'];
                        $user->surname = $getCache['surname'];
                        $user->email = $email;
                        $user->password = Hash::make($getCache['password']);
                    }
                    $token = JWTAuth::fromUser($user);
                    $authorization = [
                        'user' => $user,
                        'token' => $token,
                        'type' => 'bearer',
                    ];

                    Cache::store('database')->forget($email);

                    $text = "Yangi: User Aktiv bo`ldi" . "%0D%0A" . "Full name: <b>" . $user->name . " " . $user->surname . "</b>%0D%0A" .
                        "Email: <b>" . $user->email . "</b>";
                    TelegramManager::sendTelegram($text);

                    return response()->json([
                        'success' => true,
                        'message' => "Akkount faollashdi",
                        'data' => $authorization,
                        'code' => 200
                    ]);
                } catch (\Exception $exception) {
                    return response()->json([
                        'success' => false,
                        'message' => $exception->getMessage() . $exception->getLine(). $exception->getFile(),
                        'data' => null,
                        'code' => 400
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'kod xato',
                    'data' => $getCache['verify_code'],
                    'code' => 400
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'foydalanuvchi topilmadi',
                'data' => null,
                'code' => 404
            ]);
        }
    }

    /**
     * * * * * *  * * * *  * * * * * *
     * @OA\Post(
     * path="/login",
     * summary="login",
     * description="Login emaill, password",
     * tags={"Auth"},
     * @OA\RequestBody(required=true, description="Pass credentials",
     *    @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema(type="object", required={"email","password"},
     *          @OA\Property(property="email", type="string", format="text", example="user@gmail.com"),
     *          @OA\Property(property="password", type="string", format="password", example="admin123"),
     *      ),
     *    ),
     * ),
     *      @OA\Response(response=200,description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User"),
     *      ),
     *      @OA\Response(response=404,description="Not found",
     *          @OA\JsonContent(ref="#/components/schemas/Error"),
     *      ),
     * )
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => ['required', 'string', 'max:255', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }
        $email = trim($request->email);
        $model = User::where('email', $email)->first();
        if ($model) {
            if (Hash::check($request->password, $model->password)) {

                $credentials = $request->only('email', 'password');
                $token = auth('api')->attempt($credentials);
                if (!$token) {
                    return response()->json(['errors' => ['Unauthorized']], 401);
                }
                $user = auth('api')->user();
                $user->authorization = [
                    'token' => $token,
                    'type' => 'bearer',
                ];
                return response()->json([
                    'success' => true,
                    'message' => "Success",
                    'data' => $user,
                    'code' => 200
                ]);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Parol xato",
                    'data' => null,
                    'code' => 403
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Foydalanuvchi topilmadi",
                'data' => null,
                'code' => 404
            ]);
        }
    }

    /**
     * * * * * *  * * * *  * * * * * *
     * @OA\Post(
     * path="/revoke-password",
     * summary="password revoke",
     * description="revoke password",
     * security={{ "api": {} }},
     * tags={"Auth"},
     * @OA\RequestBody(required=true, description="Pass credentials",
     *    @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema(type="object", required={"email"},
     *          @OA\Property(property="email", type="string", format="text", example="user@gmail.com"),
     *      ),
     *    ),
     * ),
     *      @OA\Response(response=200,description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User"),
     *      ),
     *      @OA\Response(response=404,description="Not found",
     *          @OA\JsonContent(ref="#/components/schemas/Error"),
     *      ),
     * )
     */
    public function revokePassword(Request $request)
    {
        $rules = [
            'email' => ['required', 'exists:users,email'],
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }
        $login = $request->email;
        $user = User::where('email', $login)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "Foydalanuvchi topilmadi!",
                'data' => null,
                'code' => 404
            ]);
        }

        $data = [
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
        ];

        dispatch(new RevokePassword($data));

        return response()->json([
            'success' => true,
            'message' => $request->email . " ga tasdiqlash kodi yuborildi",
            'data' => null,
            'code' => 200
        ]);
    }

    /**
     * @OA\Post (
     *      path="/reset-password-verify",
     *      tags={"Auth"},
     *      summary="reset password",
     *      description="Parolni tiklash uchun tasdiqlash kodi",
     *       @OA\RequestBody(required=true,description="Verify",
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(type="object",
     *                  required={"email","verify_code", "password", "password_confirmation"},
     *                  @OA\Property(property="email", type="string", format="email", example="nuridinovsaloxiddin@gmail.com"),
     *                  @OA\Property(property="verify_code", type="integer", format="number", example=123456),
     *                  @OA\Property(property="password", type="string", format="password", example="user12345"),
     *                  @OA\Property(property="password_confirmation", type="string", format="password", example="user12345"),
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200,description="Success",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="SUCCESS")),
     *      )
     * )
     */
    public function resetPasswordVerify(Request $request): \Illuminate\Http\JsonResponse
    {
        $rules = [
            'email' => ['required', 'exists:users,email'],
            'verify_code' => ['required', 'numeric'],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }
        $email = trim($request->email);

        $getCache = Cache::store('database')->get($email);

        if (Cache::store('database')->has($email)) {
            if ($getCache['verify_code'] == $request->verify_code) {
                $student = User::where('email', $email)->first();
                $student->password = Hash::make($request->password);
                $token = JWTAuth::fromUser($student);
                $student->update();

                return response()->json([
                    'success' => true,
                    'message' => 'Parol o`zgartirildi',
                    'data' => [
                        'user' => $student,
                        'authorization' => [
                            'token' => $token,
                            'type' => 'bearer',
                        ]
                    ],
                    'code' => 200
                ]);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Tasdiqlash kodi xato",
                    'data' => null,
                    'code' => 400
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Foydalanuvchi topilmadi',
                'data' => null,
                'code' => 404
            ]);
        }
    }

    /**
     * @OA\Post(
     * path="/logout",
     * summary="Post a new data",
     * description="LogOut",
     * security={{ "api": {} }},
     * tags={"Auth"},
     * @OA\Response(response=200,description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/User"),
     * ),
     * @OA\Response(response=404,description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/Error"),
     * ),
     * )
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json([
            'success' => true,
            'message' => 'Hisobdan muvaffaqiyatli chiqdi',
            'data' => null,
            'code' => 200
        ]);
    }
    public function refresh()
    {
        return response()->json([
            'success' => true,
            'message' => auth('api')->user(),
            'data' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ],
            'code' => 200
        ]);
    }

    public function me()
    {
        return response()->json(auth('api')->user(), 200);
    }

    /**
     * @OA\Post(
     * path="/change-password",
     * summary="Change password",
     * description="Register",
     * tags={"Setting"},
     * security={{ "api": {} }},
     * @OA\RequestBody(required=true, description="data  credentials",
     *   @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema(type="object", required={"old_password","password", "password_confirmation"},
     *          @OA\Property(property="old_password", type="string", format="password", example="admin123"),
     *          @OA\Property(property="password", type="string", format="password", example="user12345"),
     *          @OA\Property(property="password_confirmation", type="string", format="password", example="user12345"),
     *      ),
     *    ),
     * ),
     *    @OA\Response(response=200,description="Successful operation",
     *        @OA\JsonContent(ref="#/components/schemas/User"),
     *      ),
     *    @OA\Response(response=404,description="Not found",
     *        @OA\JsonContent(ref="#/components/schemas/Error"),
     *      ),
     * )
     */
    public function changePassword(Request $request)
    {
        $rules = [
            'old_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }
        $auth = auth('api')->user();
        $user = User::find($auth->id);
        try {
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->update();

                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'Change password Success',
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Parol xato kiritildi",
                    'data' => null,
                    'code' => 404
                ]);
            }
        } catch (\Exception $exception){
            return 'File: '. $exception->getFile() . 'line: ' . $exception->getLine() . 'Message ' . $exception->getMessage();
        }


    }

}
