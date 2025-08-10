<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InspectionLogRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class InspectionLogCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InspectionLogNotificationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    public function isMobile(){
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
    }
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\InspectionLog::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/inspection-log-notification');
        CRUD::setEntityNameStrings('inspection log', 'inspection logs');
        $this->crud->denyAccess("create");
        $this->crud->denyAccess("update");
        $this->crud->denyAccess("delete");
        $this->crud->denyAccess("show");
        if($this->isMobile()){
            CRUD::setShowView('inspection-log.show-mobile');
        }else{
            CRUD::setShowView('inspection-log.show');
        }
		$this->crud->entity_name_plural="بازرسی مجدد";
        $this->crud->entity_name="بازرسی مجدد";
        $this->crud->addClause('where', 'requires_second_inspection', '=', 1);
        $this->crud->addClause('whereNotNull', 'second_inspection_date');
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
        CRUD::column('inspection_period')->label("دوره بازرسی");
        CRUD::column('inspection_date_fa')->label("تاریخ بازرسی");
        CRUD::column('requires_second_inspection')->label("نیاز به بازرسی مجدد")->type("switch");
        CRUD::column('second_inspection_date_fa')->label("تاریخ بازرسی مجدد");
        CRUD::column('inspector_signature')->label("امضا بازرس")->type("image")->prefix('storage')->width("200px")->height("200px");

        CRUD::column('legal_expert_signature')->label("امضا کارشناس حقوقی / مدیر دفتر")->type("image")->prefix('storage')->width("200px")->height("200px");
        CRUD::column('office_manager_signature')->label("امضا مدیر دفتر")->type("image")->prefix('storage')->width("200px")->height("200px");
        // if the model has timestamps, add columns for created_at and updated_at
        if ($this->crud->get('show.timestamps') && $this->crud->model->usesTimestamps()) {
            $this->crud->column($this->crud->model->getCreatedAtColumn())->type('datetime');
            $this->crud->column($this->crud->model->getUpdatedAtColumn())->type('datetime');
        }

        // if the model has SoftDeletes, add column for deleted_at
        if ($this->crud->get('show.softDeletes') && in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->crud->model))) {
            $this->crud->column($this->crud->model->getDeletedAtColumn())->type('datetime');
        }

        // remove the columns that usually don't make sense inside the Show operation
        $this->removeColumnsThatDontBelongInsideShowOperation();
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
		CRUD::column('adapt')->label("تطابق اطلاعات")->type("switch");
        CRUD::column('inspection_period')->label("دوره بازرسی");
        CRUD::column('inspection_date_fa')->label("تاریخ بازرسی");
        CRUD::column('requires_second_inspection')->label("نیاز به بازرسی مجدد")->type("switch");
        CRUD::column('second_inspection_date_fa')->label("تاریخ بازرسی مجدد");
        CRUD::column('inspector_signature')->label("امضا بازرس")->escaped(false);
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
}
