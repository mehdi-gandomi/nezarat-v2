<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OfficeDocumentRequest;
use App\Models\OfficeFile;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OfficeDocumentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OfficeDocumentCrudController extends CrudController
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
        CRUD::setModel(\App\Models\OfficeDocument::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/office-document');
        CRUD::setEntityNameStrings('office document', 'office documents');
        $this->crud->entity_name_plural="فایل های دفاتر";
        $this->crud->entity_name="فایل دفتر";
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumns([
            [
                'name'  => 'office_code',
                'label' => 'کد دفتر',
                'type'  => 'text',
            ],
            [
                'name'  => 'inspection_date',
                'label' => 'تاریخ بازرسی',
                'type'  => 'date',
            ],
            [
                'name'  => 'files',
                'label' => 'فایل ها',
                'type'  => 'array',
            ],
            [
                'name'  => 'created_at',
                'label' => 'تاریخ ایجاد',
                'type'  => 'datetime',
            ],
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(OfficeDocumentRequest::class);
        
        // Get office codes from OfficeFile model
        $officeCodes = OfficeFile::all()->pluck('office_code', 'office_code')->toArray();
        
        CRUD::addFields([
            [
                'name'  => 'user_id',
                'label' => 'کاربر',
                'type'  => 'hidden',
                'value' => auth('backpack')->id(),
            ],
            
            [
                'name'        => 'office_code',
                'label'       => 'کد دفتر',
                'type'        => 'select2_from_array',
                'options'     => $officeCodes,
                'allows_null' => false,
            ],
            [
                'name'  => 'inspection_date',
                'label' => 'تاریخ بازرسی',
                'type'  => 'date_picker',
                'date_picker_options' => [
                    'todayBtn' => 'linked',
                    'format'   => 'dd-mm-yyyy',
                    'language' => 'fa'
                ],
            ],
            [
                'name'  => 'name',
                'label' => 'نام فایل (ها)',
                'type'  => 'text',
            ],
            [
                'name'  => 'files',
                'label' => 'فایل ها',
                'type'  => 'browse_multiple',
                'disk'  => 'public',
            ],
        ]);
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
