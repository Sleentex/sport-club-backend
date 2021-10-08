<?php

namespace App\Http\Requests\User;

use App\Enums\UserRoles;
use App\Http\Responses\User\UserLoginResponse;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCreate extends FormRequest
{
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
            'email' => 'required|email|unique:users|max:50',
            'phone_number' => 'required|regex:/[0-9]{10}/|unique:users',
            'password' => 'required|string|min:6',
            'first_name' => 'required|string|min:2|max:32',
            'last_name' => 'required|string|min:2|max:32',
            'role'      => ['required', 'string', Rule::in(UserRoles::getValues())]
        ];
    }

    public function perform() {

        try {
            $this->createUser();
            $token = auth()->attempt($this->only(['email', 'password']));

            return (new UserLoginResponse($token))->toArray();
        } catch (\Throwable $exception){
            return [
                'status_code' => 422,
                'error'       => $exception->getMessage()
            ];
        }

    }

    public function createUser() {
        $attributes = $this->validated();
        $user = User::query()->create(array_merge($attributes,
            ['password' => bcrypt($this->input('password'))]));

        return $this;
    }
}
