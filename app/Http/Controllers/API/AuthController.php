<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * @var string
     */
    protected string $authKey;
    public function __construct()
    {
        $this->authKey = config('system')['_token'];
    }

    /**
     * @param RegisterUserRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $success['token'] = $user->createToken($this->authKey)->plainTextToken;
        $success['name'] = $user->name;

        return $this->successResponse($success, 'User register successfully.', 201);
    }
 
    /**
     * @param LoginUserRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = User::where('email', $request->email)->first();
            $success['token'] = $user->createToken($this->authKey)->plainTextToken; 
            $success['name'] = $user->name;

            return $this->successResponse($success, 'User login successfully.');
        } else { 
            return $this->errorResponse('Unauthorized.', 401);
        } 
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $accessToken = $request->bearerToken();
        if (!$accessToken) {
            return $this->errorResponse('Unauthorized.', 401);
        }
        $token = PersonalAccessToken::findToken($accessToken);
        if (!$token) {
            return $this->errorResponse('Unauthorized.', 401);
        }
        $token->delete();
        return $this->successResponse([], 'User logged out successfully.');
    }
}
    