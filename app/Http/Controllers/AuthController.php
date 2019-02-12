<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\PasswordResetEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    // Token life time in hours
    const TOKEN_LIFE_TIME = 24;

    /** @var JWTAuth $jwt */
    private $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        try {
            if (!$token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json([], 401);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], 500);
        }

        return response()->json([
            'token' => $token,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendPasswordResetEmail(Request $request): JsonResponse
    {
        /** @var string $token */
        $token = md5(str_random(24).microtime());

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::to($request->email)->send(new PasswordResetEmail($token));

        return response()->json([]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        $record = DB::table('password_resets')
            ->where('token', $request->route('token'))
            ->first();

        if ($this->checkTokenLifetime($record->created_at)) {
            if ($request->password1 === $request->password2) {
                User::where('email', $record->email)->update(['password' => app('hash')->make($request->password1)]);
            }

            return response()->json([]);
        }

        return response()->json([], 404);
    }

    /**
     * @param $submittedAt
     * @return bool
     */
    private function checkTokenLifetime($submittedAt): bool
    {
        return ($submittedAt) && Carbon::createFromFormat('Y-m-d H:i:s', $submittedAt)->addHours(self::TOKEN_LIFE_TIME) > Carbon::now();
    }
}