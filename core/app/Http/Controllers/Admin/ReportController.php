<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\GeneralQuestion;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
class ReportController extends Controller
{
	public function report(){
		$provinces=\App\Models\Province::all();
		$ids=[45,28,41,32,31,30,23,48,1,18];
		$questions=\App\Models\GeneralQuestion::whereIn("id",$ids)->with("category")->get();
		return view(backpack_view("report"),compact('provinces','questions'));
	}
	public function post_report(Request $request){
		$data=$request->validate([
			'general_question_id'=>'required',
			'province_id'=>'nullable',
			'office_code'=>'nullable'
		]);
		$chartData=[];
		$labels=[];
		if(isset($data['province_id']) && $data['province_id']){
			$labels=[Province::find($data['province_id'])->name];
				$countQuery=\App\Models\InspectionLog::whereHas("office",function($q)use($data){
					return $q->where("province_id",$data['province_id']);
				})->whereHas("checklists.questions",function($q)use($data){
					if(in_array($data["general_question_id"],[1,18,28,32,41,45])){
						return $q->where("general_question_id",$data["general_question_id"])->where("rating",0);
					}else{
						return $q->where("general_question_id",$data["general_question_id"])->where("rating",1);
					}
				});
				if(request('office_code')){
					$countQuery=$countQuery->where("office_code",request('office_code'));
				}
				$count=$countQuery->count();
				$chartData[]=$count;
		}else{
			$provinces=Province::all();
			$labels=$provinces->pluck('name')->toArray();
			foreach($provinces as $province){
				$countQuery=\App\Models\InspectionLog::whereHas("office",function($q)use($province){
					return $q->where("province_id",$province->id);
				})->whereHas("checklists.questions",function($q)use($data){
					return $q->where("general_question_id",$data["general_question_id"])->where("rating",1);
				});
				if(request('office_code')){
					$countQuery=$countQuery->where("office_code",request('office_code'));
				}
				$count=$countQuery->count();
				$chartData[]=$count;
			}

		}
		return [
			'ok'=>true,
			'data'=>[
				'data'=>$chartData,
				'labels'=>$labels
			]
		];
		
	}
	public function excelExport(Request $request){
		$data=$request->validate([
			'general_question_id'=>'required',
			'province_id'=>'nullable',
			'office_code'=>'nullable'
		]);
		$chartData=[];
		$labels=[];
		$rows=[];
		$question=GeneralQuestion::find($data["general_question_id"]);
		if(isset($data['province_id']) && $data['province_id']){
// 			$labels=[Province::find($data['province_id'])->name];
			$province=Province::find($data['province_id']);
			$labels=[
				$question->short_description,
				'استان',
				'تعداد تخلف دفتر',
				'نام مدیر دفتر',
				'کد دفتر'
			];
			
				$countQuery=\App\Models\InspectionLog::whereHas("office",function($q)use($data){
					return $q->where("province_id",$data['province_id']);
				})->whereHas("checklists.questions",function($q)use($data){
					if(in_array($data["general_question_id"],[1,18,28,32,41,45])){
						return $q->where("general_question_id",$data["general_question_id"])->where("rating",0);
					}else{
						return $q->where("general_question_id",$data["general_question_id"])->where("rating",1);
					}
				});
				if(request('office_code')){
					$countQuery=$countQuery->where("office_code",request('office_code'));
				}
				// $count=$countQuery->count();
				$counts=$countQuery->groupBy('office_code')->with(['office'])->select('office_code', \DB::raw('count(*) as total'))->get();
				$total_count=$counts->sum('total');
				foreach($counts as $key=>$item){
					$rows[]=[
						$key == 0 ? 'تعداد تخلف کل استان '.$province->name.": ".$total_count:"-",
						$province->name,
						$item->total,
						$item->office->first_name." ".$item->office->last_name,
						strval($item->office_code)
					];
				}
				// $chartData[]=$count;
		}else{
			$provinces=Province::all();
			
			$labels=[
				$question->short_description,
				'استان',
				'تعداد تخلف دفتر',
				'نام مدیر دفتر',
				'کد دفتر'
			];
			$rows=[];
			foreach($provinces as $province){
				$countQuery=\App\Models\InspectionLog::whereHas("office",function($q)use($province){
					return $q->where("province_id",$province->id);
				})->whereHas("checklists.questions",function($q)use($data){
					return $q->where("general_question_id",$data["general_question_id"])->where("rating",1);
				});
				if(request('office_code')){
					$countQuery=$countQuery->where("office_code",request('office_code'));
				}
				$counts=$countQuery->groupBy('office_code')->with(['office'])->select('office_code', \DB::raw('count(*) as total'))->get();
				$total_count=$counts->sum('total');
				foreach($counts as $key=>$item){
					$rows[]=[
						$key == 0 ? 'تعداد تخلف کل استان '.$province->name.": ".$total_count:"-",
						$province->name,
						$item->total,
						$item->office->first_name." ".$item->office->last_name,
						strval($item->office_code)
					];
				}
				
			}

		}
		return Excel::download(new ReportExport($labels,$rows), 'users.xlsx');
		
	}
}
