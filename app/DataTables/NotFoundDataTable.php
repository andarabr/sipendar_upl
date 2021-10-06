<?php

namespace App\DataTables;

use App\Models\MasterCustomer;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class NotFoundDataTable extends DataTable
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
            ->query($query)
            ->addColumn('action', 'notfound.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\NotFound $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $customers = MasterCustomer::select('name_lists.name', 'name_lists.id_num', 'name_lists.birthdate', 'name_lists.birthplace')
                                    ->rightJoin('name_lists', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->whereNull('master_customers.name')
                                    ->orderby('master_customers.name')
                                    ->get();


        return $this->applyScopes($customers);
        // return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('notfound-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
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
    protected function getColumns()
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('id'),
            Column::make('add your columns'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'NotFound_' . date('YmdHis');
    }
}
