<?php


namespace App\Http\Responses\User;


/**
 * @OA\Schema(
 * @OA\Xml(name="UserLoginResponse"),
 * @OA\Property(property="access_token", type="string", example="siaudiUISIDYIU!$jjsyeewr6545sad"),
 * @OA\Property(property="token_type", type="string", example="bearer"),
 * @OA\Property(property="expires_in", type="integer", example="3600"),
 * @OA\Property(property="email", type="string", format="email", description="User unique email address", example="user@gmail.com"),
 * @OA\Property(property="phone_number", type="string", example="0990619649"),
 * )
 */
class UserLoginResponse
{
    private $token;
    private $user;
    private $token_expires_in;

    public function __construct($token)
    {
        $this->token = $token;
        $this->user = auth()->user();
        $this->token_expires_in = auth()->factory()->getTTl() * 60;
    }

    public function toArray()
    {
        return [
            'status_code' => 200,
            'access_token' => $this->token,
            'token_type'   => 'bearer',
            'expires_in'   => $this->token_expires_in,
            'user'         => $this->user
        ];
    }
}
