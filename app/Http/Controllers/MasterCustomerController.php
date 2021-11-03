<?php

namespace App\Http\Controllers;

use App\Models\MasterCustomer;
use App\Models\NameList;
use App\Models\ViewLDBName;
use Illuminate\Http\Request;
use Spatie\ArrayToXml\ArrayToXml;
use App\DataTables\MasterCustomerDataTable;
use App\DataTables\NotFoundDataTable;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MasterCustomerImport;
use App\Exports\MasterCustomerExport;
use File;
use ZipArchive;

class MasterCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $masterCustomer = MasterCustomer::paginate(20);

        return view('mastercustomers.index', ['customers' => $masterCustomer]);
    }

    public function index2(MasterCustomerDataTable $dataTable)
    {
        return $dataTable->render('mastercustomers.index2');
    }

    public function importExcel(Request $request){
        set_time_limit(1000000);
        // $file = $request->file('file');
        // $file = is_array($file) ? $file[0] : $file;
        // dd($file);
        // $xx = $this->validate($request, [
		// 	'file' => 'required|mimes:csv,txt'
		// ]);

        // $mimes ='text/csv';
        // if(in_array($_FILES['file']['type'],$mimes)){
        //     // do something
        //   } else {
        //     die("Sorry, mime type not allowed");
        //   }

        $file = $request->file('file');

        $fileName = rand().$file->getClientOriginalName();

        $file->move('cust_files',$fileName);

        Excel::import(new MasterCustomerImport, public_path('/cust_files/'.$fileName));

        $request->session()->flash('message', "Data berhasil diimport");

        $delFiles = File::files(public_path('cust_files'));
        File::delete($delFiles);

        return redirect('/customers');
    }

    public function lookupDataIndividu()
    {
        $joinData= NameList::select('name_lists.*')
                                    ->join('master_customers', 'master_customers.name', '=', 'name_lists.name')
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

        //$joinData = ViewLDBName::where('cust_type', 'R')->paginate(10);

        return view('mastercustomers.individu', ['customers' => $joinData]);
    }

    public function lookupDataIndividuFilter(Request $request)
    {
        //dd($request);

        $fktp = $request['filterktp'];
        $fbd = $request['filterbirthdate'];
        $fbp = $request['filterbirthplace'];

        if ($fktp == 0 && $fbd == 0 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 0;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Individu by Nama';
        }
        else if ($fktp == 1 && $fbd == 0 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 0;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Individu by Nama & KTP';

            //dd($joinData);
        }
        else if ($fktp == 0 && $fbd == 1 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 1;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Individu by Nama & Tanggal Lahir';

        }
        else if ($fktp == 1 && $fbd == 1 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 1;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Individu by Nama, KTP & Tanggal Lahir';

        }

        if ($fktp == 0 && $fbd == 0 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 0;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Individu by Nama & Tempat Lahir';
        }
        else if ($fktp == 1 && $fbd == 0 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 0;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Individu by Nama, KTP & Tempat Lahir';

            //dd($joinData);
        }
        else if ($fktp == 0 && $fbd == 1 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 1;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Individu by Nama, Tanggal Lahir & Tempat Lahir';

        }
        else if ($fktp == 1 && $fbd == 1 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 1;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Individu by Nama, KTP, Tanggal Lahir & Tempat Lahir';

        }

        //NULL START
        else if ($fktp == 0 && $fbd == 0 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 0;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Individu by Nama | Tempat Lahir NULL';

        }
        else if ($fktp == 0 && $fbd == 2 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 0;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Individu by Nama | Tanggal Lahir NULL';

        }
        else if ($fktp == 0 && $fbd == 2 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 2;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Individu by Nama & Tempat Lahir | Tanggal Lahir NULL';

        }
        else if ($fktp == 0 && $fbd == 1 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 1;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Individu by Nama & Tanggal Lahir | Tempat Lahir NULL';


        }
        else if ($fktp == 0 && $fbd == 2 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.birthdate')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 2;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Individu by Nama | Tanggal Lahir & Tempat Lahir NULL';

        }
        else if ($fktp == 2 && $fbd == 0 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.id_num')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 0;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Individu by Nama | KTP NULL';

        }
        else if ($fktp == 2 && $fbd == 1 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.id_num')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 1;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Individu by Nama & Tanggal Lahir | KTP NULL';


        }
        else if ($fktp == 2 && $fbd == 1 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.id_num')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 1;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Individu by Nama, Tanggal Lahir & Tempat Lahir | KTP NULL';


        }
        else if ($fktp == 2 && $fbd == 1 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.id_num')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 1;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Individu by Nama & Tanggal Lahir | KTP & Tempat Lahir NULL';


        }
        else if ($fktp == 2 && $fbd == 2 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.id_num')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 2;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Individu by Nama & Tempat Lahir | KTP & Tanggal Lahir NULL';

        }
        else if ($fktp == 2 && $fbd == 2 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.id_num')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 2;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Individu by Nama | KTP & Tanggal Lahir NULL';

        }
        else if ($fktp == 2 && $fbd == 2 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.id_num')
                                    ->whereNull('name_lists.birthdate')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 2;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Individu by Nama | KTP, Tanggal Lahir & Tempat Lahir NULL';

        }
        else if ($fktp == 1 && $fbd == 2 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 2;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Individu by Nama & Tempat Lahir | Tanggal Lahir NULL';

        }
        else if ($fktp == 1 && $fbd == 2 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.birthdate')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 2;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Individu by Nama & KTP | Tanggal Lahir & Tempat Lahir NULL';

        }
        else if ($fktp == 1 && $fbd == 2 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 2;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Individu by Nama & KTP | Tanggal Lahir NULL';

        }
        else if ($fktp == 1 && $fbd == 1 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 1;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Individu by Nama, KTP, dan Tanggal Lahir | Tempat Lahir NULL';
        }

        $data = [
            'fktp' => $fktp,
            'fbd' => $fbd,
            'fbp' => $fbp,
            'title' => $title
        ];

        return view('mastercustomers.individufilter', ['customers' => $joinData], ['data' => $data]);
    }

    public function lookupDataKorporasi()
    {
        $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', 'master_customers.name', '=', 'name_lists.name')
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

        return view('mastercustomers.korporasi', ['customers' => $joinData]);
    }

    public function lookupDataKorporasiFilter(Request $request)
    {
        //dump($request->query('filterbirthplace'));

        $fktp = $request['filterktp'];
        $fbd = $request['filterbirthdate'];
        $fbp = $request['filterbirthplace'];

        if ($fktp == 0 && $fbd == 0 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 0;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Korporasi by Nama';
        }
        else if ($fktp == 1 && $fbd == 0 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 0;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Korporasi by Nama & KTP';

            //dd($joinData);
        }
        else if ($fktp == 0 && $fbd == 1 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 1;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Korporasi by Nama & Tanggal Lahir';

        }
        else if ($fktp == 1 && $fbd == 1 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 1;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Korporasi by Nama, KTP & Tanggal Lahir';

        }

        else if ($fktp == 0 && $fbd == 0 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 0;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Korporasi by Nama & Tempat Lahir';
        }
        else if ($fktp == 1 && $fbd == 0 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 0;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Korporasi by Nama, KTP & Tempat Lahir';

            //dd($joinData);
        }
        else if ($fktp == 0 && $fbd == 1 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 1;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Korporasi by Nama, Tanggal Lahir & Tempat Lahir';

        }
        else if ($fktp == 1 && $fbd == 1 && $fbp == 1) {
            //dump($fbp);
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 1;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Korporasi by Nama, KTP, Tanggal Lahir & Tempat Lahir';

        }

        //NULL START
        else if ($fktp == 0 && $fbd == 0 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 0;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Korporasi by Nama | Tempat Lahir NULL';

        }
        else if ($fktp == 0 && $fbd == 2 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 0;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Korporasi by Nama | Tanggal Lahir NULL';

        }
        else if ($fktp == 0 && $fbd == 2 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 2;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Korporasi by Nama & Tempat Lahir | Tanggal Lahir NULL';

        }
        else if ($fktp == 0 && $fbd == 1 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 1;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Korporasi by Nama & Tanggal Lahir | Tempat Lahir NULL';


        }
        else if ($fktp == 0 && $fbd == 2 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.birthdate')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 0;
            $fbd = 2;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Korporasi by Nama | Tanggal Lahir & Tempat Lahir NULL';

        }
        else if ($fktp == 2 && $fbd == 0 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.id_num')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 0;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Korporasi by Nama | KTP NULL';

        }
        else if ($fktp == 2 && $fbd == 1 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.id_num')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 1;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Korporasi by Nama & Tanggal Lahir | KTP NULL';


        }
        else if ($fktp == 2 && $fbd == 1 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.id_num')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 1;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Korporasi by Nama, Tanggal Lahir & Tempat Lahir | KTP NULL';


        }
        else if ($fktp == 2 && $fbd == 1 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.id_num')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 1;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Korporasi by Nama & Tanggal Lahir | KTP & Tempat Lahir NULL';


        }
        else if ($fktp == 2 && $fbd == 2 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.id_num')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 2;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Korporasi by Nama & Tempat Lahir | KTP & Tanggal Lahir NULL';

        }
        else if ($fktp == 2 && $fbd == 2 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.id_num')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 2;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Korporasi by Nama | KTP & Tanggal Lahir NULL';

        }
        else if ($fktp == 2 && $fbd == 2 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.id_num')
                                    ->whereNull('name_lists.birthdate')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 2;
            $fbd = 2;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Korporasi by Nama | KTP, Tanggal Lahir & Tempat Lahir NULL';

        }
        else if ($fktp == 1 && $fbd == 2 && $fbp == 1) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 2;
            $fbp = 1;
            $title = 'Lookup Data Proaktif Korporasi by Nama & Tempat Lahir | Tanggal Lahir NULL';

        }
        else if ($fktp == 1 && $fbd == 2 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.birthdate')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 2;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Korporasi by Nama & KTP | Tanggal Lahir & Tempat Lahir NULL';

        }
        else if ($fktp == 1 && $fbd == 2 && $fbp == 0) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 2;
            $fbp = 0;
            $title = 'Lookup Data Proaktif Korporasi by Nama & KTP | Tanggal Lahir NULL';

        }
        else if ($fktp == 1 && $fbd == 1 && $fbp == 2) {
            $joinData = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->paginate(10);

            $fktp = 1;
            $fbd = 1;
            $fbp = 2;
            $title = 'Lookup Data Proaktif Korporasi by Nama, KTP, dan Tanggal Lahir | Tempat Lahir NULL';
        }

        $data = [
            'fktp' => $fktp,
            'fbd' => $fbd,
            'fbp' => $fbp,
            'title' => $title
        ];

        return view('mastercustomers.korporasifilter', ['customers' => $joinData], ['data' => $data]);
    }

    public function xmlDownRekursif(MasterCustomer $customer){

        $customerData = MasterCustomer::where('id', $customer->id)->first();

        $customerDatas = MasterCustomer::all()->toArray();

        foreach ($customerDatas as $cust) {
            $phones['phone'][] = array (
                'contact_type' => $cust['contact_type'],
                'communication_type_code' => $cust['communication_type'],
                'country_prefix' => $cust['country_prefix'],
                'number' => $cust['phone_number'],
            );
        }

        foreach ($customerDatas as $cust1) {
            $rekenings['rekening'][] = array (
                'cif' => $cust1['cif'],
                'jenis_rekening_code' => $cust1['account_type'],
                'status_rekening_code' => $cust1['account_status'],
                'no_rekening' => $cust1['account_num'],
                'pjk_id' => '99',
            );
        }

        $array = [
            'proaktif' => [
                'jenis_watchlist' => 'INTERNAL WATCHLIST',
                // 'tindak_pidana_id' => '1',
                'sumber_informasi_khusus' => '3',
                'organisasi_id' => '1ac6efb0-04d9-447a-bde8-44d98621c080', //lookup organisasi_id bank shinhan indonesia
                'keterangan' => 'Internal Watchlist Organisasi',
                'individu' => [
                    'nama'=> $customerData['name'],
                    'negara_code' => $customerData['country_code'],
                    // 'alamat' => $customerData['id_address'],
                    'tempat_lahir' => $customerData['birthplace'],
                    'tanggal_lahir' => $customerData['birthdate'],
                    'addresses'=> [
                        'address' => [
                            'address_type' => $customerData['current_address_type'],
                            'address' => $customerData['current_address'],
                            'city' => $customerData['city'],
                            'country_code' => $customerData['current_country_code'],
                        ],
                    ],
                    'phones' => $phones,
                    'rekenings' => $rekenings,
                    'identifications' => [
                        'identification' => [
                            'type_code' => $customerData['id_type'],
                            'number' => $customerData['id_num'],
                            'issue_date' => $customerData['issue_date'],
                            'expiry_date' => $customerData['expiry_date'],
                            'issued_by' => $customerData['issued_by'],
                            'issued_country_code' => $customerData['issued_country_code'],
                        ],
                    ],
                ]
            ]
        ];

        $first = array( '12:00 AM', '1:00 AM', '2:00 AM', '3:00 AM', '4:00 AM', '5:00 AM', '6:00 AM', '7:00 AM', '8:00 AM', '9:00 AM', '10:00 AM', '11:00 AM' );

        $arrayToXml = new ArrayToXml($array);
        $arrayToXml->setDomProperties(['formatOutput' => true],['preserveWhiteSpace' => true]);
        $result = $arrayToXml->prettify()->toXml();

        $dom = new \DOMdocument('1.0');
        $dom->preserveWhiteSpace = true;
        $dom->formatOutput = true;
        $dom->loadXML($result);
        //$xml_pretty = $dom->saveXML();

        $custName = str_replace(' ', '', $customerData['name']);

        $file_name = 'INDIVIDU_'.strtoupper($custName).'_'.date('Ymdhis').'.xml';
        $dom->save($file_name);

        return response()->download($file_name)->deleteFileAfterSend(true);
    }

    public function xmlDownIndividu(Request $request, MasterCustomer $customer){

        //dd($request);

        $customerData = MasterCustomer::where('id', $request->id)->first();

        $customerDatas = MasterCustomer::all()->toArray();

        $array = [
            'proaktif' => [
                'jenis_watchlist' => $request['tipe_watchlist'],
                // 'tindak_pidana_id' => '1',
                'sumber_informasi_khusus' => $request['sumber_watchlist'],
                'organisasi_id' => '1ac6efb0-04d9-447a-bde8-44d98621c080', //lookup organisasi_id bank shinhan indonesia
                'keterangan' => 'Internal Watchlist Organisasi',
                'individu' => [
                    'nama'=> $customerData['name'],
                    'negara_code' => $customerData['country_code'],
                    // 'alamat' => $customerData['id_address'],
                    'tempat_lahir' => $customerData['birthplace'],
                    'tanggal_lahir' => $customerData['birthdate'],
                    'addresses'=> [
                        'address' => [
                            'address_type' => $customerData['current_address_type'],
                            'address' => $customerData['current_address'],
                            'city' => $customerData['city'],
                            'country_code' => $customerData['current_country_code'],
                        ],
                    ],
                    'phones' => [
                        'phone' => [
                            'contact_type' => $customerData['contact_type'],
                            'communication_type_code' => $customerData['communication_type'],
                            'country_prefix' => $customerData['country_prefix'],
                            'number' => $customerData['phone_number'],
                        ],
                    ],
                    'rekenings' => [
                        'cif' => $customerData['cif'],
                        'jenis_rekening_code' => $customerData['account_type'],
                        'status_rekening_code' => $customerData['account_status'],
                        'no_rekening' => $customerData['account_num'],
                        'pjk_id' => '99',
                        'atms' => [
                            'no_atm' => $customerData['card_num'],
                        ],
                    ],
                    'identifications' => [
                        'identification' => [
                            'type_code' => $customerData['id_type'],
                            'number' => $customerData['id_num'],
                            'issue_date' => $customerData['issue_date'],
                            'expiry_date' => $customerData['expiry_date'],
                            'issued_by' => $customerData['issued_by'],
                            'issued_country_code' => $customerData['issued_country_code'],
                        ],
                    ],
                ]
            ]
        ];


        $arrayToXml = new ArrayToXml($array);
        $arrayToXml->setDomProperties(['encoding' => 'UTF-8'],['formatOutput' => true],['preserveWhiteSpace' => true]);
        $result = $arrayToXml->prettify()->toXml();

        $dom = new \DOMdocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = true;
        $dom->formatOutput = true;
        $dom->loadXML($result);
        //$xml_pretty = $dom->saveXML();

        $custName = str_replace(' ', '', $customerData['name']);

        $file_name = 'INDIVIDU_'.strtoupper($custName).'_'.date('Ymdhis').'.xml';
        $dom->save($file_name);

        return response()->download($file_name)->deleteFileAfterSend(true);
    }

    public function xmlDownKorporasi(Request $request, MasterCustomer $customer){

        $customerData = MasterCustomer::where('id', $customer->id)->first();

        $customerDatas = MasterCustomer::all()->toArray();

        $array = [
            'proaktif' => [
                'jenis_watchlist' => $request['tipe_watchlist'],
                // 'tindak_pidana_id' => '1',
                'sumber_informasi_khusus' => $request['sumber_watchlist'],
                'organisasi_id' => '1ac6efb0-04d9-447a-bde8-44d98621c080', //lookup organisasi_id bank shinhan indonesia
                'keterangan' => 'Internal Watchlist Organisasi',
                'korporasi' => [
                    'nama'=> $customerData['name'],
                    'negara_code' => $customerData['country_code'],
                    'npwp' => $customerData['npwp'],
                    'no_izin_usaha' => $customerData['no_izin_usaha'],
                    'addresses'=> [
                        'address' => [
                            'address_type' => $customerData['current_address_type'],
                            'address' => $customerData['current_address'],
                            'city' => $customerData['city'],
                            'country_code' => $customerData['current_country_code'],
                            'zip' => $customerData['zip_code'],
                        ],
                    ],
                    'phones' => [
                        'contact_type' => $customerData['contact_type'],
                        'communication_type_code' => $customerData['communication_type'],
                        'country_prefix' => $customerData['country_prefix'],
                        'number' => $customerData['phone_number'],
                    ],
                    'rekenings' => [
                        'rekening' => [
                            'cif' => $customerData['cif'],
                            'jenis_rekening_code' => $customerData['account_type'],
                            'status_rekening_code' => $customerData['account_status'],
                            'no_rekening' => $customerData['account_num'],
                            'pjk_id' => '99',
                            'atms' => [
                                'no_atm' => $customerData['card_num'],
                            ],
                        ],
                    ],
                ]
            ]
        ];


        $arrayToXml = new ArrayToXml($array);
        $arrayToXml->setDomProperties(['encoding' => 'UTF-8'],['formatOutput' => true],['preserveWhiteSpace' => true]);
        $result = $arrayToXml->prettify()->toXml();

        $dom = new \DOMdocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = true;
        $dom->formatOutput = true;
        $dom->loadXML($result);
        //$xml_pretty = $dom->saveXML();

        $custName = str_replace(' ', '', $customerData['name']);

        $file_name = 'KORPORASI_'.strtoupper($custName).'_'.date('Ymdhis').'.xml';
        $dom->save($file_name);

        return response()->download($file_name)->deleteFileAfterSend(true);
    }

    public function xmlZipIndividu($data, $id){
        //dd($data);
        $customerData = MasterCustomer::where('id', $id)->first();


        $customerDatas = MasterCustomer::all()->toArray();

        $array = [
            'proaktif' => [
                'jenis_watchlist' => $data['0'],
                // 'tindak_pidana_id' => '1',
                'sumber_informasi_khusus' => $data['1'],
                'organisasi_id' => '1ac6efb0-04d9-447a-bde8-44d98621c080', //lookup organisasi_id bank shinhan indonesia
                'keterangan' => 'Internal Watchlist Organisasi',
                'individu' => [
                    'nama'=> $customerData['name'],
                    'negara_code' => $customerData['country_code'],
                    // 'alamat' => $customerData['id_address'],
                    'tempat_lahir' => $customerData['birthplace'],
                    'tanggal_lahir' => $customerData['birthdate'],
                    'addresses'=> [
                        'address' => [
                            'address_type' => $customerData['current_address_type'],
                            'address' => $customerData['current_address'],
                            'city' => $customerData['city'],
                            'country_code' => $customerData['current_country_code'],
                        ],
                    ],
                    'phones' => [
                        'phone' => [
                            'contact_type' => $customerData['contact_type'],
                            'communication_type_code' => $customerData['communication_type'],
                            'country_prefix' => $customerData['country_prefix'],
                            'number' => $customerData['phone_number'],
                        ],
                    ],
                    'rekenings' => [
                        'cif' => $customerData['cif'],
                        'jenis_rekening_code' => $customerData['account_type'],
                        'status_rekening_code' => $customerData['account_status'],
                        'no_rekening' => $customerData['account_num'],
                        'pjk_id' => '99',
                        'atms' => [
                            'no_atm' => $customerData['card_num'],
                        ],
                    ],
                    'identifications' => [
                        'identification' => [
                            'type_code' => $customerData['id_type'],
                            'number' => $customerData['id_num'],
                            'issue_date' => $customerData['issue_date'],
                            'expiry_date' => $customerData['expiry_date'],
                            'issued_by' => $customerData['issued_by'],
                            'issued_country_code' => $customerData['issued_country_code'],
                        ],
                    ],
                ]
            ]
        ];


        $arrayToXml = new ArrayToXml($array);
        $arrayToXml->setDomProperties(['encoding' => 'UTF-8'],['formatOutput' => true],['preserveWhiteSpace' => true]);
        $result = $arrayToXml->prettify()->toXml();

        $dom = new \DOMdocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = true;
        $dom->formatOutput = true;
        $dom->loadXML($result);

        $custName = str_replace(' ', '', $customerData['name']);

        $file_name = 'INDIVIDU_'.strtoupper($custName).'_'.date('Ymdhis').'.xml';
        $dom->save(public_path('/xml_data/proaktif_individu/'.$file_name));
    }

    public function xmlZipKorporasi($data, $id){

        $customerData = MasterCustomer::where('id', $id)->first();

        $customerDatas = MasterCustomer::all()->toArray();

        $array = [
            'proaktif' => [
                'jenis_watchlist' => $data['0'],
                // 'tindak_pidana_id' => '1',
                'sumber_informasi_khusus' => $data['1'],
                'organisasi_id' => '1ac6efb0-04d9-447a-bde8-44d98621c080', //lookup organisasi_id bank shinhan indonesia
                'keterangan' => 'Internal Watchlist Organisasi',
                'korporasi' => [
                    'nama'=> $customerData['name'],
                    'negara_code' => $customerData['country_code'],
                    'npwp' => $customerData['npwp'],
                    'no_izin_usaha' => $customerData['no_izin_usaha'],
                    'addresses'=> [
                        'address' => [
                            'address_type' => $customerData['current_address_type'],
                            'address' => $customerData['current_address'],
                            'city' => $customerData['city'],
                            'country_code' => $customerData['current_country_code'],
                            'zip' => $customerData['zip_code'],
                        ],
                    ],
                    'phones' => [
                        'contact_type' => $customerData['contact_type'],
                        'communication_type_code' => $customerData['communication_type'],
                        'country_prefix' => $customerData['country_prefix'],
                        'number' => $customerData['phone_number'],
                    ],
                    'rekenings' => [
                        'rekening' => [
                            'cif' => $customerData['cif'],
                            'jenis_rekening_code' => $customerData['account_type'],
                            'status_rekening_code' => $customerData['account_status'],
                            'no_rekening' => $customerData['account_num'],
                            'pjk_id' => '99',
                            'atms' => [
                                'no_atm' => $customerData['card_num'],
                            ],
                        ],
                    ],
                ]
            ]
        ];


        $arrayToXml = new ArrayToXml($array);
        $arrayToXml->setDomProperties(['encoding' => 'UTF-8'],['formatOutput' => true],['preserveWhiteSpace' => true]);
        $result = $arrayToXml->prettify()->toXml();

        $dom = new \DOMdocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = true;
        $dom->formatOutput = true;
        $dom->loadXML($result);

        $custName = str_replace(' ', '', $customerData['name']);

        $file_name = 'KORPORASI_'.strtoupper($custName).'_'.date('Ymdhis').'.xml';
        $dom->save(public_path('/xml_data/proaktif_korporasi/'.$file_name));
    }

    public function xmlAll(Request $request, $cust){
        //dd($request);
        set_time_limit(1000000);
        //dump($request);
        $fktp = $request['filterktp'];
        $fbd = $request['filterbirthdate'];
        $fbp = $request['filterbirthplace'];

        if ($fktp == 0 && $fbd == 0 && $fbp == 0) {
            $customers = MasterCustomer::select('master_customers.*')
                                    ->join('name_lists', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 0;
            $fbd = 0;
            $fbp = 0;
        }
        else if ($fktp == 1 && $fbd == 0 && $fbp == 0) {
            $customers = MasterCustomer::select('master_customers.*')
                                    ->join('name_lists', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 1;
            $fbd = 0;
            $fbp = 0;

            //dd($joinData);
        }
        else if ($fktp == 0 && $fbd == 1 && $fbp == 0) {
            $customers = MasterCustomer::select('master_customers.*')
                                    ->join('name_lists', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 0;
            $fbd = 1;
            $fbp = 0;

        }
        else if ($fktp == 1 && $fbd == 1 && $fbp == 0) {
            $customers = MasterCustomer::select('master_customers.*')
                                    ->join('name_lists', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 1;
            $fbd = 1;
            $fbp = 0;

        }

        else if ($fktp == 0 && $fbd == 0 && $fbp == 1) {
            $customers = MasterCustomer::select('master_customers.*')
                                    ->join('name_lists', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 0;
            $fbd = 0;
            $fbp = 1;
        }
        else if ($fktp == 1 && $fbd == 0 && $fbp == 1) {
            $customers = MasterCustomer::select('master_customers.*')
                                    ->join('name_lists', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 1;
            $fbd = 0;
            $fbp = 1;

            //dd($joinData);
        }
        else if ($fktp == 0 && $fbd == 1 && $fbp == 1) {
            $customers = MasterCustomer::select('master_customers.*')
                                    ->join('name_lists', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 0;
            $fbd = 1;
            $fbp = 1;

        }
        else if ($fktp == 1 && $fbd == 1 && $fbp == 1) {
            $customers = MasterCustomer::select('master_customers.*')
                                    ->join('name_lists', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 1;
            $fbd = 1;
            $fbp = 1;

        }

        //NULL START

        else if ($fktp == 0 && $fbd == 0 && $fbp == 2) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 0;
            $fbd = 0;
            $fbp = 2;
        }
        else if ($fktp == 0 && $fbd == 2 && $fbp == 0) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 0;
            $fbd = 0;
            $fbp = 2;
        }
        else if ($fktp == 0 && $fbd == 2 && $fbp == 1) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 0;
            $fbd = 2;
            $fbp = 1;
        }
        else if ($fktp == 0 && $fbd == 1 && $fbp == 2) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 0;
            $fbd = 1;
            $fbp = 2;

        }
        else if ($fktp == 0 && $fbd == 2 && $fbp == 2) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.birthdate')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 0;
            $fbd = 2;
            $fbp = 2;
        }
        else if ($fktp == 2 && $fbd == 0 && $fbp == 0) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.id_num')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 2;
            $fbd = 0;
            $fbp = 0;
        }
        else if ($fktp == 2 && $fbd == 1 && $fbp == 0) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.id_num')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 2;
            $fbd = 1;
            $fbp = 0;

        }
        else if ($fktp == 2 && $fbd == 1 && $fbp == 1) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.id_num')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 2;
            $fbd = 1;
            $fbp = 1;

        }
        else if ($fktp == 2 && $fbd == 1 && $fbp == 2) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.id_num')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 2;
            $fbd = 1;
            $fbp = 2;

        }
        else if ($fktp == 2 && $fbd == 2 && $fbp == 1) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.id_num')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 2;
            $fbd = 2;
            $fbp = 1;
        }
        else if ($fktp == 2 && $fbd == 2 && $fbp == 0) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.id_num')
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 2;
            $fbd = 2;
            $fbp = 0;
        }
        else if ($fktp == 2 && $fbd == 2 && $fbp == 2) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.id_num')
                                    ->whereNull('name_lists.birthdate')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 2;
            $fbd = 2;
            $fbp = 2;
        }
        else if ($fktp == 1 && $fbd == 2 && $fbp == 1) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 1;
            $fbd = 2;
            $fbp = 1;
        }
        else if ($fktp == 1 && $fbd == 2 && $fbp == 2) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.birthdate')
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 1;
            $fbd = 2;
            $fbp = 2;
        }
        else if ($fktp == 1 && $fbd == 2 && $fbp == 0) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.birthdate')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 1;
            $fbd = 2;
            $fbp = 0;
        }
        else if ($fktp == 1 && $fbd == 1 && $fbp == 2) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
                                    {
                                        $join->on('master_customers.name', '=', 'name_lists.name');
                                        $join->on('master_customers.id_num', '=', 'name_lists.id_num');
                                        $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');

                                    })
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->whereNull('name_lists.birthplace')
                                    ->orderby('master_customers.name')
                                    ->get();

            $fktp = 1;
            $fbd = 1;
            $fbp = 2;

        }

        // $customers = MasterCustomer::select('master_customers.*')
        //                             ->join('name_lists', 'master_customers.name', '=', 'name_lists.name')
        //                             ->where('master_customers.cust_type', '=', $cust)
        //                             ->get();

        $watchlistData = array(
            $request['tipe_watchlist'],
            $request['sumber_watchlist']
        );

        //dd($customers->count());

        foreach ($customers as $cust_single) {
            if ($cust == 'R') {
                $this->xmlZipIndividu($watchlistData, $cust_single['id']);
            }
            else if ($cust == 'C'){
                $this->xmlZipKorporasi($watchlistData, $cust_single['id']);
            }
        }
        $this->downloadZip($cust);

        if ($cust == 'R') {
            $files = File::files(public_path('xml_data/proaktif_individu'));
            File::delete($files);
        }
        else if ($cust == 'C'){
            $files = File::files(public_path('xml_data/proaktif_korporasi'));
            File::delete($files);
        }

        return response()->download(session("zname"))->deleteFileAfterSend(true);

    }

    public function downloadZip($cust)
    {
        $zip = new ZipArchive;

        if ($cust == 'R') {
            $fileName = 'PROAKTIF_INDIVIDU'.'_'.date('Ymdhis').'.zip';
        }
        else if ($cust == 'C') {
            $fileName = 'PROAKTIF_KORPORASI'.'_'.date('Ymdhis').'.zip';
        }

        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
        {
            if ($cust == 'R') {
                $files = File::files(public_path('xml_data/proaktif_individu'));
            }
            else if ($cust == 'C') {
                $files = File::files(public_path('xml_data/proaktif_korporasi'));
            }

            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }

            $zip->close();
        }

        session(["zname"=>$fileName]);
    }

    public function destroy()
    {
        MasterCustomer::truncate();

        session()->flash('message', "Data berhasil dihapus");

        return redirect('/customers');
    }

    public function dataNotFound(NotFoundDataTable $dataTables){
        // $customers = MasterCustomer::select('name_lists.name', 'name_lists.id_num', 'name_lists.birthdate', 'name_lists.birthplace')
        // ->rightJoin('name_lists', function($join)
        // {
        //     $join->on('master_customers.name', '=', 'name_lists.name');
        //     $join->on('master_customers.id_num', '=', 'name_lists.id_num');
        //     $join->on('master_customers.birthdate', '=', 'name_lists.birthdate');
        //     $join->on('master_customers.birthplace', '=', 'name_lists.birthplace');

        // })
        // ->whereNull('master_customers.name')
        // ->orderby('master_customers.name')
        // ->get();

        // // return view('mastercustomers.datanotfound', ['customers' => $customers]);
        // return $dataTables->query();
        // // return $dataTables->render('mastercustomers.datanotfound');

        //return Excel::download(new MasterCustomerExport, 'users.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        //return (new MasterCustomerExport)->download('invoices.csv', \Maatwebsite\Excel\Excel::CSV);
        return (new MasterCustomerExport)->download('users.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        //Excel::store(new MasterCustomerExport(2018), 'upload.csv');
    }

    public function downloadFormat(){
        return response()->download('format_upload_master_data.xlsx');
    }
}
