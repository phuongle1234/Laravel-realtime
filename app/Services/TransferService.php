<?php

namespace App\Services;

use App\Enums\EStatus;
use App\Enums\ETransfer;
use App\Exports\TransferExport;
use App\Models\UserCompensationsLogs;
use App\Repositories\UserCompensationRepository;
use App\Services\BaseService;
use Maatwebsite\Excel\Facades\Excel;

class TransferService extends BaseService
{
    const TYPE_RECORD = 1;
    const TYPE_TOTAL = 2;
    const TRANSACTION_FEE = 330;

    protected $userCompensationRepo;

    public function __construct(UserCompensationRepository $userCompensationRepo)
    {
        $this->userCompensationRepo = $userCompensationRepo;
    }

    public function generateCSV()
    {
        $fileIsStored = false;
        $compensations = $this->userCompensationRepo->getAllCompensations();

        if (!empty($compensations)) {
            $totalRecords = count($compensations);
            $csv_data = [];

            foreach ($compensations as $compensation) {
                $bankAccount = $compensation->user->bankAccount;
                $transaction_fee = self::TRANSACTION_FEE;

                // calculate the amount with transaction fee
                $handled_amount = (int)$compensation->amount - $transaction_fee;

                $csv_data[] = [
                    'service' => self::TYPE_RECORD,
                    'bank_code' => $bankAccount->bank_code,
                    'branch_code' => $bankAccount->branch_code,
                    'bank_account_type' => $bankAccount->bank_account_type,
                    'bank_account_number' => $bankAccount->bank_account_number,
                    'bank_account_name' => $bankAccount->bank_account_name,
                    'amount' => $handled_amount,
                    'transferred_by_who' => null
                ];
            }

            $totalAmount = array_sum(array_column($csv_data, 'amount'));

            // insert last record in csv for total amount and records
            $csv_data[] = [
                'service' => self::TYPE_TOTAL,
                'bank_code' => null,
                'branch_code' => null,
                'bank_account_type' => null,
                'bank_account_number' => null,
                'bank_account_name' => $totalRecords,
                'amount' => (int)$totalAmount,
                'transferred_by_who' => null
            ];

            $fileName = date('Ymd') . '-teacher_' . uniqid() . '.csv';
//            $fileIsStored = Excel::store(
//                new TransferExport($csv_data),
//                 $fileName,
//                'transfer',
//                \Maatwebsite\Excel\Excel::CSV
//            );

            $fileIsStored = $this->export_csv($csv_data, storage_path("app/public/transfer/"), $fileName,'SJIS-win');

            if ($fileIsStored) {

                //add log
                $log = UserCompensationsLogs::create([
                    'total_amount_exported' => (int)$totalAmount,
                    'file_path_name' => $fileName,
                    'status' => EStatus::APPROVED,
                    'note' => 'export thanh cong'
                ]);

                // update transfer status
                $transferIDs = $compensations->pluck('id')->all();
                $conditions = [
                    'status' => EStatus::APPROVED,
                    'log_id' => $log->id,
                    'note' => 'export thanh cong'
                ];

                $this->userCompensationRepo->updateStatusByIDs($transferIDs, $conditions);

            }

            return $fileName;
        }
        return false;
    }

    private function export_csv($data, $savePath, $fileName, $encoding = 'UTF-8', $header = []){
        if(!empty($data) && is_array($data)){

            $fileName = !empty($savePath) ? $savePath . $fileName : $fileName;

            // open csv file for writing
            $f = fopen($fileName, 'wb');

            if ($f === false) {
                die('Error opening the file ' . $fileName);
            }

            if(!empty($header)){
                foreach($header as $head){
                    $encodeHeader[] = mb_convert_encoding($head, $encoding, 'UTF-8');
                }

                fputcsv($f, $encodeHeader);
            }

            // write each row at a time to a file

            for($i = 0; $i < count($data); $i ++){
                $row = [];
                foreach($data[$i] as $value){
                    $row[] = mb_convert_encoding($value,$encoding,'UTF-8');
                }

                // $newArray = array_map(function ($vl) {
                //     return "\"$vl\"";
                // }, $row);
//                fputcsv($f, $row);
                $this->fputcsv2($f, $row);
            }

            // close the file
            fclose($f);

            return $fileName;
        }

        return false;
    }

    private function fputcsv2($fp, $fields, $delimiter = ',', $enclosure = '"', $mysql_null = false) {
        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');

        $output = array();
        foreach ($fields as $field) {
            if ($field === null && $mysql_null) {
                $output[] = 'NULL';
                continue;
            }

            // original
            // $output[] = preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field) ? (
            //     $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure
            // ) : $field;

            $output[] = preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field) ? (
                $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure
            ) : "\"{$field}\"";
        }

        fwrite($fp, join($delimiter, $output) . "\n");
    }

}
