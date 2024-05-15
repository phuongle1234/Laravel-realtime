<?php

namespace App\Services\Student;

use App\Enums\EStatus;
use App\Enums\EStripe;
use App\Enums\EUser;
use App\Events\SendMailCancelPlanEvent;
use App\Events\SendMailChangePlanEvent;
use App\Repositories\UserStripeRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Subscription;

class SettingService extends BaseService
{
    const EXTRA_TICKET = 1;

    private $request;
    private $environment;
    private $userStripeRepo;

    public function __construct(Request $request, UserStripeRepository $userStripeRepo)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $this->request = $request;
        $this->environment = env('STRIPE_ENVIRONMENT');
        $this->userStripeRepo = $userStripeRepo;
    }

    private function setPlanPrice($plan)
    {
        switch ($plan){
            case EStripe::STANDARD_PLAN_ID:
                $plan_price = EStripe::STANDARD_MEMBER_PRICE_ID;
                break;
            case EStripe::PREMIUM_PLAN_ID:
                $plan_price = EStripe::PREMIUM_MEMBER_PRICE_ID;
                break;
        }
        return $plan_price;
    }

    public function updatePlan()
    {
        $result = [];
        $_selected_plan_id = (int)$this->request->only('selected_plan_id')['selected_plan_id'];
        $_user = auth()->guard('student')->user();
        $_user_stripe = $_user->stripe()->first();
        $_current_plan_id = $_user_stripe->plan_id;
        $plan_price = $this->setPlanPrice($_selected_plan_id);

        if ($_selected_plan_id === $_current_plan_id) {
            $result['message'] = trans('common.error');
            $result['code'] = 400;
        }

        switch ($_current_plan_id) {
            case EStripe::FREE_PLAN_ID:

                // create subscription
                $user_plan_subscription = Subscription::create([
                    'customer' => $_user_stripe->cus_id,
                    'items' => [
                        ['price' => $plan_price[$this->environment . '_stripe_price_id']]
                    ],
                    'payment_behavior' => 'allow_incomplete',
                    'proration_behavior' => 'always_invoice'
                ]);

                // update plan immediately
                $_user_stripe->plan_id = $_selected_plan_id;
                $_user_stripe->price_id = $plan_price[$this->environment . '_stripe_price_id'];
                $_user_stripe->subscription_id = $user_plan_subscription->id;
                $_user_stripe->save();

                // update tickets
//                $added_tickets = EStripe::getTicketsByPlan($plan_price[$this->environment . '_stripe_price_id'],$this->environment);
//                $_user->tickets()->create([
//                    'user_id' => $_user->id,
//                    'amount' => $added_tickets + self::EXTRA_TICKET,
//                    'status' => EStatus::ACTIVE,
//                    'note' => 'moi tao subscription tu setting change plan ( free -> paid )',
//                    'action' => EUser::CREATE_SUBSCRIPTION_ACTION
//                ]);

                if($user_plan_subscription){

                    $_user->fresh();
                    // send mail
                    $send_mail_data = [
                        'name' => $_user->name,
                        'email' => $_user->email,
                        'deadline_date' => $_user->current_period_end
                    ];
                    event(new SendMailChangePlanEvent($send_mail_data));
                }



                $result['message'] = trans('common.success');
                $result['code'] = 200;

                return $result;
                break;
            case EStripe::STANDARD_PLAN_ID:
            case EStripe::PREMIUM_PLAN_ID:
                $_subscriptionID = $_user_stripe->subscription_id;
                $_subscription = Subscription::retrieve($_subscriptionID, []);
                $_subscriptionItemID = $_subscription->items->data[0]->id;

                $_updated_subscription = Subscription::update($_subscriptionID,
                [
                    'proration_behavior' => 'none',
                    'items' => [
                        [   'id' => $_subscriptionItemID,
                            'price' => $plan_price[$this->environment . '_stripe_price_id']
                        ]
                    ]
                ]);

            if($_updated_subscription){

                $_user->fresh();
                // send mail
                $send_mail_data = [
                    'name' => $_user->name,
                    'email' => $_user->email,
                    'deadline_date' => $_user->current_period_end
                ];
                event(new SendMailChangePlanEvent($send_mail_data));
            }

                $result['message'] = trans('common.success');
                $result['code'] = 200;

                return $result;
                break;
        }
    }

    public function cancelPlan()
    {
        $result = [];
        $_user = auth()->guard('student')->user();
        $_user_stripe = $_user->stripe()->first();

        // update current subscription
        $subscription = Subscription::update($_user_stripe->subscription_id,[
            'cancel_at_period_end' => true
        ]);

        $_stripe_info = Subscription::retrieve($_user_stripe->subscription_id ,[]);;

        $_user->subscription = $_stripe_info;

        // send mail cancel plan
        if($subscription){
            $data = [
                'name' => $_user->name,
                'deadline_date' => $_user->current_period_end,
                'email' => $_user->email
            ];
            event(new SendMailCancelPlanEvent($data));
        }


        $result['message'] = trans('common.success');
        $result['code'] = 200;
        return $result;
    }
}
