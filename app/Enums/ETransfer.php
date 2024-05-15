<?php

namespace App\Enums;

class ETransfer
{
    const BONUS = 'bonus';
    const BONUS_TEXT = '追加';
    const BONUS_SYMBOL = '+';

    const MINUS = 'minus';
    const MINUS_TEXT = '削減';
    const MINUS_SYMBOL = '-';

    const COMPLETE_REQUEST = 'complete_request';
    const COMPLETE_REQUEST_TEXT = 'リクエスト完了報酬';

    const REWARD_REQUEST = 'reward_request';
    const REWARD_REQUEST_TEXT = '報酬リクエスト';

    const NORMAL_REQUEST = 250;
    const URGENT_REQUEST = 300;
    const DIRECT_REQUEST = 300;

    public static function renderAction($value)
    {
        switch ($value) {
            case self::BONUS:
                return self::BONUS_TEXT;
            case self::MINUS:
                return self::MINUS_TEXT;
            case self::COMPLETE_REQUEST:
                return self::COMPLETE_REQUEST_TEXT;
            case self::REWARD_REQUEST:
                return self::REWARD_REQUEST_TEXT;
        }
    }

    public static function renderTransferByAction($value,$action)
    {
        switch ($action) {
            case self::BONUS:
            case self::COMPLETE_REQUEST:
                return self::BONUS_SYMBOL . number_format($value);
            case self::MINUS:
            case self::REWARD_REQUEST:
                return "<span class='cl-red'>" . number_format($value) . "</span>";
        }
    }
}
