<?php

namespace App\Imports;

use App\Models\Namelist;
use Maatwebsite\Excel\Concerns\ToModel;

class NamesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Namelist([
            'name' => $row[0],
        ]);
    }
}
