<?php

namespace App\Enums;

class EStatus
{
    const ACTIVE = 'active';

    const PENDING = 'pending';

    const DENIED = 'denied';

    const PASS = 'pass';

    const DELAYED = 'delayed';

    const COMPLETE = 'complete';

    const APPROVED = 'approved';

    const DELETE = 'deleted';

    const IS_LATE_DEADLINE = 1;

    const NOT_SCHEDULE_REQUEST = [self::DELAYED,self::COMPLETE];

    const ACCEPTED = 'accept';

    const IS_DISPLAYED = 1;
    const IS_DISPLAYED_TEXT = "公開";

    const IS_NOT_DISPLAYED = 0;
    const IS_NOT_DISPLAYED_TEXT = "非公開";


    const EFFECTIVENESS = '有効';
    const INVALID = '無効';

    const REQUEST_STATUS_ARRAY = [
        self::PENDING => '受注待ち',
        self::ACCEPTED => '作業中',
        self::DELAYED => '遅延',
        self::APPROVED => '動画アップロード済',
        self::PASS => '動画承認済み',
        self::DENIED => '再提出要求',
        self::COMPLETE => '完了',
    ];

    public static function getTextByStatus($status)
    {
        foreach(self::REQUEST_STATUS_ARRAY as $k => $v){
            if($status === $k) return $v;
        }
    }

    public static function renderPublicStatus($value)
    {
        switch($value){
            case self::IS_DISPLAYED:
                return self::IS_DISPLAYED_TEXT;
            default:
                return self::IS_NOT_DISPLAYED_TEXT;
        }
    }
}
