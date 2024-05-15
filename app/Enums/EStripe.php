<?php

namespace App\Enums;

class EStripe
{
    const TICKETS_PRICE_UNIT = 440;

    // const DEFINE = [ 'FREE', 'STANDARD', 'PREMIUM' ];

    const FREE_PLAN_NAME = '無料会員';
    const FREE_PLAN_ID = 1;
    const FREE_PLAN_BONUS_TICKET = 1;

    const STANDARD_PLAN_NAME = 'チケット購入プラン';
    const STANDARD_PLAN_ID = 2;
    const STANDARD_PLAN_PRICE = 980;

    const PREMIUM_PLAN_NAME = 'リクエストし放題プラン';
    const PREMIUM_PLAN_ID = 3;
    const PREMIUM_PLAN_PRICE = 2500;


    const STANDARD_MEMBER_PRICE_ID =
        [
            'tickets' => 0,
            'demo_stripe_price_id' => 'price_1LPGzDFZ0sdKqkqcjIT5mNtO',
            'release_stripe_price_id' => 'price_1M8wv9FMSCEEkvCeRyeS9Nit',
            'plan_id' => self::STANDARD_PLAN_ID
        ];

    const PREMIUM_MEMBER_PRICE_ID =
        [
            'tickets' => 9999,
            'demo_stripe_price_id' => 'price_1LPGzXFZ0sdKqkqckZDGE4c1',
            'release_stripe_price_id' => 'price_1M8wvPFMSCEEkvCekTJP2xo9',
            'plan_id' => self::PREMIUM_PLAN_ID
        ];

    public static function getAllPlan()
    {
        return [
          ['id' => self::FREE_PLAN_ID, 'name' => self::FREE_PLAN_NAME ],
          ['id' => self::STANDARD_PLAN_ID, 'name' => self::STANDARD_PLAN_NAME ],
          ['id' => self::PREMIUM_PLAN_ID, 'name' => self::PREMIUM_PLAN_NAME ]
        ];

    }

    public static function getDescriptByPlan($price_id)
    {

        switch ($price_id){
            case self::STANDARD_PLAN_ID :
                return "必要なときにリクエストチケット（１枚４４０円）を購入し、解説授業をリクエストできるプランです。
                        edutosssのすべての機能が利用できます。";
            case self::PREMIUM_PLAN_ID :
                return "何度解説授業をリクエストをしても定額で利用できるプランです。
                        edutosssのすべての機能が利用でき、月に４回以上のリクエストで
                        お得になります。";
            default:
                return "解説動画がすべて視聴できるプラン。今ならリクエストチケット1枚がついてきます。";
        }
    }

    public static function getTicketsByPlan($price_id,$environment = 'demo')
    {
        switch ($price_id){
            case self::STANDARD_MEMBER_PRICE_ID[$environment . '_stripe_price_id']:
                return self::STANDARD_MEMBER_PRICE_ID['tickets'];
            case self::PREMIUM_MEMBER_PRICE_ID[$environment . '_stripe_price_id']:
                return self::PREMIUM_MEMBER_PRICE_ID['tickets'];
        }
    }

    public static function getPlanByPrice($price_id,$environment = 'demo')
    {
        switch ($price_id) {
            case self::STANDARD_MEMBER_PRICE_ID[$environment . '_stripe_price_id']:
                return self::STANDARD_PLAN_ID;
            case self::PREMIUM_MEMBER_PRICE_ID[$environment . '_stripe_price_id']:
                return self::PREMIUM_PLAN_ID;
        }
    }

    public static function getPlanNameByPlanID($plan_id)
    {
        switch ($plan_id) {
            case self::STANDARD_PLAN_ID:
                return self::STANDARD_PLAN_NAME;
            case self::PREMIUM_PLAN_ID:
                return self::PREMIUM_PLAN_NAME;
            default:
                return self::FREE_PLAN_NAME;
        }
    }
}
