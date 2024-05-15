<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\EStatus;
use Illuminate\Http\Request;
//use App\Traits\VimeoApiTrait;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Enums\EUser;
use App\Requests\AdmitManageRequest;
use App\Requests\Student\ChangeRegistRequest;
use Illuminate\Support\Facades\Crypt;
use App\Enums\EPage;
use App\Enums\EStripe;
use Auth;
use Hash;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\PaymentIntent;

use App\Models\Contact;
use App\Models\Message;
use App\Jobs\SeenMessageJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Repositories\RequestRepository;
use Mail;
use Illuminate\Support\Facades\DB;
use App\Traits\StoreFileTrait;
use Image;

use App\Services\ReadFileImportSubjectService;
//ReadFileImportSubjectService $_text  	$this->_text = $_text;

class UserController extends Controller
{
	use StoreFileTrait;

	private $_userRepo;
	private $_request_repo;
	private $_order_by;
	const URL_INDEX = "teacher.";
	const PATH_INDEX = "pages.teacher.home";
	private $_text;
    public function __construct(UserRepository $userRepo,RequestRepository $_request_repo)
	{
		Stripe::setApiKey(env('STRIPE_SECRET'));
		$this->_userRepo = $userRepo;
		$this->_request_repo = $_request_repo;
		$this->_order_by = EPage::getOrderBy();
		// $this->driver = 'public';
		parent::__construct();
	}


	public function index(Request $request)
	{
		try {

			return view(self::PATH_INDEX.'.index');

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function showCardId(Request $request){
		try {

			$_user = Auth::guard('student')->user();
			$_stripe = $_user->stripe()->first();

			$_card = Customer::allSources(
						$_stripe->cus_id,
						['object' => 'card', 'limit' => 100]
					)->data;

			$_default = Customer::retrieve($_stripe->cus_id,[])->default_source;
			if( $request->method() == 'GET' )
				return view('pages.student.setting.payment',compact('_card','_default'));

			return view('component.pop_up.payment_method',compact('_card','_default'));

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function addCard(Request $request){
		try {

			$_user = Auth::guard('student')->user();
			$_stripe = $_user->stripe()->first();

			Customer::createSource(
						$_stripe->cus_id,
						['source' => $request->token_stripe]
			);



			return redirect()->route(self::URL_INDEX."setting.payment");

		} catch (Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function updateDefaultCard(Request $request){
		try {

			$_user = Auth::guard('student')->user();
			$_stripe = $_user->stripe()->first();

			Customer::update(
						$_stripe->cus_id,
						['default_source' => $request->card_id]
			);

			return redirect()->route(self::URL_INDEX."setting.payment");

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function showEditCard(Request $request, $card_id){
		try {

			$_user = Auth::guard('student')->user();
			$_stripe = $_user->stripe()->first();

			$_card = Customer::retrieveSource(
						$_stripe->cus_id,
						$card_id
					);

			return view("pages.student.setting.payment_edit", compact('_card'));

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function cardInfor(Request $request){

		try {

			$_user = Auth::guard('student')->user();
			$_stripe = $_user->stripe()->first();

			$_card = Customer::retrieveSource(
						$_stripe->cus_id,
						$request->card_id
					);

			return view("component.pop_up.edit_card", compact('_card'));

		} catch (\Throwable $e) {
			report( $e );
			return response()->json( $e ,400);
		}
	}
	// using ajx

	public function updatePercent(Request $request){
		try {

			$_user = Auth::guard('teacher')->user();

			// $_verify_user = $_user->verifyUser()->first();

			if( $_user->percent_training > $request->percent)
			throw new Exception(trans('common.error'));

			$_user->percent_training = $request->percent;
			$_user->save();


			return response()->json( trans('common.success') ,200);
		} catch (\Throwable $e) {
			report( $e );
			return response()->json( $e ,400);
		}
	}

	public function updateCardInfor(Request $request){
		try {

			$_user = Auth::guard('student')->user();
			$_stripe = $_user->stripe()->first();

			Customer::deleteSource(
						$_stripe->cus_id,
						$request->card_id,
						[]
			);

			Customer::createSource(
				$_stripe->cus_id,
				['source' => $request->token_stripe]
			);

			return response()->json( trans('common.success') ,200);

		} catch (Throwable $e) {
			report( $e );
			return response()->json( $e ,400);
		}
	}

	public function paymentIntents(Request $request){
		try {

			DB::beginTransaction();

			$_user = Auth::guard('student')->user();
			$_stripe = $_user->stripe()->first();

			$_pyment = PaymentIntent::create([
				'amount' => $request->tickets * EStripe::POINT,
				'currency' => 'JPY',
				'customer' => $_stripe->cus_id
				// 'payment_method_types' => ['card'],
			  ]);

			  $_user->tickets()->create(['amount'=> $request->tickets,  'status' => EStatus::ACTIVE, 'note' => 'buy points by stripe' ]);

			DB::commit();

			return response()->json( ['point' => $_user->fresh()->point ] ,200);

		} catch (\Throwable $e) {
			DB::rollBack();
			report( $e );
			return response()->json( $e ,400);
		}
	}

	public function updateCard(Request $request, $card_id){
		try {

			$_user = Auth::guard('student')->user();
			$_stripe = $_user->stripe()->first();

			Customer::deleteSource(
						$_stripe->cus_id,
						$card_id,
						[]
			);

			Customer::createSource(
				$_stripe->cus_id,
				['source' => $request->token_stripe]
			);

			return redirect()->route(self::URL_INDEX."setting.payment");

		} catch (Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function show(Request $request, $id)
	{

		try {
			$item = $this->_userRepo->fetchWhere(['id' => $id, 'status' => EUser::STATUS_ACTIVE])->first();
			return view(self::PATH_INDEX.'.edit', compact('item') );

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}

	}

	public function update(ChangeRegistRequest $request)
	{

		try {
			$_user = Auth::guard('student')->user();
			$_request = $request->only('email','name');

			if($request->avatar){
				//Image
				if($_user->avatar)
				$this->storageDelete($_user->avatar);

				//Storage::disk('public')->delete($_user->avatar);

				$_request['avatar'] = $this->storageUpload($this->request->avatar, 'avatar', $_user->code ."-". date('Ymdhis') . ".png" );


				// $_avatar = Storage::disk('public')->putFileAs('avatar', $request->avatar, $_user->code.".".$request->avatar->getClientOriginalExtension());
				// $img = Image::make("storage/" . $_avatar);
				// $img->resize(150, null, function ($constraint) {
				// 	$constraint->aspectRatio();
				// })->save();

				$_request['avatar'] = $_avatar;
			}

			$_user->update( $_request );

			return redirect()->route(self::URL_INDEX."setting.index")->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			report( $e );
			return redirect()->back()->withErrors( trans("common.error") );
		}

	}

	public function updateNoti(Request $request)
	{

		try {

			$_user = Auth::guard('student')->user();
			$_seting = $_user->setings();

			$_seting->updateOrCreate(
					['user_id' => $_user->id ],
					[
						'notifications_by_email' => $request->notifications_by_email ? true : false,
						'notifications_from_admin' => $request->notifications_from_admin ? true : false,
						'other_notices' => $request->other_notices ? true : false
					]);

			return redirect()->back()->with(['message'=>trans("common.success")]);

		} catch (Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}

	}

	public function message(Request $request)
	{

		try {

			$_user = Auth::guard('teacher')->user();
			$_contact = Contact::where(['user_id' =>$_user->id])
			            ->with(['request','user', 'message'])
						->whereHas('user', function($_query){
							$_query->whereNull('users.deleted_at');
						})
						->whereHas('request', function($_query){
							$_query->whereNull('requests.deleted_at');
						})
						->orderBy( $this->_order_by_default[0], $this->_order_by_default[1] )->get();

			return view('pages.teacher.message.index', compact('_contact') );

        } catch (Throwable $th) {
            report( $th->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
        }

	}

	public function contact(Request $request)
	{

		try {

            $subject = "【自動送信メール】Webよりお問い合わせがありました";
            $mailTo = env('MAIL_CONTACT');


            $details = $request->all();

            Mail::send("mail.inquiry",compact('details'),
                    function($e) use ($mailTo, $subject) {
                        $e->to($mailTo)
                        ->subject($subject);
                    }
            );

			return redirect()->back()->with(['message' => trans("common.success.inquiry")]);

        } catch (\Throwable $th) {
            report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
        }

	}

	public function blockRequest(Request $request){
		try {
			DB::beginTransaction();
			$_user = Auth::guard('student')->user();
			$_contact = Contact::where('id',$request->contact_id)->first();
			$_contact->status = EStatus::DENIED;
			$_contact->save();
			$_request = $this->_request_repo->fetchWhere([ 'id' => $_contact->request_id ] )->update([ 'status' => EStatus::DENIED ]);
			$_user->blockedUser()->updateOrCreate(['user_id' => $_user->id, 'blocked_user_id' => $_contact->user_contact_id], ['blocked_user_id' => $_contact->user_contact_id] );
			DB::commit();
			return redirect()->back()->with(['message'=>trans("common.success")]);
        } catch (Throwable $th) {
			DB::rollBack();
            report( $th );
			return redirect()->back()->withErrors( trans("common.error") );
        }
	}
}
