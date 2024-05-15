<?php

namespace App\Enums;

class EInquiry
{
    const STUDENT_INQUIRY_OPTIONS_ARRAY = [
        'about_service' => 'サービスに関するお問い合わせ',
        'about_ticket' => 'チケットについて',
        'request_cancel' => 'リクエストキャンセル',
        'other' => 'その他',
        'cancellation_the_plan' => '退会について',
    ];

    const TEACHER_INQUIRY_OPTIONS_ARRAY = [
        'about_service' => 'サービスに関するお問い合わせ',
        'about_ticket' => 'チケットについて',
        'request_cancel' => 'リクエストキャンセル',
        'other' => 'その他',
    ];

    public static function getTextByOptions($value)
    {
        foreach(self::STUDENT_INQUIRY_OPTIONS_ARRAY as $k => $v){
            if($k === $value) return $v;
        }
    }

}
