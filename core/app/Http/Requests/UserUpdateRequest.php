<?php
namespace App\Http\Requests;
use Backpack\PermissionManager\app\Http\Requests\UserUpdateCrudRequest as UpdateRequest;
class UserUpdateRequest extends UpdateRequest{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->get('id') ?? request()->route('id');

        return [
            'email'    => 'required|unique:'.config('backpack.permissionmanager.models.user', 'users').',email,'.$id,
            'password' => 'confirmed',
        ];
    }
}
