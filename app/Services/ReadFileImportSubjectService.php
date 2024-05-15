<?php

namespace App\Services;
use Excel;
use Illuminate\Support\Carbon;

class ReadFileImportSubjectService
{

  public $_subject = [];
  public $_tab = [];
  private $_file_local;


  public function __construct()
  {
      $this->_file_local = __DIR__.'\..\..\public\file_template\subject.csv';
      $this->handSubject();
  }

  public function handSubject()
  {

    $_result = Excel::toArray([],$this->_file_local)[0];

    $_count = count($_result) - 1;
    $_id_subject = 0;

    for( $i = 2; $i <= $_count; $i ++ ){
        if($_result[$i][1]){
            $this->addSubject($_result[$i][0],$_result[$i][1]);
            $_id_subject ++;
        }
            $this->addTab($_id_subject,$_result[$i][2]);
    }

  }

  private function addSubject($icon,$name)
  {
      $icon = trim($icon);

      array_push($this->_subject,[
                  'name' => $this->addBreakLine($name),
                  'icon' => "common_img/sub_icon/{$icon}",
                  'status' => 1,
                  'created_at' => Carbon::now()
                ]);
      return $this->_subject;
  }

  private function addBreakLine($_string)
  {

      $_result = "";
      $stt = 0;
      $_array_str = mb_str_split($_string);

      $_star_str_break = $this->countStrBreak( count($_array_str) );

      foreach( $_array_str as $_key => $_row){
          if($stt == $_star_str_break){
            $_result.= "<br /> {$_row}";
            $stt = 1;
          }else{
            $_result.= "$_row";
              $stt ++;
          }
      }

      return $_result;
  }

  private function countStrBreak($_count_str)
  {
      if($_count_str >= 5)
        return 3;

      if($_count_str >= 7)
        return 4;

      if($_count_str == 3)
        return 3;

      return 2;
  }

  private function addTab($_id_subject,$name)
  {

      array_push($this->_tab,[
                  'name' => $name,
                  'subject_id' => $_id_subject,
                  'active' => '1',
                  'created_at' => Carbon::now()
                ]);

      return $this->_tab;
  }

}
