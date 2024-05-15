@extends('layouts.student.index')

@inject('_watches','App\Services\StatisticsService')

@section('body_class','main_body p-study_management pdbottom')
@section('class_main','p-content-study_management pdbottom')


@php

  $_month =  isset($month) ? (int)$month : date('m');
  $_day = isset($day) ? (int)$day : date('d');
  $_year = isset($year) ? (int)$year : date('Y');

  $_result_month = $_watches->totalViewByMonth([$_month,$_year]);

  $_result_day = $_watches->totalViewByDay([$_day,$_month,$_year]);

  $_result_month = collect( $_result_month->toArray() )->mapWithKeys( function( $_val ){
                                                             return [$_val['day'] => (int)$_val['seconds']];
                                                        })->toArray();


  $_max_day = cal_days_in_month(CAL_GREGORIAN, (int)$_month, (int)$_year);

  $_wday_first_day_month = getdate( strtotime("{$_year}-{$_month}-01") )['wday'];

  $_range = array_merge(  array_fill(1, $_wday_first_day_month, null) , range(1,$_max_day)  );

  $_range_day = array_chunk( $_range ,7);

  $_user =  Auth::guard('student')->check()  ? Auth::guard('student')->user() : null;
  $_arr_colr = ['cl-5BB9CD','cl-80D4E5','cl-80BBE5', 'cl-80A2E5', 'cl-8280E5', 'cl-B980E5', 'cl-E580C3', 'cl-E15FB5', 'cl-E15F96', 'cl-E5337E'];

@endphp

@section('custom_title')
  <link rel="stylesheet" href="{{ asset('student/css/slick/slick.css') }}">
  <link rel="stylesheet" href="{{ asset('student/css/slick/slick-theme.css') }}">
@endsection

<!-- section('custom_title')
<img src="{{ asset('student/images/your_request/icon_001.svg') }}" alt="依頼中のリクエスト">
endsection -->

@section('content')

<div class="study-block">
                  <div class="item">
                    <h2 class="secondpage-tit">{{ trans('student.study-learning') }}</h2>
                    <div class="box-shawdow calendar-box">
                      <div class="calendar-title">
                        <select class="select-month">
                          @for($i=1; $i<=12; $i++)
                          <option value="{{ $i }}" {{ $_month == $i ? 'selected' : null }}>{{ $i }}月</option>
                          @endfor
                        </select>

                        <select class="select-year" name="year">
                          @for( $i = (int)date('Y'); $i>= date('Y')-4; $i-- )
                            <option value="{{ $i }}" {{ $_year == $i ? 'selected' : null }} >{{ $i }}</option>
                          @endfor
                        </select>
                      </div>
                      <div class="calendar-body">
                        <table>
                          <thead>
                            <tr>
                              <th>日</th>
                              <th>月</th>
                              <th>火</th>
                              <th>水</th>
                              <th>木</th>
                              <th>金</th>
                              <th>土</th>
                            </tr>
                          </thead>
                          <tbody>

                          @for($i = 0; $i <= count($_range_day) -1; $i ++)
                            <tr>
                                @foreach($_range_day[$i] as $_key => $_row)
                                  <td {{ $_day == $_row ? 'class=today' : null }}>
                                    <div class="custom-checkbox">
                                      <input type="radio" name="chooseday" {{ $_row ? "onclick=filtetFrom(event)" : null }} value="{{ $_row }}" >
                                      <label for=""><span>{{ $_row }}</span></label>
                                    </div>
                                    {!!  isset($_result_month[$_row]) ? "<span class=note>".round( $_result_month[$_row] /60 ,0, PHP_ROUND_HALF_DOWN)."分</span>" : null  !!}
                                  </td>
                                @endforeach
                            </tr>
                          @endfor

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="item fixchart">
                    <h2 class="secondpage-tit">{{ trans('student.study-time') }}</h2>
                    <div class="box-shawdow">
                      <div class="chart-wrapper scroll-beauty-horizontal">

                        <ul class="chart">
                              @foreach( $_result_day as $_val => $_row )
                                <li class="chart-column">
                                  <div class="hour">{{ $_row['hour'] }}:00</div>
                                  @php
                                    $_percent = round( $_row['seconds'] /3600 ,1)*100;
                                    $_percent = $_percent > 0 ? $_percent : 1;
                                  @endphp
                                  <div class="percent-cl percent-25">
                                    <div style="height: {{ $_percent }}%" class="percent-cl--ins {{ $_arr_colr[rand(0, count($_arr_colr)-1 )]  }}"></div>
                                  </div>
                                  <div class="percent-txt">{!! round(  $_row['seconds'] /60 ,0, PHP_ROUND_HALF_DOWN) !!}分</div>
                                </li>
                              @endforeach
                        </ul>
                      </div>
                      @php
                        $_re_port = isset($_result_month[$_day]) ? gmdate( 'H時間i分s秒', $_result_month[$_day] ) : null;
                      @endphp
                      <h2 class="chart-title"> <span>{{ $_year }}年{{ $_month }}月{{ $_day }}日合計学習時間：{{ $_re_port }}</span></h2>
                    </div>
                  </div>
                </div>

@endsection

@section('custom_js')
<script src="{{ asset('student/js/slick.min.js') }}"></script>
<script>

let _dtt = new Date();
let _day_nt = _dtt.getDate();
let _month_nt = _dtt.getMonth()+1;
let _year_nt = _dtt.getFullYear();

$('select.select-year').parent().find('ul.select-options li').on('click',(e)=>{
      renderDate();
})

$('select.select-month').parent().find('ul.select-options li').on('click',(e)=>{
      renderDate();
})


//axios('example/url'[, config])
//const _repose =  new Promise( (resole,reson) => {
          // let _month = $('select.select-month').val();
          // let _year = $('select.select-year').val();

//});

async function renderDate(){

    let _month = $('select.select-month').val();
    let _year = $('select.select-year').val();

    repose = await axios.post( window.location.origin+'/userStatistics',{ _method: 'PUT', month: _month, year: _year });
    repose = repose.data;

    _wday_first_day_month = new Date(_year, parseInt(_month)-1, 1).getDay();
    _days = new Date(_year, _month, 0).getDate();

    _row = Math.round( (_days + _wday_first_day_month)/7 );

    let _html = '';

    _day_stt = 0;

    // render tr

    for(_i=0; _i <= _row; _i++)
    {
      _html+= '<tr>';

          for(i=1; i <= 7; i++)
          {

            if(_i == 0 && i == 1 && _wday_first_day_month != 0)
            {
              _text = '<td></td>';
              _html+= _text.repeat(_wday_first_day_month);
              i = _wday_first_day_month;
              continue;
            }

            if(_day_stt == _days)
              break;

            _day_stt++;
            _class = ( _day_nt == _day_stt && _month_nt == _month && _year_nt == _year ) ? 'class="today"' : null;

            _note = repose[_day_stt] != undefined ?  `<span class="note">${ Math.round( repose[_day_stt]/60 ) }分</span>` : '';

            _html+= `
                        <td ${_class}>
                            <div class="custom-checkbox">
                              <input type="radio" name="chooseday" onclick="filtetFrom(event)" value="${_day_stt}">
                              <label for=""><span>${_day_stt}</span></label>
                            </div>
                            ${_note}
                          </td>
                     `;
          }

      _html+= '</tr>';

    }


    $('div.calendar-body table tbody').empty();
    $('div.calendar-body table tbody').html(_html);
}

function filtetFrom(e){

    let _day = e.target.value;
    let _month = $('select.select-month').val();
    let _year = $('select.select-year').val();

    var form = $("<form>",{ method:'post' })
              .append($('<input>',{name:'_token',value:_token}))
              .append($('<input>',{name:'day',value:_day}))
              .append($('<input>',{name:'month',value:_month}))
              .append($('<input>',{name:'year',value:_year}));
    form.appendTo('body').submit();
    form.remove();

}


</script>
@endsection
