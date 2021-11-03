<?php

namespace App\Imports;

use App\Models\Namelist;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class NamesImport implements ToModel, WithHeadingRow, WithCustomCsvSettings
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Namelist([
            'periode' => $row['periode'],
            'list_id' => $row['id'],
            'kode_watchlist' => $row['kode_watchlist'],
            'jenis_pelaku' => $row['jenis_pelaku'],
            'name' => strtoupper($row['name']),
            'id_num' => $row['id_num'],
            'birthplace' => $row['birthplace'],
            'birthdate' => $row['birthdate'],
        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ";"
        ];
    }
}
