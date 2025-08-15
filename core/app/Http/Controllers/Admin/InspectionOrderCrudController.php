<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InspectionOrderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class InspectionOrderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InspectionOrderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\InspectionOrder::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/inspection-order');
        CRUD::setEntityNameStrings('حکم بازرسی', 'احکام بازرسی');

        // Filter by user_id for non-admin users
        if (auth('backpack')->user()->user_type != 1) {
            CRUD::addClause('where', 'user_id', auth('backpack')->id());
        }
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id')->label('شناسه');

        // Show user column only for admin users
        if (auth('backpack')->user()->user_type == 1) {
            CRUD::column('user.name')->label('کاربر');
        }

        CRUD::column('claim_no')->label('شماره ابلاغ');
        CRUD::column('claim_date_start')->label('تاریخ شروع')->type('date');
        CRUD::column('claim_date_end')->label('تاریخ پایان')->type('date');
        // CRUD::column('status')->label('وضعیت')->type('select_from_array')->options([
        //     'active' => 'فعال',
        //     'inactive' => 'غیرفعال'
        // ]);
        CRUD::column('is_active')->label('وضعیت فعلی')->type('boolean')->options([
            0 => 'غیرفعال',
            1 => 'فعال'
        ]);
        CRUD::column('created_at')->label('تاریخ ایجاد')->type('datetime');

        // Add custom column to show if edit is allowed
        CRUD::column('can_edit')->label('قابل ویرایش')->type('boolean')->options([
            0 => 'خیر',
            1 => 'بله'
        ]);

        // Disable create button if active order exists
        if (\App\Models\InspectionOrder::hasActiveOrder()) {
            CRUD::denyAccess('create');
        }
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        // Check if there's already an active order (only for create operation)
        if (\App\Models\InspectionOrder::hasActiveOrder()) {
            abort(403, 'یک دستور بازرسی فعال وجود دارد. لطفاً ابتدا آن را غیرفعال کنید.');
        }

        CRUD::setValidation(InspectionOrderRequest::class);

        CRUD::addFields([
            [
                'name'  => 'user_id',
                'label' => 'کاربر',
                'type'  => 'hidden',
                'value' => auth('backpack')->id(),
            ],
            [
                'name'  => 'claim_no',
                'label' => 'شماره ابلاغ',
                'type'  => 'text',
            ],
            [
                'name'  => 'claim_date_start',
                'label' => 'تاریخ شروع',
                'type'  => 'persian_datepicker',
                'date_picker_options' => [
                    'todayBtn' => 'linked',
                    'format'   => 'dd-mm-yyyy',
                    'language' => 'fa'
                ],
            ],
            [
                'name'  => 'claim_date_end',
                'label' => 'تاریخ پایان',
                'type'  => 'persian_datepicker',
                'date_picker_options' => [
                    'todayBtn' => 'linked',
                    'format'   => 'dd-mm-yyyy',
                    'language' => 'fa'
                ],
            ],
            [
                'name'  => 'claim_file',
                'label' => 'فایل ابلاغ',
                'type'  => 'browse',
                // 'hint'  => 'نام فایل یا مسیر فایل',
            ],
            [
                'name'    => 'status',
                'label'   => 'وضعیت',
                'type'    => 'select_from_array',
                'options' => [
                    'active'   => 'فعال',
                    'inactive' => 'غیرفعال'
                ],
                'default' => 'active',
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
        // Get the current entry being updated
        $entry = $this->crud->getCurrentEntry();

        // Check if there's already an active order (but allow if this is the current active order)
        if (\App\Models\InspectionOrder::hasActiveOrder() && (!$entry || !$entry->is_active)) {
            abort(403, 'یک دستور بازرسی فعال وجود دارد. لطفاً ابتدا آن را غیرفعال کنید.');
        }

        CRUD::setValidation(InspectionOrderRequest::class);

        CRUD::addFields([
            [
                'name'  => 'claim_no',
                'label' => 'شماره ابلاغ',
                'type'  => 'text',
            ],
            [
                'name'  => 'claim_date_start',
                'label' => 'تاریخ شروع',
                'type'  => 'persian_datepicker',
                'date_picker_options' => [
                    'todayBtn' => 'linked',
                    'format'   => 'dd-mm-yyyy',
                    'language' => 'fa'
                ],
            ],
            [
                'name'  => 'claim_date_end',
                'label' => 'تاریخ پایان',
                'type'  => 'persian_datepicker',
                'date_picker_options' => [
                    'todayBtn' => 'linked',
                    'format'   => 'dd-mm-yyyy',
                    'language' => 'fa'
                ],
            ],
            [
                'name'  => 'claim_file',
                'label' => 'فایل ابلاغ',
                'type'  => 'browse',
                // 'hint'  => 'نام فایل یا مسیر فایل',
            ],
            [
                'name'    => 'status',
                'label'   => 'وضعیت',
                'type'    => 'select_from_array',
                'options' => [
                    'active'   => 'فعال',
                    'inactive' => 'غیرفعال'
                ],
                'default' => 'active',
            ],
        ]);
    }

    /**
     * Define what happens when the Show operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-show
     * @return void
     */
    protected function setupShowOperation()
    {
        CRUD::column('id')->label('شناسه');
        CRUD::column('claim_no')->label('شماره ابلاغ');
        CRUD::column('claim_date_start')->label('تاریخ شروع')->type('date');
        CRUD::column('claim_date_end')->label('تاریخ پایان')->type('date');
        CRUD::column('claim_file')->label('فایل ابلاغ');
        CRUD::column('status')->label('وضعیت')->type('select_from_array')->options([
            'active' => 'فعال',
            'inactive' => 'غیرفعال'
        ]);
        CRUD::column('is_active')->label('وضعیت فعلی')->type('boolean')->options([
            0 => 'غیرفعال',
            1 => 'فعال'
        ]);
        CRUD::column('created_at')->label('تاریخ ایجاد')->type('datetime');
        CRUD::column('updated_at')->label('تاریخ بروزرسانی')->type('datetime');
    }

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        // Check if there's already an active order
        if (\App\Models\InspectionOrder::hasActiveOrder()) {
            abort(403, 'یک دستور بازرسی فعال وجود دارد. لطفاً ابتدا آن را غیرفعال کنید.');
        }

        $this->crud->setRequest($this->crud->validateRequest());
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
        // Get the current entry
        $entry = $this->crud->getCurrentEntry();

        // Check if order is expired (but allow editing if it's the current active order)
        if ($entry && $entry->claim_date_end < now()->toDateString() && !$entry->is_active) {
            abort(403, 'این دستور بازرسی منقضی شده است و قابل ویرایش نیست.');
        }

        $this->crud->setRequest($this->crud->validateRequest());
        $this->crud->unsetValidation(); // validation has already been run

        return $this->traitUpdate();
    }
}
