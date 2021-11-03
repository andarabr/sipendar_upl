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

class WatchlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function watchlistIndividu(Request $request, NameList $nameList){

        $customerData = NameList::where('id', $request->id)->first();

        // for ($i=0; $i < 4; $i++) {
        //     $identitasx[][] = array (
        //             'jenis_identitas' => 'KTPA',
        //             'no_identitas' => $customerData['id_num'],
        //     );
        // }

        //dd($identitasx);

        //dd($data);

        $array = [
            'watchlist' => [
                'periode' => $customerData['periode'],
                'id' => $customerData['list_id'],
                'kode_watchlist' => $customerData['kode_watchlist'],
                'jenis_pelaku' => $customerData['jenis_pelaku'],
                'nama_asli' => $customerData['name'],
                'parameter_pencarian_nama' => $customerData['name'],
                'identitas' => [
                    'jenis_identitas' => 'KTP',
                    'no_identitas' => $customerData['id_num'],
                ],
                'tempat_lahir' => $customerData['birthplace'],
                'tanggal_lahir' => $customerData['birthdate'],
            ]
        ];

        //dd($array);

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

    public function watchlistKorporasi(Request $request, NameList $nameList){

        $customerData = NameList::where('id', $request->id)->first();

        // for ($i=0; $i < 4; $i++) {
        //     $identitasx[][] = array (
        //             'jenis_identitas' => 'KTPA',
        //             'no_identitas' => $customerData['id_num'],
        //     );
        // }

        //dd($identitasx);

        //dd($data);

        $array = [
            'watchlist' => [
                'periode' => $customerData['periode'],
                'id' => $customerData['list_id'],
                'kode_watchlist' => $customerData['kode_watchlist'],
                'jenis_pelaku' => $customerData['jenis_pelaku'],
                'nama_asli' => $customerData['name'],
                'parameter_pencarian_nama' => $customerData['name'],
                'identitas' => [
                    'jenis_identitas' => 'KTP',
                    'no_identitas' => $customerData['id_num'],
                ],
                'tempat_lahir' => $customerData['birthplace'],
                'tanggal_lahir' => $customerData['birthdate'],
            ]
        ];

        //dd($array);

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

    public function xmlAll(Request $request, $cust){
        //dd($request);
        set_time_limit(1000000);
        //dump($request);
        $fktp = $request['filterktp'];
        $fbd = $request['filterbirthdate'];
        $fbp = $request['filterbirthplace'];

        if ($fktp == 0 && $fbd == 0 && $fbp == 0) {
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
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
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
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
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
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
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
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
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
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
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
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
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
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
            $customers = NameList::select('name_lists.*')
                                    ->join('master_customers', function($join)
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

        //NULL BEGIN

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

        //dd($customers->count());

        foreach ($customers as $cust_single) {
            if ($cust == 'R') {
                $this->xmlZipIndividuWatchlist($cust_single['id']);
            }
            else if ($cust == 'C'){
                $this->xmlZipKorporasiWatchlist($cust_single['id']);
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
            $fileName = 'WATCHLIST_INDIVIDU'.'_'.date('Ymdhis').'.zip';
        }
        else if ($cust == 'C') {
            $fileName = 'WATCHLIST_KORPORASI'.'_'.date('Ymdhis').'.zip';
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


    public function xmlZipIndividuWatchlist($id){
        //dump($id);
        $customerData = NameList::where('id', $id)->first();
        //dd($customerData['periode']);

        $array = [
            'watchlist' => [
                'periode' => $customerData['periode'],
                'id' => $customerData['list_id'],
                'kode_watchlist' => $customerData['kode_watchlist'],
                'jenis_pelaku' => $customerData['jenis_pelaku'],
                'nama_asli' => $customerData['name'],
                'parameter_pencarian_nama' => $customerData['name'],
                'identitas' => [
                    'jenis_identitas' => 'KTP',
                    'no_identitas' => $customerData['id_num'],
                ],
                'tempat_lahir' => $customerData['birthplace'],
                'tanggal_lahir' => $customerData['birthdate'],
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

    public function xmlZipKorporasiWatchlist($id){
        //dump($id);
        $customerData = NameList::where('id', $id)->first();
        //dd($customerData['periode']);

        $array = [
            'watchlist' => [
                'periode' => $customerData['periode'],
                'id' => $customerData['list_id'],
                'kode_watchlist' => $customerData['kode_watchlist'],
                'jenis_pelaku' => $customerData['jenis_pelaku'],
                'nama_asli' => $customerData['name'],
                'parameter_pencarian_nama' => $customerData['name'],
                'identitas' => [
                    'jenis_identitas' => 'KTP',
                    'no_identitas' => $customerData['id_num'],
                ],
                'tempat_lahir' => $customerData['birthplace'],
                'tanggal_lahir' => $customerData['birthdate'],
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
}
