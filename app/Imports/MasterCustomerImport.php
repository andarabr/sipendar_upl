<?php

namespace App\Imports;

use App\Models\MasterCustomer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class MasterCustomerImport implements ToModel, WithHeadingRow, WithCustomCsvSettings, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //dd($row);

        return new MasterCustomer([
            'cust_type' => $row['cust_type'],
            'name' => strtoupper($row['name']),
            'country_code' => $row['country_code'],
            'birthplace' => $row['birthplace'],
            'birthdate' => $row['birthdate'],
            'npwp' => $row['npwp'],
            'no_izin_usaha' => $row['no_izin_usaha'],
            'current_address_type' => $row['current_address_type'],
            'current_address' => $row['current_address'],
            'city' => $row['city'],
            'current_country_code' => $row['current_country_code'],
            'zip_code' => $row['zip_code'],
            'contact_type' => $row['contact_type'],
            'communication_type' => $row['communication_type'],
            'country_prefix' => $row['country_prefix'],
            'phone_number' => $row['phone_number'],
            'cif' => $row['cif'],
            'account_type' => $row['account_type'],
            'account_status' => $row['account_status'],
            'account_num' => $row['account_num'],
            'pjk_id' => $row['pjk_id'],
            'card_num' => $row['card_num'],
            'id_type' => $row['id_type'],
            'id_num' => $row['id_num'],
            'issue_date' => $row['issue_date'],
            'expiry_date' => $row['expiry_date'],
            'issued_by' => $row['issued_by'],
            'issued_country_code' => $row['issued_country_code'],

        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ","
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

}
