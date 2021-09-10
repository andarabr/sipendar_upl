<?php

namespace App\DataTables;

use App\Models\NameList;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class NameListDataTable extends DataTable
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
            ->addColumn('action', 'namelist.action')
            ->addColumn('tanggal_upload', function($row)
            {
               $date = date("d F Y / H:m", strtotime($row->created_at));
               return $date;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\NameList $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(NameList $model)
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
                    ->setTableId('namelist-table')
                    //->columns($this->getColumns())
                    ->columns([
                        'DT_RowIndex'   => ['title' => 'No', 'searchable' => False, 'orderable' => False],
                        'name'          => ['title' => 'Nama'],
                        'tanggal_upload'    => ['title' => 'Tanggal Upload']
                    ])
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(2)
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
    // protected function getColumns()
    // {
    //     return [
    //         'DT_RowIndex',
    //         'name',
    //         'created_at'
    //     ];
    // }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'NameList_' . date('YmdHis');
    }
}
