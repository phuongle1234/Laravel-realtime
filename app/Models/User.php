<?php

namespace App\Models;

use App\Enums\EStatus;
use App\Enums\ETransfer;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Filters;
use App\Enums\EStripe;
use App\Enums\EUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Stripe\Subscription;
use function PHPUnit\Framework\returnSelf;
use App\Models\Request;

//use Illuminate\Broadcasting\Channel;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable, Filters, HasApiTokens;

    public $prefix;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $fillable = [
        'name',
        'email',
        'role',
        'code',
        'avatar',
        'kana',
        'birthday',
        'sex',
        'tel',
        'address',
        'vimeo_folder_id',
        'email_verified_at',
        'remember_token',
        'university_Code',
        'faculty_code',
        'edu_status',
        'status',
        'last_name',
        'access_token',
        'card_id',
        'introduction',
        'approved_admin',
        'token',
        'password',
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    protected $dates = ['seen_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['avatar_img', 'online', ''];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */



    public function receivesBroadcastNotificationsOn()
    {
        return 'App.Models.User.'.$this->id;
    }
    // school for teach

    // public function getUniversityAttribute()
    // {
    //    return '早稲田大学 経済学部';
    // }

    public function getCurrentPeriodEndAttribute(): string
    {
        //$subscriptionID = $this->stripe()->first();
        if(empty( $this->subscription ))
            return '';

        //$subscription = Subscription::retrieve($subscriptionID->subscription_id ,[]);
        return Carbon::parse($this->subscription->current_period_end)->timezone(env('APP_TIMEZONE'))->format("Y年m月d日 H時 i分");
    }

    //request
    public function getListRequestComplteAttribute()
    {
       return $this->request()->where('status',EStatus::COMPLETE)->with('subject')->orderBy('created_at','DESC')->get();
    }

    public function getRequestCompleteAttribute()
    {
        return $this->orderRequest()->where('status',EStatus::COMPLETE)->get();
    }

    public function getListRequestAttribute()
    {
       return $this->request()->whereNotIn('status',[EStatus::COMPLETE])->with('subject')->orderBy('created_at','DESC')->get();
    }

    public function getRequestAttribute()
    {
       return $this->request()->count() ;
    }

    public function getTicketAttribute()
    {
        if( $this->stripe()->first()->plan_id == EStripe::PREMIUM_PLAN_ID )
        return '<img height="15px" src="'.asset('student/images/unlimit.svg').'" alt="">' ;

        return $this->tickets()->sum('amount');
    }

    public function getRewardAttribute()
    {
        return $this->transfer()->sum('amount');
    }

    public function getListBlockAttribute()
    {
        $_attribute = $this->blockedUser()->get()->toArray();

        if(! empty($_attribute));
            return  collect($_attribute)->map(function($val){ return $val['blocked_user_id']; })->toArray();

        return [];
    }
    // using for teacher

    //likes

    // public function getUniverAttribute(){
    //     return $this->university();
    // }


    public function getPeopleRatingAttribute(){
        return '('.$this->Ratings()->count().'評価)';
    }

    public function getRatingAttribute(){
        return  round( $this->Ratings()->avg('evaluation') , 1 );
    }

    public function getListBlockedByUserAttribute()
    {
        $_attribute = $this->blockedByUser()->get()->toArray();

        if(! empty($_attribute));
            return  collect($_attribute)->map(function($val){ return $val['user_id']; })->toArray();

        return [];
    }

    public function getAvatarImgAttribute()
    {
        if($this->avatar)
          return $this->avatar."?rid=".rand(0,99); //Storage::url($this->avatar);

        return asset('student/images/message/user.svg');
    }

    public function getViaEmailAttribute()
    {
        $attribute = $this->settings()->first();

        if($attribute){
            if( $attribute->notifications_by_email )
            return true;
        }

        return false;

    }

    public function getGenderAttribute()
    {
        switch($this->sex){
            case 'male': return '男性' ; break;
            case 'female': return '女性'; break;
        }
    }


    public function getPercentAttribute()
    {
        // $_attribut = $this->verifyUser()->first();

        // if($_attribut)
        //     return $_attribut->percent_training;

        return $this->percent_training;
    }


    // public function getIntroductionAttribute()
    // {
    //     $_attribut = $this->verifyUser()->first();

    //     if($_attribut)
    //         return $_attribut->introduction;

    //     return null;
    // }

    public function getSubjectArrayAttribute()
    {
        $_result = collect($this->subject->toArray())->map( function($value, $index){
                        return $value['id'];
                    });

        return $_result->toArray();
    }

    public function getSubjectTextAttribute()
    {
        $_result = collect($this->subject->toArray())->map(function($value, $index){ return str_replace('<br />','',$value['name']); });
        return $_result->implode('・');
    }

    public function getOnlineAttribute()
    {
        $_now =  Carbon::now();
        $_seen_at = Carbon::parse( $this->seen_at );

         if( ! ( $_now->diffInDays($_seen_at) ) )
            return true;

        return false;
    }

    public function getOfflineTextAttribute()
    {
        $_now = Carbon::now();
        $_seen_at = Carbon::parse($this->seen_at);
        $_total_offline_days = $_now->diffInDays($_seen_at);
        $offline_text = "最終ログイン<br>一週間以上前";

        if($_total_offline_days < 1)
            $offline_text = "最終ログイン<br>24時間以内";

        if(in_array($_total_offline_days,[1,2,3,4,5,6,7]))
            $offline_text = "最終ログイン<br>{$_total_offline_days}日前";

        return $offline_text;
    }

    // using admin

    public function getApproveStatusAttribute()
    {
        $_text = '';
        $_class = '';

        switch($this->approved_admin){
            case 1 :  $_text = '承認済み'; $_class = 'cl-blue'; break;
            case 2 : $_text = '再提出要請'; $_class = 'cl-green'; break;
            default: $_text = '未確認'; $_class = 'cl-red'; break;
        }

        return (object)[ '_text' => $_text, '_class' => $_class ];
    }

    public function getTrainStatusAttribute()
    {

        if($this->percent_training == 100)
          return (object)[ '_text' => '視聴済み', '_class' => 'cl-blue' ];

          return (object)[ '_text' => '未完了', '_class' => 'cl-red' ];
    }

    // build relationship
    public function views()
    {
        return $this->hasMany(UserView::class,'user_id','id');
    }

    public function watches()
    {
        return $this->hasMany(UserWatch::class,'user_id','id');
    }

    public function university()
    {
        return $this->hasOne(SchoolMaster::class, 'university_Code','university_Code');
    }

    public function faculty($_faculty_code)
    {
        return $this->hasOne(SchoolMaster::class, 'university_Code','university_Code')->where('faculty_code',$_faculty_code);
    }

    // get like for teacher
    public function liked()
    {
        return $this->hasManyThrough( UserLike::class, Video::class, 'owner_id', 'video_id', 'id', 'id' )->where( 'status', EStatus::ACTIVE);
    }

    public function likes()
    {
        return $this->hasMany(UserLike::class,'user_id','id');
    }

    public function settings()
    {
        return $this->hasOne(Setting::class,'user_id','id');
    }

    public function userSubject()
    {
        return $this->hasMany(UserSubject::class,'user_id','id');
    }

    public function subject()
    {
        return $this->hasManyThrough(Subject::class,UserSubject::class,'user_id','id','id','subject_id');
    }

    public function tickets()
    {
        return $this->hasMany(UserTicket::class,'user_id','id')->where('status',EStatus::ACTIVE);
    }

    public function ratings()
    {
        return $this->hasMany(UserRating::class,'user_id','id');
    }

    // list user blocked
    public function blockedUser()
    {
        return $this->hasMany(UserBlock::class,'user_id','id');
    }

     // list user blocked
     public function blockedByUser()
     {
         return $this->hasMany(UserBlock::class,'blocked_user_id','id');
     }

     public function bankAccount()
     {
         return $this->hasOne(BankAccount::class,'user_id','id');
     }
    // request from student

    public function suggestRequest()
    {

        return Request::join('user_subjects', 'requests.subject_id', '=' ,'user_subjects.subject_id')
               ->where(['user_subjects.user_id' => $this->id, 'requests.status' => EStatus::PENDING, 'is_direct' => 0, 'is_displayed' => EStatus::IS_DISPLAYED])
               ->whereNotIn('requests.user_id', $this->list_blocked_by_user )
               ->select(
                            'requests.id as id',
                            'requests.content',
                            'requests.is_urgent',
                            'requests.user_id',
                            'requests.title',
                            'requests.subject_id',
                            'requests.file_path',
                            'requests.deadline'
                        )
              ->orderBy('requests.created_at','DESC');
    }

    public function nominationRequests()
    {
        return $this->hasMany(Request::class,'user_receive_id','id')->where('is_direct',1);
    }

    public function request()
    {
        return $this->hasMany(Request::class,'user_id','id');
    }

    public function orderRequest()
    {
        return $this->hasMany(Request::class,'user_receive_id','id')->orderBy('created_at','DESC');
    }

    // list video
    public function videos()
    {
        return $this->hasMany(Video::class,'owner_id','id');
    }

    // list contact
    public function contact()
    {
        return $this->hasMany(Contact::class,'user_id','id')
                        ->whereNull('seen_at')
                        ->whereHas( 'request', function( $query ){ $query->whereNull('deleted_at');   } )
                        ->where('status',EStatus::ACTIVE);
    }

     // verify_users
    //  public function verifyUser()
    //  {
    //      return $this->hasOne(VerifyUser::class,'user_id','id');
    //  }

     // user stripe
     public function stripe()
     {
         return $this->hasOne(UserStripe::class,'user_id','id');
     }

     // user transfer
    public function transfer()
    {
        return $this->hasMany(UserTransfer::class,'user_id','id')->latest();
    }

    // user compensation
    public function compensation()
    {
        return $this->hasMany(UserCompensations::class,'user_id','id');
    }

    // user statistical
    public function statistical()
    {
        return $this->hasMany(Statistical::class,'user_id','id');
    }

}
