<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;
use PHPExcel_Cell;

// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;
use Auth;

class ViewsExport implements FromView
{

    private $_data;
    private $_date_time;


    public function __construct($_data, string $_date_time)
    {
        $this->_data = $_data;
        $this->_date_time = $_date_time;

    }

    public function view(): View
    {
        return view('exports.file_export', [ '_data' => $this->_data, '_date_time' => $this->_date_time ]);
    }

}
