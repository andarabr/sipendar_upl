<?php

namespace App\Http\Controllers;

use App\Models\MasterCustomer;
use App\Models\ViewLDBName;
use Illuminate\Http\Request;
use Spatie\ArrayToXml\ArrayToXml;
use App\DataTables\MasterCustomerDataTable;
use App\DataTables\NotFoundDataTable;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MasterCustomerImport;
use File;
use ZipArchive;

class ProaktifController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $masterCustomer = MasterCustomer::get();
        $masterCustomerCount = $masterCustomer->count();

        return $masterCustomer->count();
    }

    
}
