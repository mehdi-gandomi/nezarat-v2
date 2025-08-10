<?php

namespace Database\Seeders;

use App\Models\GeneralQuestion;
use App\Models\GeneralQuestionCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneralQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GeneralQuestionCategory::insert([
            [
                'name'=>'شاخص مدیریت'
            ],
            [
                'name'=>'شاخص منابع انسانی'
            ],
            [
                'name'=>'شاخص قانون مداری'
            ],
            [
                'name'=>'شاخص تجهیزات و امکانات'
            ],
            [
                'name'=>'شاخص امنیت'
            ],
            [
                'name'=>'شاخص گزارش های دوره ای'
            ]
        ]);
        GeneralQuestion::insert([
            [
                "question"=>"آیا مدیر دفتر به صورت تمام وقت و در ساعات اداری در دفتر حضور دارد؟ 	",
                "general_question_category_id"=>1
            ],
            [
                "question"=>"آیا رفتار مدیر دفتر با مراجعین طبق شئونات و بدون تبعیض می باشد؟ 	",
                "general_question_category_id"=>1
            ],
            [
                "question"=>"آیا آراستگی اداری و وضعیت ظاهری مدیر دفتر مورد تایید است؟ ",
                "general_question_category_id"=>1
            ],
            [
                "question"=>"آیا اطلاعات و اسناد مربوط در موعد مقرر در سامانه ثبت و ارسال می شوند؟ 	",
                "general_question_category_id"=>1
            ],
            [
                "question"=>"آیا همکاری های لازم توسط مدیر دفتر با بازرسی صورت پذیرفته است؟ ",
                "general_question_category_id"=>1
            ],
            [
                "question"=>"آیا دعاوی ثبت شده صرفا توسط مدیر دفتر و یا نماینده وی ارسال می گردد؟",
                "general_question_category_id"=>1
            ],
            ["question"=>"آیا کارشناس حقوقی دفتر به معاونت خدمات معرفی و در دفتر حضور دارد ؟","general_question_category_id"=>2],
            ["question"=>"آیا کفیل به معاونت خدمات  معرفی شده است؟","general_question_category_id"=>2],
            ["question"=>"آیا کارکنان مطابق اسامی اعلام شده در دفتر حضور دارند؟","general_question_category_id"=>2],
            ["question"=>"آیا عملکرد و دانش و اطلاعات کارکنان مورد تایید است؟","general_question_category_id"=>2],
            ["question"=>"آیا آراستگی اداری و وضعیت ظاهری کارکنان مورد تایید است؟","general_question_category_id"=>2],
            ["question"=>"آیا رفتار کارکنان دفتر با مراجعین طبق شئونات و بدون تبعیض می باشد؟","general_question_category_id"=>2],
            ["question"=>"آیا کاربر غیر مجاز در دفتر فعالیت دارد؟","general_question_category_id"=>2],
            ["question"=>"آیا همکاری های لازم توسط کارکنان با بازرس صورت پذیرفته است؟","general_question_category_id"=>2],
            
            ["question"=>"آیا مجوزتاسیس دفتردر محل قابل رویت مراجعین نصب شده است؟","general_question_category_id"=>3],
            ["question"=>"آیا برنامه ساعت کار دفتر در محل قابل رویت مراجعین نصب شده است؟","general_question_category_id"=>3],
            ["question"=>"آیا سامانه های تنظیم در دفتر در خصوص نوبت دهی رعایت می گردد؟","general_question_category_id"=>3],
            ["question"=>"آیا بخش نامه های اعلامی در محل قابل رویت مراجعین نصب شده است؟","general_question_category_id"=>3],
            ["question"=>"آیا تعداد کارت خوان های موجود در دفتر مطابق استانداردهای معاونت خدمات می باشد؟","general_question_category_id"=>3],
            ["question"=>"آیا تابلوی تعرفه خدمات در محل قابل رویت مراجعین نصب شده است؟","general_question_category_id"=>3],
            ["question"=>"آیا وجوه دریافتی مطابق تعرفه و صرفا ازطریق کارتخوان اعلام شده دریافت می گردد؟","general_question_category_id"=>3],
            ["question"=>"آیا قبض رسید وجوه دریافتی در اختیار مراجعین قرار می گیرد؟","general_question_category_id"=>3],
            ["question"=>"آیا مهر دفترجهت ممهورنمودن اسناد اعلامی در دفتر موجود است؟","general_question_category_id"=>3],
            ["question"=>"آیا احراز هویت به درستی توسط دفترصورت می پذیرد؟","general_question_category_id"=>3],
            ["question"=>"آیا ارزش منطقه ای ملک به درستی محاسبه می گردد؟","general_question_category_id"=>3],
            ["question"=>"آیا دفتر صرفا مدارک اصل و یا برابراصل را اسکن می کند ؟","general_question_category_id"=>3],
            ["question"=>"آیا رونوشت در اختیارکسانی که قانونا حق دریافت دارند قرار می گیرد؟","general_question_category_id"=>3],
            ["question"=>"آیا صندوق شکایات معاونت خدمات موجود و در محل مناسب نصب گردیده است؟","general_question_category_id"=>3],
            ["question"=>"آیا چک لیست تایید اطلاعات در اختیار ارباب رجوع قرارمی گیرد؟","general_question_category_id"=>3],
            ["question"=>"آیا ثبت دعوی صرفا در کانتر تایید شده صورت می پذیرد؟","general_question_category_id"=>3],
            ["question"=>"آیا اطلاعات صحیح و کامل افراد در سامانه ثبت می گردد؟","general_question_category_id"=>3],
            ["question"=>"آیا تابلوی سردردفترطبق الگوی تعیین شده نصب گردیده است؟","general_question_category_id"=>3],
            ["question"=>"آیا ترتیب نوبت دهی برای ارباب رجوع توسط دفتررعایت می گردد؟","general_question_category_id"=>3],
            ["question"=>"آیا تمامی خدمات ارایه شده توسط معاونت خدمات در دفتر اجراء می گردد؟","general_question_category_id"=>3],
            ["question"=>"آیا اوراق ابرازی از سوی متقاضی کنترل و پس از تایید صحت آن پیوست می گردد؟","general_question_category_id"=>3],
            ["question"=>"آیا در دعاوی که دو وکیل یا بیشترثبت میشود، امضاء آنها در سیستم اخذ میگردد؟","general_question_category_id"=>3],
            ["question"=>"آیا دفتراقدام به پذیرش دعوی و ثبت آن در زمان دیگربدون عذرموجه مینماید؟","general_question_category_id"=>3],
            ["question"=>"آیا چیدمان دفترطبق الگوی اعلام شده در نظر گرفته شده است؟","general_question_category_id"=>4],
            ["question"=>"آیا صندلی کاربر به تعداد لازم و مطابق الگوی اعلام شده می باشد؟","general_question_category_id"=>4],
            ["question"=>"آیا صندلی ارباب رجوع به تعداد لازم و مطابق الگوی اعلام شده می باشد؟","general_question_category_id"=>4],
            ["question"=>"آیا حداقل تعداد صندلی انتظاروجود دارد؟(10عدد)","general_question_category_id"=>4],
            ["question"=>"آیا مانیتور اطلاع رسانی طبق الگوی اعلام شده و فعال می باشد؟","general_question_category_id"=>4],
            ["question"=>"آیا خدمات پذیرایی ضروری (نظیرآبسردکن) وجود دارد؟","general_question_category_id"=>4],
            ["question"=>"آیا تابلواطلاع رسانی طبق الگو اعلام شده و در محل مناسب نصب شده است؟","general_question_category_id"=>4],
            ["question"=>"آیا دوربین مداربسته طبق مشخصات اعلام شده و فعال می باشد؟","general_question_category_id"=>4],
            ["question"=>"آیا کپسول اطفا حریق سالم و در دفتر وجود دارد؟","general_question_category_id"=>4],
            ["question"=>"آیا جایگاه مناسب برای تابلو های ضروری در نظر گرفته شده است؟","general_question_category_id"=>4],
            ["question"=>"آیا دفتر از لحاظ پاکیزگی ظاهری وضعیت مطلوبی دارد؟","general_question_category_id"=>4],
            ["question"=>"آیا دفتر دارای نور و تهویه مناسب می باشد؟","general_question_category_id"=>4],
            ["question"=>"آیا تاسیسات سرمایشی و گرمایشی مناسب وجود دارد؟","general_question_category_id"=>4],
            ["question"=>"آیا وضعیت آسانسوردفاتری که در طبقه دوم یا بالاتر قراردارند فعال می باشد؟","general_question_category_id"=>4],
            ["question"=>"آیا خدمات رفاهی (سرویس بهداشتی) دفتردراختیارارباب رجوعان قرار میگیرد؟","general_question_category_id"=>4],
            ["question"=>"آیا نکات امنیتی در خصوص عدم افشای اطلاعات و عدم دسترسی به اسناد موجود برای افراد غیرمسئول رعایت می شود؟","general_question_category_id"=>5],
            ["question"=>"آیا نکات امنیتی درحفاظت از اطلاعات سامانه خدمات وراه اندازی شبکه (آنتی ویروس) رعایت می شود؟","general_question_category_id"=>5],
            ["question"=>"امتیازدریافتی دفتردرخصوص میزان برگشتی ها در چه سطحی قراردارد؟","general_question_category_id"=>6],
            ["question"=>"امتیازدریافتی دفتردر خصوص تعداد شکایات در چه سطحی قراردارد؟","general_question_category_id"=>6]
        ]);
    }
}
