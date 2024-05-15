<?php

namespace App\Services;

use App\Enums\EStatus;
use App\Enums\EStripe;
use App\Enums\EUser;
use App\Events\SendMailPaymentFailedStripeEvent;
use App\Repositories\UserStripeRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Subscription;

class WebHookService extends BaseService
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

    public function verifyWebHook()
    {
        $secret = env("STRIPE_WEBHOOK_SECRET");

        $payload = $this->request->getContent();
        $sig_header = $this->request->header('stripe_signature');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $secret
            );
        } catch (\UnexpectedValueException $e) {
            return false;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return false;
        }

        return $event;
    }

    public function handlePaymentSucceed(array $payload)
    {
        $customerId = $payload['data']['object']['customer'];
        $subscriptionId = $payload['data']['object']['subscription'];
        $price = $payload['data']['object']['lines']['data'][0]['price']['id'];

        $findPayment = $this->userStripeRepo->fetchByCondition([
            'cus_id' => $customerId,
            'subscription_id' => $subscriptionId
        ]);

        if($findPayment){
            $user = $findPayment->user()->first();
            $user_stripe = $user->stripe()->first();
            $subscription = Subscription::retrieve($user_stripe->subscription_id,[]);
            $added_tickets = EStripe::getTicketsByPlan($price,$this->environment);

            // check if subscription is cancel, do nothing
            if($subscription->cancel_at_period_end)
                return http_response_code(400);

            // check if user has subscription or not
            $is_subscription = $user->tickets()->where([
                'action' => EUser::CREATE_SUBSCRIPTION_ACTION,
                'user_id' => $findPayment->user_id
            ])->first();

            if(!empty($is_subscription)){

                // if user change plan, update it
                if($price != $findPayment->price_id){
                    $current_plan = EStripe::getPlanByPrice($price,$this->environment);
                    $findPayment->price_id = $price;
                    $findPayment->plan_id = $current_plan;
                    $findPayment->save();
                }

                // update tickets

                if( $added_tickets && ( $user_stripe->plan_id != EStripe::PREMIUM_PLAN_ID ) )
                {
                    $user->tickets()->create([
                        'user_id' => $user->id,
                        'amount' => $added_tickets,
                        'status' => EStatus::ACTIVE,
                        'note' => 'renew subscription',
                        'action' => EUser::CREATE_SUBSCRIPTION_ACTION
                    ]);
                }

            } else {

                // check if there are any deleted subscription before or not.
                // if there are not, add extra ticket


                // if($user->tickets()->withTrashed()->get()->isEmpty() && ( $user_stripe->plan_id != EStripe::PREMIUM_PLAN_ID ) )
                //     $added_tickets = $added_tickets + self::EXTRA_TICKET;

                $user->tickets()->create([
                    'user_id' => $user->id,
                    'amount' => $added_tickets,
                    'status' => EStatus::ACTIVE,
                    'note' => 'moi tao subscription',
                    'action' => EUser::CREATE_SUBSCRIPTION_ACTION
                ]);
            }
        }
        return http_response_code(404);
    }

    public function handlePaymentFailed(array $payload)
    {
        $customerId = $payload['data']['object']['customer'];
        $subscriptionId = $payload['data']['object']['subscription'];

        $findPayment = $this->userStripeRepo->fetchByCondition([
            'cus_id' => $customerId,
            'subscription_id' => $subscriptionId
        ]);

        if($findPayment){
            $user = $findPayment->user()->first();

            $data = [
                'name' => $user->name,
                'email' => $user->email
            ];

            // send mail
            event(new SendMailPaymentFailedStripeEvent($data));
        }
        return http_response_code(404);
    }

    public function handleSubscriptionDeleted(array $payload)
    {
        $customerId = $payload['data']['object']['customer'];
        $subscriptionId = $payload['data']['object']['id'];

        $findPayment = $this->userStripeRepo->fetchByCondition([
            'cus_id' => $customerId,
            'subscription_id' => $subscriptionId
        ]);

        if($findPayment){
            $user = $findPayment->user()->first();

            // update user plan
            $findPayment->subscription_id = null;
            $findPayment->plan_id = EStripe::FREE_PLAN_ID;
            $findPayment->price_id = null;
            $findPayment->save();

            // remove all ticket
            $_tickets = $user->tickets();
            $_tickets->update([ 'note' => "deleted due to free plan transfer" ]);
            $_tickets->delete();

        }
        return http_response_code(404);
    }
}
