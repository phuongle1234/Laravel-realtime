<?php

namespace App\Services;

class SeoService
{
    public $title;

    public $keyword;

    public $description;

    public function __construct()
    {
        $items = $this->getStaticSeo();
        $this->setSeo($items);
    }

    public function getTitle(){
        return $this->title;
    }

    public function getKeyword(){
        return $this->keyword;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setTitle($title = ''){
        $this->title = $title;
    }

    public function setKeyword($keyword = ''){
        $this->keyword = $keyword;
    }

    public function setDescription($description = ''){
        $this->description = $description;
    }

    private function readFile() {

        $json_file = @file_get_contents(public_path('seo.json'));
        return !empty($json_file) ? json_decode($json_file,true) : [];

    }

    public function getSeo() {

        return [
            'title' => $this->getTitle(),
            'keyword' => $this->getKeyword(),
            'description' => $this->getDescription()
        ];
    }

    public function setSeo($items) {

        if(is_array($items)){
            foreach($items as $key => $val){

                // method : setTitie(), setKeyword(), ...
                @$this->{'set'. strtoupper($key) } ($val);
            }
        }

    }

    public function getStaticSeo(){

        $items = $this->readFile();
        $parent_path = request()->path();
        $route_name = request()->route() ? request()->route()->getName(): "";

        $res = [];

        foreach($items as $key => $val){

            // RETURN SEO VALUE DEPEND ON CURRENT URL
            if($route_name == $key){
                return $res[$key] = $val;
            }
        }

        return [];
    }
}