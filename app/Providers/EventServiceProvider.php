<?php

namespace App\Providers;

use App\Events\SendMailActivatedTeacherAccount;
use App\Events\SendMailCancelPlanEvent;
use App\Events\SendMailChangePlanEvent;
use App\Events\SendMailPaymentFailedStripeEvent;
use App\Events\SendMailRemoveAccountEvent;
use App\Listeners\SendMailActivatedTeacherAccountListen;
use App\Listeners\SendMailCancelPlanListener;
use App\Listeners\SendMailChangePlanListener;
use App\Listeners\SendMailPaymentFailedStripeListener;
use App\Listeners\SendMailRemoveAccountListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
// use send message
use App\Listeners\SendMessageListen;
use App\Events\MessageSentEvent;

// event elequent
use App\Models\User;
use App\Observers\UserObserver;
// event for request
use App\Models\Request;
use App\Observers\RequestObserver;

// event send mail otp
use App\Events\SendMailOtp;
use App\Listeners\SendMailOtpListen;

// event send mail register complete
use App\Events\SendMailRegisterComplete;
use App\Listeners\SendMailCompleteListen;

// event reset pass
use App\Events\SendMailRestPass;
use App\Listeners\SendMailRestPassListen;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [

        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        MessageSentEvent::class => [
            SendMessageListen::class
        ],
        SendMailPaymentFailedStripeEvent::class => [
            SendMailPaymentFailedStripeListener::class
        ],
        SendMailCancelPlanEvent::class => [
            SendMailCancelPlanListener::class
        ],
        SendMailChangePlanEvent::class => [
            SendMailChangePlanListener::class
        ],
        SendMailRemoveAccountEvent::class => [
            SendMailRemoveAccountListener::class
        ],
        SendMailOtp::class => [
            SendMailOtpListen::class
        ],
        SendMailRegisterComplete::class => [
            SendMailCompleteListen::class
        ],
        SendMailRestPass::class => [
            SendMailRestPassListen::class
        ],
        SendMailActivatedTeacherAccount::class => [
            SendMailActivatedTeacherAccountListen::class
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Request::observe(RequestObserver::class);
    }
}
