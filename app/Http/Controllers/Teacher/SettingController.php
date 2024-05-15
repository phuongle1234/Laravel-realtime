<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;
//use App\Traits\VimeoApiTrait;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\SettingRepository;
use App\Enums\EUser;
use App\Http\Requests\Teacher\SettingBankAccount;
use App\Http\Requests\Teacher\SettingProfile;
use Mail;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

use App\Models\BankAccount;
use App\Models\Setting;
use App\Repositories\BankAccountRepository;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\StoreFileTrait;
use Image;
use Hash;

class SettingController extends Controller
{
    use StoreFileTrait;

    const PATH_INDEX = "pages.teacher.setting";
    private $_setting;
    private $_bankAccount;
    private $_bankAccRepo;
    public function __construct(UserRepository $userRepo, SettingRepository $settingRepo, Setting $setting, BankAccount $bankAccount,
     BankAccountRepository $bankAccountRepo )
    {
        $this->_userRepo = $userRepo;
        $this->_settingRepo = $settingRepo;
        $this->_setting = $setting;
        $this->_bankAccount = $bankAccount;
        $this->_bankAccRepo = $bankAccountRepo;
    }

    public function profile(SettingProfile $request)
    {

            $item = $this->_userRepo->fetchWhere(['id' => Auth::user()->id, 'status' => EUser::STATUS_ACTIVE])->with('userSubject')->first();

            if ($request->isMethod("POST")) {

                DB::beginTransaction();
                try{

                    //$b_day = $request->year.'-'.$request->month.'-'.$request->day;
                    $data = $request->only('name','email','tel','edu_status');

                    if( $request->password )
                    $data['password'] = Hash::make( $request->password );

                    $_subject = collect( $request->subject )->map(function($val) use($item){ return ['subject_id' => $val, 'user_id' => $item->id, 'created_at' => Carbon::now() ]; })->toArray();

                    if($request->avatar){
                        //Image
                        if($item->avatar)
                            $this->storageDelete( $item->avatar );
                        //Storage::disk('public')->delete($item->avatar);

                        // $_avatar = Storage::disk('public')->putFileAs('avatar', $request->avatar, $item->code.".".$request->avatar->getClientOriginalExtension());

                        // $img = Image::make("storage/" . $_avatar);

                        // $img->resize(150, null, function ($constraint) {
                        //     $constraint->aspectRatio();
                        // })->save();
                        $_avatar = $this->storageUpload($request->avatar, 'avatar', $item->code.".png" );

                        $data['avatar'] = $_avatar;
                    }

                    $_user_subject = $item->userSubject();
                    $_user_subject->delete();
                    $_user_subject->insert($_subject);

                    $item->fill($data);
                    //$item->birthday = $b_day;
                    $item->introduction = $request->introduction;
                    // $item->verifyUser()->update(['introduction'=>$request->introduction]);

                    $item->save();

                    DB::commit();
                    return redirect()->back()->with(['message'=>trans("common.success")]);

                } catch (Exception $e) {
                    DB::rollBack();
                    report( $e );
                    return redirect()->back()->withErrors( trans("common.error") );

            }
        }
                return view(self::PATH_INDEX . '.profile', compact('item'));

    }

    public function notification(Request $request)
    {
        $item = $this->_settingRepo->fetchWhere(['user_id' => Auth::user()->id])->first();
        if ($request->isMethod("POST")) {
            DB::beginTransaction();
                try {
                    if (empty($item)) {
                        $item =  new $this->_setting();
                        $item->user_id = Auth::user()->id;
                    }
                    if (empty($request->notifications_by_email))
                        $item->notifications_by_email = 0;
                    else
                        $item->notifications_by_email = 1;
                    if (empty($request->notifications_from_admin))
                        $item->notifications_from_admin = 0;
                    else
                        $item->notifications_from_admin = 1;
                    if (empty($request->other_notices))
                        $item->other_notices = 0;
                    else
                        $item->other_notices = 1;
                    // dd($item);

                    $item->save();
                    DB::commit();
                    return redirect()->back()->with(['message'=>trans("common.success")]);


                } catch (\Exception $e) {
                    DB::rollBack();
                    report( $e );
                    return redirect()->back()->withErrors( trans("common.error") );

                }

        }
        return view(self::PATH_INDEX . '.notification', compact('item'));
    }

    public function request_reception(Request $request)
    {
        $item = $this->_settingRepo->fetchWhere(['user_id' => Auth::user()->id])->first();
        if ($request->isMethod("POST")) {
            DB::beginTransaction();
                try {
                    if (empty($item)) {
                        $item =  new $this->_setting();
                        $item->user_id = Auth::user()->id;
                    }

                    if (empty($request->accept_directly))
                        $item->accept_directly  = 0;
                    else
                        $item->accept_directly  = 1;
                    $item->save();
                    DB::commit();
                    return redirect()->back()->with(['message'=>trans("common.success")]);

                } catch (\Exception $e) {
                    DB::rollBack();
                    report( $e );
                    return redirect()->back()->withErrors( trans("common.error") );

                }
            ;
        }
        return view(self::PATH_INDEX . '.request_reception', compact('item'));
    }

    public function accountInfo(SettingBankAccount $request)
    {
        $item = $this->_bankAccRepo->fetchWhere(['user_id' => Auth::user()->id])->first();
        if ($request->isMethod("POST")) {
            DB::beginTransaction();
            try {
                    if (empty($item)) {
                        $item =  new $this->_bankAccount();
                        $item->user_id = Auth::user()->id;
                    }
                    $data = $request->only('bank_code', 'branch_code', 'bank_account_number', 'bank_account_name','bank_account_type');
                    $item->fill($data);
                    $item->save();
                    DB::commit();
                    return redirect()->back()->with(['message'=>trans("common.success")]);

                } catch (\Exception $e) {
                    DB::rollBack();
                    report( $e );
                    return redirect()->back()->withErrors( trans("common.error") );

                }
            ;
        }
        return view(self::PATH_INDEX . '.account_info', ['item'=>$item ?? []]);
    }

    public function contact(ContactRequest $request)
	{
        if($request->isMethod('POST')){

            try {

                $subject = "【自動送信メール】Webよりお問い合わせがありました";
                $mailTo = env('MAIL_CONTACT');


                $details = $request->all();

                Mail::send("mail.inquiry", compact('details'),
                    function($e) use ($mailTo, $subject) {
                        $e->to($mailTo)
                            ->subject($subject);
                    }
                );

                return redirect()->back()->with(['message' => trans("common.success.inquiry")]);

            } catch (\Throwable $e) {
                report( $e->getMessage() );
                return redirect()->back()->withErrors(trans("common.error"));
            }
        }

		$list_request_complete = auth()->guard('teacher')->user()->request_complete;

        return view(self::PATH_INDEX . '.inquiry',compact(
            'list_request_complete'
        ));

	}
}
