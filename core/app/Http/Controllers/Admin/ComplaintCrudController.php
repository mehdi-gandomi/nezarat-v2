<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ComplaintRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ComplaintCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ComplaintCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Complaint::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/complaint');
        CRUD::setEntityNameStrings('complaint', 'complaints');
        $this->crud->denyAccess("create");
        $this->crud->denyAccess("update");
        $this->crud->denyAccess("delete");
          $this->crud->entity_name_plural="شکایات";
        $this->crud->entity_name="شکایت";
    }
    
      protected function setupShowOperation()
    {
        CRUD::column('first_name')->label("نام");
        CRUD::column('last_name')->label("نام خانوادگی");
        
        
        CRUD::column('office_code')->label("کد دفتر");
        CRUD::column('subject')->label("موضوع");
        CRUD::column('message')->label("متن پیام");
        CRUD::column('created_at_fa')->label("تاریخ");
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // set columns from db columns.
CRUD::column('first_name')->label("نام");
        CRUD::column('last_name')->label("نام خانوادگی");
        CRUD::column('mobile')->label("شماره همراه");
        CRUD::column('national_code')->label("شماره ملی");
        
        CRUD::column('office_code')->label("کد دفتر");
        CRUD::column('subject')->label("موضوع");
        
        CRUD::column('created_at_fa')->label("تاریخ");
        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ComplaintRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
