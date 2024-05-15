<?php


namespace App\Enums;
use DB;
use Carbon\Carbon;
use PhpParser\Node\Stmt\Return_;

class ENotification {

    // student send notice to teacher

    static function addHyperlinkForStudent(): string
    {
        return route('student.request.list');
    }

    static function addHyperlinkForTeacher(): string
    {
        return route('teacher.request.list');
    }

    static function addHyperlinkForTeacherDenied($_id): string
    {
        return route('teacher.request.edit',["id" => $_id ]);
    }

    static function addHyperlinkForTeacherComplete()
    {
        return route( 'teacher.request.accepted' );
    }

    static function SEND_REQUEST_DIRECT ( $_name_student, $_dead_line )
    {

      $_dead_line = Carbon::parse( $_dead_line )->format('Y年m月d日 H:i');

      return  [
                    'title' => '直接リクエストの受注依頼が来ています',
                    'content' => " $_name_student さんからあなたに直接リクエストの受注依頼が来ています。 \n
                                    内容を確認して、受注するかどうかを決定してください。\n
                                    リクエスト受注期限 $_dead_line  \n" ,
             ];
    }

    static function BLOCKED_REQUEST ($_student_name){

        return [
            'title' => "リクエストがキャンセルされました",
            'content' => "$_student_name さんがリクエストをキャンセルしました。\n",
        ];

    }

    static function COMPLETE_REQUEST ($_request_name){
        $url = self::addHyperlinkForTeacherComplete()."#complete";

        return [
            'title' => "リクエストが完了しました",
            'content' => "<a href='{$url}'>$_request_name</a> が完了しました。 \n
                          報酬を確認して下さい。",
        ];

    }

    // teacher send notice to student
    // *** do not accept

    static function NOT_ACCEPT_REQUEST ( $_title_request, $_name_teacher ) {
        $url = self::addHyperlinkForStudent();

        return [
                    'title' => '直接リクエストが受諾されませんでした。',
                    'content' => "{$_name_teacher}さんが <a href='{$url}'>$_title_request</a> の直接リクエストを受諾しませんでした。 \n
                    <a href='{$url}'>$_title_request</a> は通常のリクエストとなり、ほかの先生が受注可能になります。",
                ];
    }

    static function ACCEPT_REQUEST ( $_title_request, $_name_teacher ) {
        $url = self::addHyperlinkForStudent();

        return [
                    'title' => 'リクエストが受諾されました',
                    'content' => "<a href='{$url}'>$_title_request</a>  が $_name_teacher さんによって受諾されました。 \n
                     動画アップロードまでしばらくお待ちください。",
                ];
    }

    static function UPLOADED_VIDEO(){
        return [
            'title' => "動画がアップロードされました",
            'content' => ''
        ];
    }

    // admin send notice

    static function PASS_VIDEO_TO_STUDENT ($_request_name){
        $url = self::addHyperlinkForStudent();

        return [
                'title' => '先生が動画をアップロードしました',
                'content' => "<a href='{$url}'>$_request_name</a> の動画がアップロードされました。\n
                内容を確認して、問題がなければリクエストを完了してください。",
        ];
    }

    static function DELETE_REQUEST($_request_name) {
        return [
            'title' => 'リクエストが削除されました',
            'content' => "$_request_name は \n
                          リクエストの内容がポリシーに違反している、または受諾した先生 が動画制作を放棄したため、管理者によって削除されました。\n
                          使用されているチケットは返還されます。",
        ];
    }

    static function DENIED_VIDEO($_request_name, $_id, $_reason){
        $url = self::addHyperlinkForTeacherDenied($_id);

        return [
            'title' => 'この動画の公開を拒否します。',
            'content' => "<a href='{$url}'>$_request_name</a> にアップロードされた動画は内容を変更する必要があります。 \n
                         【再提出の理由】 \n
                          $_reason",
        ];
    }

    //system send notice

    static function DELAYED_REQUEST($_request_name){
        $url = self::addHyperlinkForTeacherComplete();

        return [
            'title' => "動画のアップロードが遅れています",
            'content' => "<a href='{$url}'>$_request_name</a> の動画アップロードが遅れています。 \n
                           直ちに動画を作成しアップロードしてください。"
        ];
    }

    // TRANCODE NOT COMPLETE FROM VIMEO

    static function DENIED_VIDEO_VIMEO( $_teach_name ,$_id, $_request_name){

        $url = self::addHyperlinkForTeacherDenied($_id);

        return [
            'title' => '動画のアップロードに失敗しました。',
            'content' => " $_teach_name 様 \n
                        <a href='{$url}'>$_request_name</a> にアップロードされた動画は内容を変更する必要があります。 \n
                        再度動画のアップロードをお願いいたします。"
        ];

    }

    static function EXPIRED_REQUEST($_request_name){

        // $url = self::addHyperlinkForStudent();

        return [
            'title' => "リクエストが受注されませんでした",
            'content' => "$_request_name が2日間以内に受注されませんでした。 \n
                        リクエストはキャンセルされ、チケットは返還されます。 \n
                        再度回答を募集する場合は、リクエストをもう一度作成してください。 \n \n

                        ※ヒント
                        リクエストの詳細が不十分だと、先生がリクエストの内容について、しっかりと理解できない場合があります。できる限り詳しく内容を記載してください。"
        ];
    }


    // const ACCEPT_VIDEO = [
    //     'title' => '動画を承認しました。',
    //     'content' => '３日以内に動画のアップロードを完了してください。',
    // ];

    // const CANCEL_REQUEST_DIRECT = [
    //     'title' => 'リクエストを拒否しました。',
    //     'content' => 'リクエストを拒否しました。',
    // ];

    // admin send notice to student


    // Refun request notification to student
    static function REFUND_REQUEST_TO_STUDENT($_request){
        return [
            'title' => "{$_request->title}, was deleted due to overdue",
            'content' => 'リクエストの送信が完了しました。'
        ];
    }



}
