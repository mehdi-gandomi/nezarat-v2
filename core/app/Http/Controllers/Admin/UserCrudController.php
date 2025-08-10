<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Models\City;
use App\Models\Province;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Requests\UserStoreRequest as StoreRequest;
use App\Http\Requests\UserUpdateRequest as UpdateRequest;

use Illuminate\Support\Facades\Hash;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel(config('backpack.permissionmanager.models.user'));
        $this->crud->setEntityNameStrings(trans('backpack::permissionmanager.user'), trans('backpack::permissionmanager.users'));
        $this->crud->setRoute(backpack_url('user'));
		$this->crud->entity_name_plural="بازرس ها";
        $this->crud->entity_name="بازرس";
		CRUD::addClause('whereIn', 'user_type', [3,4]);
		CRUD::addClause('where', 'master', 0);
    }

    public function setupListOperation()
    {
        $this->crud->addColumns([
            // [
            //     'name'  => 'avatar',
            //     'label' => 'تصویر بازرس',
            //     'type'  => 'image',
            // ],
            [
                'name'  => 'first_name',
                'label' => 'نام',
                'type'  => 'text',
            ],
            [
                'name'  => 'last_name',
                'label' => 'نام خانوادگی',
                'type'  => 'text',
            ],
			 [
                'name'  => 'provinces',
                'label' => 'استان',
                'type'  => 'provinces',
            ],
  [
                'name'  => 'email',
                'label' => 'نام کاربری',
                'type'  => 'text',
            ],

			
        ]);

        if (backpack_pro()) {
            // Role Filter
            // $this->crud->addFilter(
            //     [
            //         'name'  => 'role',
            //         'type'  => 'dropdown',
            //         'label' => trans('backpack::permissionmanager.role'),
            //     ],
            //     config('permission.models.role')::all()->pluck('name', 'id')->toArray(),
            //     function ($value) { // if the filter is active
            //         $this->crud->addClause('whereHas', 'roles', function ($query) use ($value) {
            //             $query->where('role_id', '=', $value);
            //         });
            //     }
            // );

            // // Extra Permission Filter
            // $this->crud->addFilter(
            //     [
            //         'name'  => 'permissions',
            //         'type'  => 'select2',
            //         'label' => trans('backpack::permissionmanager.extra_permissions'),
            //     ],
            //     config('permission.models.permission')::all()->pluck('name', 'id')->toArray(),
            //     function ($value) { // if the filter is active
            //         $this->crud->addClause('whereHas', 'permissions', function ($query) use ($value) {
            //             $query->where('permission_id', '=', $value);
            //         });
            //     }
            // );
        }
    }

    public function setupCreateOperation()
    {
        $this->addUserFields();
        $this->crud->setValidation(StoreRequest::class);
    }

    public function setupUpdateOperation()
    {
        $this->addUserFields();
        $this->crud->setValidation(UpdateRequest::class);
    }

    public function setupShowOperation()
    {
        // automatically add the columns
        $this->crud->column('name');
        $this->crud->column('email');
        $this->crud->column([
            // two interconnected entities
            'label'             => trans('backpack::permissionmanager.user_role_permission'),
            'field_unique_name' => 'user_role_permission',
            'type'              => 'checklist_dependency',
            'name'              => 'roles_permissions',
            'subfields'         => [
                'primary' => [
                    'label'            => trans('backpack::permissionmanager.role'),
                    'name'             => 'roles', // the method that defines the relationship in your Model
                    'entity'           => 'roles', // the method that defines the relationship in your Model
                    'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                    'attribute'        => 'name', // foreign key attribute that is shown to user
                    'model'            => config('permission.models.role'), // foreign key model
                ],
                'secondary' => [
                    'label'            => mb_ucfirst(trans('backpack::permissionmanager.permission_singular')),
                    'name'             => 'permissions', // the method that defines the relationship in your Model
                    'entity'           => 'permissions', // the method that defines the relationship in your Model
                    'entity_primary'   => 'roles', // the method that defines the relationship in your Model
                    'attribute'        => 'name', // foreign key attribute that is shown to user
                    'model'            => config('permission.models.permission'), // foreign key model,
                ],
            ],
        ]);
        $this->crud->column('created_at');
        $this->crud->column('updated_at');
    }

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $this->crud->setRequest($this->crud->validateRequest());
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));
        $this->crud->unsetValidation(); // validation has already been run

        return $this->traitStore();
    }

    /**
     * Update the specified resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        $this->crud->setRequest($this->crud->validateRequest());
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));
        $this->crud->unsetValidation(); // validation has already been run

        return $this->traitUpdate();
    }

    /**
     * Handle password input fields.
     */
    protected function handlePasswordInput($request)
    {
        // Remove fields not present on the user.
        $request->request->remove('password_confirmation');
        $request->request->remove('roles_show');
        $request->request->remove('permissions_show');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        return $request;
    }

    protected function addUserFields()
    {

        $provinces=Province::all()->keyBy('id')->pluck('name')->toArray();
		array_unshift($provinces,"ستاد");
        $this->crud->addFields([
            [   // Hidden
                'name'  => 'user_type',
                'type'  => 'hidden',
                'value' => '4',
            ],
            [
                'name'  => 'first_name',
                'label' => 'نام',
                'type'  => 'text',
            ],
            [
                'name'  => 'last_name',
                'label' => 'نام خانوادگی',
                'type'  => 'text',
            ],
            [
                'name'  => 'avatar',
                'label' => 'تصویر بازرس',
                'type'  => 'browse',
            ],
            [   // select2_from_array
                'name'        => 'provinces',
                'label'       => "استان ها",
                'type'        => 'select2_from_array',
                'options'     => $provinces,
                'allows_null' => false,
                'default'     => '',
                'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                'name'  => 'mobile',
                'label' => 'شماره همراه',
                'type'  => 'number',
            ],
            [
                'name'  => 'email',
                'label' => 'نام کاربری',
                'type'  => 'text',
            ],
            [
                'name'  => 'password',
                'label' => 'پسورد',
                'type'  => 'password',
            ],
            [
                'name'  => 'password_confirmation',
                'label' => 'تایید پسورد',
                'type'  => 'password',
            ],
            // [
            //     // two interconnected entities
            //     'label'             => trans('backpack::permissionmanager.user_role_permission'),
            //     'field_unique_name' => 'user_role_permission',
            //     'type'              => 'checklist_dependency',
            //     'name'              => 'roles,permissions',
            //     'subfields'         => [
            //         'primary' => [
            //             'label'            => trans('backpack::permissionmanager.roles'),
            //             'name'             => 'roles', // the method that defines the relationship in your Model
            //             'entity'           => 'roles', // the method that defines the relationship in your Model
            //             'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
            //             'attribute'        => 'name', // foreign key attribute that is shown to user
            //             'model'            => config('permission.models.role'), // foreign key model
            //             'pivot'            => true, // on create&update, do you need to add/delete pivot table entries?]
            //             'number_columns'   => 3, //can be 1,2,3,4,6
            //         ],
            //         'secondary' => [
            //             'label'          => mb_ucfirst(trans('backpack::permissionmanager.permission_plural')),
            //             'name'           => 'permissions', // the method that defines the relationship in your Model
            //             'entity'         => 'permissions', // the method that defines the relationship in your Model
            //             'entity_primary' => 'roles', // the method that defines the relationship in your Model
            //             'attribute'      => 'name', // foreign key attribute that is shown to user
            //             'model'          => config('permission.models.permission'), // foreign key model
            //             'pivot'          => true, // on create&update, do you need to add/delete pivot table entries?]
            //             'number_columns' => 3, //can be 1,2,3,4,6
            //         ],
            //     ],
            // ],
        ]);
    }
}
