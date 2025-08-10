<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InspectionLogRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Detection\MobileDetect;
use Illuminate\Http\Request;
use App\Models\InspectionLog;
/**
 * Class InspectionLogCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InspectionLogCrudController extends CrudController
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
        CRUD::setModel(\App\Models\InspectionLog::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/inspection-log');
        CRUD::setEntityNameStrings('inspection log', 'inspection logs');
        $this->crud->denyAccess("create");
        $this->crud->denyAccess("update");
        $this->crud->denyAccess("delete");
        $detect = new MobileDetect();
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        $detect->setUserAgent($useragent);

        if($detect->isMobile() || $detect->isTablet() || $detect->isiPad() || $detect->isNexusTablet() || $detect->isSamsungTablet()){
            CRUD::setShowView('inspection-log.show-mobile');
        }else{
            CRUD::setShowView('inspection-log.show');
        }
		$this->crud->entity_name_plural="سوابق بازرسی";
        $this->crud->entity_name="سابقه بازرسی";
        if(request('office_code')){
            CRUD::addClause('where', 'office_code', '=', request('office_code'));
        }
		if(auth('backpack')->user()->user_type == 4){
			CRUD::addClause('where', 'user_id', '=', auth('backpack')->user()->id);
		}
		CRUD::filter('from_date')
			->type('date')
			->label("از تاریخ")
			->whenActive(function($value) {
			CRUD::addClause('whereDate', 'inspection_date','>=', $value);
			});
		CRUD::filter('to_date')
			->type('date')
			->label("تا تاریخ")
			->whenActive(function($value) {
			CRUD::addClause('whereDate', 'inspection_date','<=', $value);
			});
    }
  /**
     * Default behaviour for the Show Operation, in case none has been
     * provided by including a setupShowOperation() method in the CrudController.
     */
    protected function autoSetupShowOperation()
    {
        CRUD::column('office_code')->label("کد دفتر");
		CRUD::column('adapt')->label("تطابق اطلاعات")->type("switch");
		CRUD::column('user.name')->label("نام بازرس");
        CRUD::column('inspection_period')->label("دوره بازرسی");
        CRUD::column('inspection_date_fa')->label("تاریخ بازرسی");
        CRUD::column('requires_second_inspection')->label("نیاز به بازرسی مجدد")->type("switch");
        CRUD::column('second_inspection_date_fa')->label("تاریخ بازرسی مجدد");
        CRUD::column('inspector_signature')->label("امضا بازرس")->type("image")->prefix('storage')->width("200px")->height("200px");

        CRUD::column('legal_expert_signature')->label("امضا کارشناس حقوقی / مدیر دفتر")->type("image")->prefix('storage')->width("200px")->height("200px");
        CRUD::column('office_manager_signature')->label("امضا مدیر دفتر")->type("image")->prefix('storage')->width("200px")->height("200px");
        CRUD::column('unauthorized_users')->label("کاربران غیرمجاز");
		CRUD::column('obligations')->label("صورت جلسه و تعهدات مدیر دفتر / کارشناس حقوقی");
        CRUD::column('second_inspection_summary')->label("جمع بندی و ارزیابی حاصل از بازرسی مجدد:");

        // if the model has timestamps, add columns for created_at and updated_at
        if ($this->crud->get('show.timestamps') && $this->crud->model->usesTimestamps()) {
            $this->crud->column($this->crud->model->getCreatedAtColumn()."_fa")->type('text')->label('تاریخ ثبت');
            // $this->crud->column($this->crud->model->getUpdatedAtColumn())->type('datetime')->label('تاریخ ویرایش');
        }

        // if the model has SoftDeletes, add column for deleted_at
        if ($this->crud->get('show.softDeletes') && in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->crud->model))) {
            $this->crud->column($this->crud->model->getDeletedAtColumn())->type('datetime');
        }

        // remove the columns that usually don't make sense inside the Show operation
        $this->removeColumnsThatDontBelongInsideShowOperation();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $this->crud->hasAccessOrFail('show');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry (include softDeleted items if the trait is used)
        if ($this->crud->get('show.softDeletes') && in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->crud->model))) {
            $this->data['entry'] = $this->crud->getModel()->withTrashed()->findOrFail($id);
        } else {
            $this->data['entry'] = $this->crud->getEntryWithLocale($id);
        }

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.preview').' '.$this->crud->entity_name;
		$this->data['entry']->read_at=now();
		$this->data['entry']->save();
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getShowView(), $this->data);
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
        CRUD::column('office_code')->label("کد دفتر");
		CRUD::column('inspection_date_fa')->label("تاریخ بازرسی");
		// CRUD::column('adapt')->label("تطابق اطلاعات")->type("switch");
		CRUD::column('user.name')->label("نام بازرس");
        CRUD::column('inspection_period')->label("دوره بازرسی");
        CRUD::column('inspection_date_fa')->label("تاریخ بازرسی");
        CRUD::column('requires_second_inspection')->label("نیاز به بازرسی مجدد")->type("switch");
        // CRUD::column('second_inspection_date_fa')->type("closure")->function()->label("تاریخ بازرسی مجدد");
        CRUD::addColumn([
    'name'     => 'second_inspection_date_fa',
    'label'    => 'تاریخ بازرسی مجدد',
    'type'     => 'closure',
	'escaped'=>false,
    'function' => function($entry) {
        return "<span class='read-status' data-value='".$entry->read_at."'></span>".$entry->second_inspection_date_fa;
    }
],);
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
        CRUD::setValidation(InspectionLogRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.
        CRUD::field('office_code')->label("کد دفتر");
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

	public function print(Request $request,$id){
		$inspectionLog=InspectionLog::with('office','user')->find($id);
		return view(backpack_view("print"),compact('inspectionLog'));
	}
}
