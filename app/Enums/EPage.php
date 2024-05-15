<?php


namespace App\Enums;


class EPage {

    const EPAGE_REDIRECT_LOGIN_ADMIN = 'admin.adminManagement.list';
    const EPAGE_REDIRECT_LOGIN_TEACHER = 'teacher.home';
    const EPAGE_REDIRECT_LOGIN_STUDENT = 'student.home';
    const E_PER_PAGE_DEFAULT = 10;

    // Using custom per page
    //private static $EPAGE_REQUEST = 50;

    const E_ODER_BY_DEFAULT = ['created_at','DESC'];
    // Using custom order by

    public static function redirectLogin( string $role):string
    {
        switch ($role){
            case 'admin':
                return self::EPAGE_REDIRECT_LOGIN_ADMIN;
                break;
            case 'teacher':
                return self::EPAGE_REDIRECT_LOGIN_TEACHER;
                break;
            case 'student':
                return self::EPAGE_REDIRECT_LOGIN_STUDENT;
                break;
        }
    }

    public static function getPerPage( string $_name_page ):int
    {
        return isset( self::${$_name_page} ) ? self::${$_name_page} : self::E_PER_PAGE_DEFAULT;
    }

    public static function getOrderBy( string $_order_by = null ):array
    {
        return isset( self::${$_order_by} ) && $_order_by ? self::${$_order_by} : self::E_ODER_BY_DEFAULT;
    }

}
