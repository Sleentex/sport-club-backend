<?php

namespace App\Http\Requests\User;

use App\Http\Responses\User\UserLoginResponse;
use Illuminate\Foundation\Http\FormRequest;

class UserLogin extends FormRequest
{
    private $access_token;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ];
    }

    public function perform()
    {
        try {
            $this->login();

            return (new UserLoginResponse($this->access_token))->toArray();
        } catch (\Throwable $exception){
            return [
                'status_code' => 422,
                'error'       => $exception->getMessage()
            ];
        }

    }

    public function login()
    {
        if ( ! $this->access_token = auth()->attempt($this->validated())) {
            throw new \Exception('Wrong credentials');
        }

        return $this;
    }
}
