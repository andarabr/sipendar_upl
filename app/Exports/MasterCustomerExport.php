<?php

namespace App\Exports;

use App\Models\MasterCustomer;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MasterCustomerExport extends DefaultValueBinder implements FromQuery, WithHeadings, WithColumnFormatting, WithCustomValueBinder, ShouldAutoSize
{
    use Exportable;

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function columnFormats(): array
    {
        return [
            'B' => "0000",
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return MasterCustomer::select('name_lists.name', 'name_lists.id_num', 'name_lists.birthdate', 'name_lists.birthplace')
        ->rightJoin('name_lists', function($join)
        {
            $join->on('master_customers.name', '=', 'name_lists.name');
            $join->on('master_customers.id_num', '=', 'name_lists.id_num');
            $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');
            $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

        })
        ->whereNull('master_customers.name')
        ->orderby('master_customers.name');
    }

    public function map($namelist): array
    {
        dd($namelist);
        return [
            $namelist->name,
            $namelist-> id_num,
            $namelist->birthdate,
            $namelist->birthplace,
        ];
    }


    public function headings(): array
    {
        return [
            'Nama',
            'No Identitas',
            'Tanggal Lahir',
            'Tempat Lahir',
        ];
    }
}
