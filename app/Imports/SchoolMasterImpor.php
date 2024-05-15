<?php
namespace App\Imports;

use App\Models\SchoolMaster;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToModel;

class SchoolMasterImpor implements ToModel, WithStartRow
{
    public function model(array $row)
    {

        return new SchoolMaster([
            'university_Code' => $row[0],
            'faculty_code' => $row[1],
            'university_name' => $row[2],
            'faculty_name' => $row[3],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}