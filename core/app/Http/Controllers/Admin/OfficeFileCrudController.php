<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OfficeFileRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Models\OfficeFile;
use App\Models\InspectionLog;
use Detection\MobileDetect;
/**
 * Class OfficeFileCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OfficeFileCrudController extends CrudController
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

        CRUD::setModel(\App\Models\OfficeFile::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/office-file');
        CRUD::setEntityNameStrings('office file', 'office files');
        $this->crud->denyAccess("create");
        $this->crud->denyAccess("update");
        $this->crud->denyAccess("delete");
// 		if(optional(auth('backpack')->user())->user_type == 3){
// 			$this->crud->denyAccess("show");
// 		}
        $this->crud->entity_name_plural="دفاتر";
        $this->crud->entity_name="دفتر";
        $detect = new MobileDetect();
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        $detect->setUserAgent($useragent);

        if($detect->isMobile() || $detect->isTablet() || $detect->isiPad() || $detect->isNexusTablet() || $detect->isSamsungTablet()){
            CRUD::setShowView('office-file.submit-inspection-mobile');
        }else{
            CRUD::setShowView('office-file.submit-inspection');
        }

        // CRUD::button('submit_inspection')->stack('line')->view('crud::buttons.quick')->meta([
        //     'access' => true,
        //     'label' => 'ثبت سوابق بازرسی',
        //     'icon' => 'la la-briefcase',
        //     'wrapper' => [
        //         'element' => 'a',
        //         'href' => url('admin/office-file/submit-inspection'),
        //         'target' => '_blank',
        //         'title' => '',
        //     ]
        // ]);
        // CRUD::button('inspection_history')->stack('line')->view('crud::buttons.quick')->meta([
        //     'access' => true,
        //     'label' => 'لیست سوابق بازرسی',
        //     'icon' => 'la la-list',
        //     'wrapper' => [
        //         'element' => 'a',
        //         'href' => url('admin/office-file/history'),
        //         'target' => '_blank',
        //         'title' => '',
        //     ]
        // ]);
        CRUD::addButtonFromModelFunction('line', 'inspection_logs', 'inspectionLogs', 'beginning');
        CRUD::addButtonFromModelFunction('line', 'old_inspections', 'oldInspections', 'beginning');
        $allProvinces=[];
        $provinces=auth('backpack')->user()->provinces;
		if(auth('backpack')->user()->user_type == 3){
			$allProvinces=Province::all()->keyBy('id')->pluck('name', 'id')->toArray();
		}
		else if($provinces && is_array($provinces) && in_array(0,$provinces)){
			$allProvinces=Province::all()->keyBy('id')->pluck('name', 'id')->toArray();
		}else{
				if($provinces){
					$allProvinces=Province::whereIn("id",$provinces)->get()->keyBy('id')->pluck('name', 'id')->toArray();
					CRUD::addClause('whereIn', 'province_id', $provinces);
				}
		}


        CRUD::filter('province_id')
            ->label('انتخاب استان')
            ->type('select2_multiple')
            ->values($allProvinces)
            ->whenActive(function($values) {
            CRUD::addClause('whereIn', 'province_id', json_decode($values,true));
            });
			            CRUD::filter('city_id')
            ->label('انتخاب شهر')
            ->type('select2_city_multiple')
            ->whenActive(function($values) {
            CRUD::addClause('whereIn', 'city_id', json_decode($values,true));
            });

        // Add search functionality based on office_code
        CRUD::filter('office_code_search')
            ->label('جستجو بر اساس کد دفتر')
            ->type('text')
            ->whenActive(function($value) {
                CRUD::addClause('where', 'office_code', 'LIKE', "%$value%");
            });

        // Handle violation status filtering from office-reports page
        if (request()->has('violation_status')) {
            $violationStatus = request()->get('violation_status');

            switch ($violationStatus) {
                case 'with_violations':
                    // Get office codes that have violations (adapt = 0)
                    $officeCodesWithViolations = \App\Models\InspectionLog::where('adapt', 0)
                        ->distinct('office_code')
                        ->pluck('office_code')
                        ->toArray();
                    CRUD::addClause('whereIn', 'office_code', $officeCodesWithViolations);
                    break;

                case 'without_violations':
                    // Get office codes that have no violations (adapt = 1)
                    $officeCodesWithoutViolations = \App\Models\InspectionLog::where('adapt', 1)
                        ->distinct('office_code')
                        ->pluck('office_code')
                        ->toArray();
                    CRUD::addClause('whereIn', 'office_code', $officeCodesWithoutViolations);
                    break;

                case 'requiring_defect_removal':
                    // Get office codes that require defect removal
                    $officeCodesRequiringDefectRemoval = \App\Models\InspectionLog::where('adapt', 0)
                        ->where('requires_second_inspection', 1)
                        ->distinct('office_code')
                        ->pluck('office_code')
                        ->toArray();
                    CRUD::addClause('whereIn', 'office_code', $officeCodesRequiringDefectRemoval);
                    break;

                case 'sent_to_board':
                    // Get office codes sent to board (you may need to adjust this based on your business logic)
                    $officeCodesSentToBoard = []; // This would need to be implemented based on your specific logic
                    CRUD::addClause('whereIn', 'office_code', $officeCodesSentToBoard);
                    break;
            }
        }
    //         CRUD::filter('description')

    // ->type('text')
    // ->whenActive(function($value) {
    //   // CRUD::addClause('where', 'description', 'LIKE', "%$value%");
    // });
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
        $provinces=auth('backpack')->user()->provinces;

        if($provinces && in_array(0,$provinces)){
			//nothing
		}else if($provinces){
			CRUD::addClause('whereIn', 'province_id', $provinces);
		}
        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
        // CRUD::column('personel_image')->type('image')->disk('public')->label("عکس پرسنلی");
        CRUD::column('office_code')->label("کد دفتر");
        CRUD::column('first_name')->label("نام");
        CRUD::column('last_name')->label("نام خانوادگی");
        CRUD::column('computed_gender')->label("جنسیت");
CRUD::column('province.name')->label("استان");
        CRUD::column('city.name')->label("شهر");
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(OfficeFileRequest::class);
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
    public function inspectionNoAdapt(Request $request,$id){
        $officeFile=OfficeFile::find($id);
        $officeFile->no_adapt=1;
        $officeFile->save();
		 $officeFile=OfficeFile::find($id);
        $data=[
			'adapt'=>0,
            'office_code'=>$officeFile->office_code,
            'inspection_period'=>$request->inspection_period,
            'lat'=>$request->lat,
            'lng'=>$request->lng,
            'inspection_date'=>$request->inspection_date
        ];
        $inspectionLog=InspectionLog::create($data);

        //notify admin
        return ['ok'=>true];
    }
    public function saveBase64Image($base64,$path){
        $image = $base64;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = \Str::random(10).'.'.'png';
        $path=$path."/".$imageName;
        \Storage::disk("public")->put($path, base64_decode($image));
        return $path;
    }
    public function submitInspection(Request $request,$id){
        $body=$request->all();

        $officeFile=OfficeFile::find($id);
        $data=[
			'adapt'=>1,
            'office_code'=>$officeFile->office_code,
            'inspection_type'=>$request->inspection_type,
			'inspection_season'=>$request->inspection_season,
            'lat'=>$request->lat,
            'lng'=>$request->lng,
            'inspection_date'=>$request->inspection_date,
            'obligations'=>$request->obligations,
            'second_inspection_summary'=>$request->second_inspection_summary,
            'requires_second_inspection'=>$request->requires_second_inspection,
            'second_inspection_date'=>$request->second_inspection_date,
            'unauthorized_users'=>$request->unauthorized_users,
			'user_id'=>$request->user_id,
			'cctv_username'=>$request->cctv_username,
			'cctv_port'=>$request->cctv_port,
			'cctv_ip'=>$request->cctv_ip,
			'cctv_password'=>$request->cctv_password,
            // 'inspector_signature'=>$request->inspector_signature,
            // 'office_manager_signature'=>$request->office_manager_signature,
            // 'legal_expert_signature'=>$request->legal_expert_signature
        ];
        $inspectionLog=InspectionLog::create($data);


        foreach($body['category'] as $category_id=>$checklist){
            $checklistModel=$inspectionLog->checklists()->create([
                'question_category_id'=>$category_id,
                //'adapt_percent'=>$checklist['adapt_percent']
            ]);
            foreach($checklist['questions'] as $question_id=>$question){
                $ques=$checklistModel->questions()->create([
                    'general_question_id'=>$question_id,
                    'rating'=>$question['rating'],
                    'description'=>$question['description']
                ]);

            }
        }

        if(isset($body['employees'])){
            $body['employees']=array_map(function($item)use($inspectionLog){
                $item['inspection_log_id']=$inspectionLog->id;
                return $item;
            },$body['employees']);
        }else{
            $body['employees']=[];
        }

        if($request->inspector_signature){
            $file=$this->saveBase64Image($request->inspector_signature,'/inspection_logs/'.$inspectionLog->id."/signatures");
            $inspectionLog->inspector_signature=$file;
            $inspectionLog->save();
        }
        if($request->office_manager_signature){
            $file=$this->saveBase64Image($request->office_manager_signature,'/inspection_logs/'.$inspectionLog->id."/signatures");
            $inspectionLog->office_manager_signature=$file;
            $inspectionLog->save();
        }
        if($request->legal_expert_signature){
            $file=$this->saveBase64Image($request->legal_expert_signature,'/inspection_logs/'.$inspectionLog->id."/signatures");
            $inspectionLog->legal_expert_signature=$file;
            $inspectionLog->save();
        }
        if($request->hasFile('attachments')){
            $attachments=[];
            foreach($request->attachments as $attachment){
                $file=$attachment->store('/inspection_logs/'.$inspectionLog->id."/attachments",'public');
                $attachments[]=$file;
            }
            $inspectionLog->attachments=$attachments;
            $inspectionLog->save();
        }
        if(isset($body['employees']) && count($body['employees'])){
            $inspectionLog->employees()->insert($body['employees']);
        }

        return redirect()->route('inspection-log.index',['office_code'=>$officeFile->office_code])->with("success","بازرسی با موفقیت انجام شد");

    }
}
