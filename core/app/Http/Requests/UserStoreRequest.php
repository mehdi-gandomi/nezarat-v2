<?php
namespace App\Http\Requests;
use Backpack\PermissionManager\app\Http\Requests\UserStoreCrudRequest as StoreRequest;
class UserStoreRequest extends StoreRequest{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'=>'sometimes|unique:users',
            'password' => 'required|confirmed',
        ];
    }
}
