<?php

namespace App\Traits;

use Vimeo;

trait VimeoApiTrait {

    private $_viemo_service;

    public function setEnvironments(){

        //$this->_service = $_service;

        \Config::set('vimeo.connections.main.client_id', $this->_client_id );
        \Config::set('vimeo.connections.main.client_secret',  $this->_client_secret );
        \Config::set('vimeo.connections.main.access_token', $this->_access_token );

    }

    // public function checkVimeo(){

    // }

    public function checkVimeo(){

        return Vimeo::connection();
    }

    public function createFolder($_user_id,$_name){
        return Vimeo::request('/me/projects',
                [
                    'user_id' => $_user_id,
                    'name' => $_name
                ],'POST');
    }

    public function getVideoInFolder($_project_id){
        return Vimeo::request("/me/projects/{$_project_id}/videos",
                    [
                        'project_id ' => $_project_id,
                        'per_page' => 100
                    ],'GET');
    }

    public function getVideoById($_video_id){
        return Vimeo::request("/videos/{$_video_id}",
                [
                    'video_id' => $_video_id
                ],'GET');
    }

    public function uploadVideo( $_name, $_description){

        //$_folder_id,

        // $_video = Vimeo::upload($_file,[
        //     "name" => $_name,
        //     "description" => $_description
        // ]);

        return Vimeo::request("/me/videos",
                    [
                        "name" => $_name,
                        "description" => $_description,
                        'privacy' => [
                            'download' => false
                        ],
                        'embed' => [
                            'playbar' => true,
                            'title' => [
                                'portrait' => 'hide',
                                'name' => 'hide',
                                'owner' => 'hide'
                            ],
                            'logos' => [
                                'vimeo' => false
                            ],
                            'buttons' => [
                                'embed' => 'false',
                                'like' => 'false',
                                'share' => 'false',
                                'watchlater' => 'false',
                            ],
                            'end_screen' => [
                                'type' => 'empty'
                            ]
                        ]
                    ],
                    'POST');
    }

    public function like( $_video_id, $_un_like = false ){

        $_methos = 'PUT';
        if($_un_like)
        $_methos = 'DELETE';

        return Vimeo::request("/me/likes/{$_video_id}",[],$_methos);

    }

    public function editVideo($_video_id, $_name, $_description){

        return Vimeo::request("/videos/{$_video_id}",
                    [
                        'name' => $_name,
                        'description' => $_description,
                        'embed.buttons.embed' => true,
                        'embed.buttons.hd' => true,
                        'embed.buttons.like' => true,
                        'embed.buttons.scaling' => true,
                        'embed.buttons.share' => true,
                        'embed.buttons.watchlater' => true,
                        'embed.playbar' => true
                    ],
                    'PATCH');
    }

    public function deleteVideo( $_video_id ){
        return Vimeo::request("/videos/{$_video_id}", [], 'DELETE');
    }
}
