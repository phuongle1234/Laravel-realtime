<?php

namespace App\Http\Controllers;

use App\Services\WebHookService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;


class WebHookController extends Controller
{
    private $service;

    public function __construct(WebHookService $webHookService )
    {
        $this->service = $webHookService;
    }

    public function handleWebhook(Request $request)
    {
        $isValidSignature = $this->service->verifyWebHook();

        if(!$isValidSignature){
            http_response_code(400);
        }

        $payload = json_decode($request->getContent(), true);

        $method = 'handle'. Str::studly(str_replace('.', '_', $payload['type']));

        if (method_exists($this, $method)) {
            return $this->{$method}($payload);
        }

        return $this->missingMethod();
    }

    public function missingMethod($parameters = [])
    {
        return new Response;
    }

    protected function handleInvoicePaymentSucceeded(array $payload)
    {
        Log::info($payload["data"]["object"]);

        DB::beginTransaction();
        try {
            $this->service->handlePaymentSucceed($payload);
            DB::commit();
            return http_response_code(200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            return redirect()->back()->withErrors(trans('common.error_occurred'));
        }
    }

    protected function handleInvoicePaymentFailed(array $payload)
    {
        Log::info($payload["data"]["object"]);

        DB::beginTransaction();
        try {
            $this->service->handlePaymentFailed($payload);
            DB::commit();
            return http_response_code(200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            return redirect()->back()->withErrors(trans('common.error_occurred'));
        }
    }

    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        Log::info($payload["data"]["object"]);

        DB::beginTransaction();
        try {
            $this->service->handleSubscriptionDeleted($payload);
            DB::commit();
            return http_response_code(200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            return redirect()->back()->withErrors(trans('common.error_occurred'));
        }
    }

}
