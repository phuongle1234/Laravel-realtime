<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VimeoRequest;
use App\Repositories\VimeoRepository;
use Illuminate\Support\Facades\Http;
use Throwable;
// use Vimeo\Vimeo;
use Vimeo;

class VimeoController extends Controller
{
    private $vimeoRepo;

    const PATH_INDEX = 'pages.admin.vimeo_management.';
    const URL_INDEX = 'admin.vimeo_management.';

    public function __construct( VimeoRepository $vimeoRepo )
    {
        $this->vimeoRepo = $vimeoRepo;
    }

    public function list()
    {

        try{
            $vimeo = Vimeo::request('/me');
            //$this->vimeoRepo->getVimeoAccount();
            return view( self::PATH_INDEX.'list', compact('vimeo') );

        }catch (Throwable $e){
            report($e->getMessage());
            return redirect()->route(self::URL_INDEX.'list')->withErrors(trans('common.error'));
        }

    }

    public function show($id)
    {
        try{
            $vimeo_model = $this->vimeoRepo->fetch($id);
            $vimeo_account = $this->vimeoRepo->getVimeoAccount();
            return view(self::PATH_INDEX.'edit',compact('vimeo_model','vimeo_account'));
        }catch (Throwable $e){
            report($e->getMessage());
            return redirect()->back()->withErrors(trans('common.error'));
        }

    }

    public function update(VimeoRequest $request,$id)
    {
        try{
            $vimeo_model = $this->vimeoRepo->fetch($id);
            $validated_requests = $request->only('client_id','client_secrets','personal_access_token');

            // authenticated by built-in vimeo package
            $built_in_vimeo = new Vimeo($validated_requests['client_id'],$validated_requests['client_secrets'],$validated_requests['personal_access_token']);
            $built_in_response = $built_in_vimeo->request('/me');

            if( $built_in_response['status'] !== 200 )
                return redirect()->back()->withErrors(trans('common.incorrect_personal_access_token'));

            // call api for authenticated
            $base64_string = base64_encode($validated_requests['client_id'].':'.$validated_requests['client_secrets']);
            $apiURL = 'https://api.vimeo.com/oauth/authorize/client';
            $postInputs = [
                'grant_type' => 'client_credentials',
                'scope' => 'public'
            ];
            $headers = [
                'Authorization' => 'basic '.$base64_string,
                'Content-Type' => 'application/json',
                'Accept' => 'application/vnd.vimeo.*+json;version=3.4'
            ];
            $response = Http::withHeaders($headers)->post($apiURL,$postInputs);
            $statusCode = $response->status();

            if($statusCode !== 200)
                return redirect()->back()->withErrors(trans('common.incorrect_client_input'));

            // update vimeo table
            $vimeo_model->client_id = $validated_requests['client_id'];
            $vimeo_model->client_secrets = $validated_requests['client_secrets'];
            $vimeo_model->personal_access_token = $validated_requests['personal_access_token'];
            $vimeo_model->save();

        }catch (Throwable $e){
            report($e->getMessage());
            return redirect()->back()->withErrors(trans('common.error'));
        }

    }
}
