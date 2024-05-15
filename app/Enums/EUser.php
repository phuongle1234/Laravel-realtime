<?php


namespace App\Enums;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use DB;

class EUser {
    // define status
    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_DELETED = 'deleted';

    // define tickets action
    const CREATE_SUBSCRIPTION_ACTION = 'create_subscription';
    const BUY_TICKET_ACTION = 'buy_ticket';
    const USE_TICKET_ACTION = 'use_ticket';
    const REFUND_TICKET = 'refund_ticket';

    // define roles
    const ADMIN = 'admin';
    const TEACHER = 'teacher';
    const STUDENT = 'student';

    // deadline for request

    const ACCEPT_REQUEST = 2;

    const TEACHER_UPLOAD = 3;

    const DENIED_REQUEST = 2;

    const ADMIN_APPROVED = 2;

    const STUDENT_COMFROM = 2;

    const TIME_URGENT = 1;

    // const ACCEPT_REQUEST = 10;

    // const TEACHER_UPLOAD = 20;

    // const DENIED_REQUEST = 10;

    // const ADMIN_APPROVED = 10;

    // const STUDENT_COMFROM = 10;

    // const TIME_URGENT = 5;



    public static function checkUrgent ( $_date_time = null )
    {
        $_now = Carbon::now();

        //$_carbon = Carbon::createFromFormat('Y-m-d H:i:s',$_date_time);
        $_carbon = Carbon::parse( $_date_time );

        // return ( $_now->diffInMinutes($_carbon) >= self::TIME_URGENT ) ;
        return ( $_now->diffInDays($_carbon) >= self::TIME_URGENT );
    }

    public static function getDeadLine ( string $status, $_start = null )
    {

        $_date_time = Carbon::now();

        switch($status){
            case EStatus::PENDING:
                $_date_time->addDay( self::ACCEPT_REQUEST );
                // $_date_time->addMinutes( self::ACCEPT_REQUEST );
                break;
            case EStatus::ACCEPTED:
                $_date_time->addDay( self::TEACHER_UPLOAD );
                // $_date_time->addMinutes( self::TEACHER_UPLOAD );
                break;
            case EStatus::APPROVED:
                $_date_time->addDay( self::ADMIN_APPROVED );
                // $_date_time->addMinutes( self::ADMIN_APPROVED );
                break;
            case EStatus::DENIED:
                $_date_time = Carbon::parse( $_start );
                $_date_time->addDay( EUser::ADMIN_APPROVED );
                // $_date_time->addMinutes( self::ADMIN_APPROVED );
                break;
            case EStatus::PASS:
                $_date_time->addDay( self::STUDENT_COMFROM );
                // $_date_time->addMinutes( self::STUDENT_COMFROM );
                break;
        }

        return $_date_time->format('Y-m-d H:i');
    }

    public static function getCodeUser( string $role ):string
    {

        $_max = DB::table('users')->where(['role' => $role])->count('id')+1;
        $_max = str_pad($_max, 4, 0, STR_PAD_LEFT ).date('is');

        switch($role){
            case self::ADMIN : $_code = 'A-'.$_max; break;
            case self::TEACHER : $_code = 'T-'.$_max; break;
            case self::STUDENT : $_code = 'S-'.$_max; break;
        }

        return $_code;
    }

      // text gender

    const GENDER = [
        'male' => '男性',
        'female' => '女性',
        'others' => 'その他'
    ];

    const EDU_STATUS = [
        '在学',
        '卒業',
        '院生',
        '院卒'
    ];
}
