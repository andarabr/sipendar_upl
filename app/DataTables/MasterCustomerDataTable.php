<?php

namespace App\DataTables;

use App\Models\MasterCustomer;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MasterCustomerDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="/xml" class="edit btn btn-info btn-sm">XML</a>';
                return $btn;
            })
            ->addColumn('tanggal_upload', function($row)
            {
               $date = date("d F Y / H:m", strtotime($row->created_at));
               return $date;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\MasterCustomer $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(MasterCustomer $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('mastercustomer-table')
                    ->columns([
                        'DT_RowIndex'   => ['title' => 'No', 'searchable' => False, 'orderable' => False],
                        'name'          => ['title' => 'Nama'],
                        'id_num'        => ['title' => 'KTP'],
                        'npwp'          => ['title' => 'NPWP'],
                        'birthplace'    => ['title' => 'Tempat Lahir'],
                        'birthdate'     => ['title' => 'Tanggal Lahir'],
                        'current_address'          => ['title' => 'Alamat', 'autoWidth' => 'false'],
                        'city'          => ['title' => 'Kota'],
                        'current_country_code'          => ['title' => 'Kode Negara'],
                        'zip_code'          => ['title' => 'Kode Pos'],
                        'phone_number'          => ['title' => 'No HP'],
                        'cif'          => ['title' => 'CIF'],
                        'account_num'          => ['title' => 'No Rekening'],
                        'account_type'          => ['title' => 'Tipe Rekening'],
                        'account_status'          => ['title' => 'Status Rekening'],
                        'tanggal_upload'=> ['title' => 'Tanggal Upload'],
                      


                        // 'action' => ['title' => 'Action', 'searchable' => False, 'orderable' => False]
                    ])
                    ->minifiedAjax()
                    //->scrollX(true)
                    ->autoWidth(false)
                    //->dom('Bfrtip')
                    ->orderBy(1, 'asc')
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    // protected function getColumns()
    // {
    //     return [
    //         Column::computed('action')
    //               ->exportable(false)
    //               ->printable(false)
    //               ->width(60)
    //               ->addClass('text-center'),
    //         Column::make('id'),
    //         Column::make('add your columns'),
    //         Column::make('created_at'),
    //         Column::make('updated_at'),
    //     ];
    // }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'MasterCustomer_' . date('YmdHis');
    }
}
