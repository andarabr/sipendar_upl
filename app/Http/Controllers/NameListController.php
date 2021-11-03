<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NameList;
use App\Models\MasterCustomer;
use App\Imports\NamesImport;
use App\DataTables\NameListDataTable;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\ArrayToXml\ArrayToXml;
use File;

class NameListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $nameList = NameList::paginate(20);

        return view('namelists.index', ['names' => $nameList]);
    }

    public function index2(NameListDataTable $dataTable)
    {
        return $dataTable->render('namelists.index2');
    }

    // public function json(){
    //     return Datatables::of(NameList::all())->make(true);
    // }

    public function xmlShow(NameList $namelist){
        $rawData = Namelist::where('id', $namelist->id)->first();

        $xmlData = [
            'data' => [
                'name' => $rawData['name'],
                'upload_date' => $rawData['created_at']
            ],
            'test' => [
                'pekerjaan' => 'Wiraswasta',
                'pendidikan' => 'S1/Sederajat'
            ]
        ];

        $xml = response()->xml($xmlData,
        $status = 200,
        $headers = [],
        $xmlRoot = 'root',
        $encoding = null);

        dd($xml);


    }

    public function xmlTest(){

        $customerData = MasterCustomer::all()->toArray();

        $array = [
            'proaktif' => [
                'jenis_watchlist' => 'INTERNAL WATCHLIST',
                'tindak_pidana_id' => '1',
                'sumber_informasi_khusus' => '3',
                'organisasi_id' => 'ddasda-adsdas-sdadas-asdas',
                'keterangan' => 'Internal Watchlist Organisasi',
                'individu' => [
                    'nama'=> $customerData['0']['name'],
                    'negara_code' => $customerData['0']['country_code'],
                    'alamat' => $customerData['0']['id_address'],
                    'tempat_lahir' => $customerData['0']['birthplace'],
                    'tanggal_lahir' => $customerData['0']['birthdate'],
                    'addresses'=> [
                        'address' => [
                            'address_type' => $customerData['0']['current_address_type'],
                            'address' => $customerData['0']['current_address'],
                            'city' => $customerData['0']['city'],
                            'country_code' => $customerData['0']['current_country_code'],
                        ],
                    ],
                    'phones' => [
                        'phone' => [
                            'contact_type' => $customerData['0']['contact_type'],
                            'communication_type_code' => $customerData['0']['communication_type'],
                            'country_prefix' => $customerData['0']['country_prefix'],
                            'number' => $customerData['0']['phone_number'],
                        ],
                    ],
                    'rekenings' => [
                        'cif' => $customerData['0']['cif'],
                        'jenis_rekening_code' => $customerData['0']['account_type'],
                        'status_rekening_code' => $customerData['0']['account_status'],
                        'no_rekening' => $customerData['0']['account_num'],
                        'pjk_id' => '99',
                        'atms' => [
                            'no_atm' => $customerData['0']['card_num'],
                        ],
                    ],
                    'identifications' => [
                        'identification' => [
                            'type_code' => $customerData['0']['id_type'],
                            'number' => $customerData['0']['id_num'],
                            'issue_date' => $customerData['0']['issue_date'],
                            'expiry_date' => $customerData['0']['expiry_date'],
                            'issued_by' => $customerData['0']['issued_by'],
                            'issued_country_code' => $customerData['0']['issued_country_code'],
                        ],
                    ],
                ]
            ]
        ];


        $arrayToXml = new ArrayToXml($array);
        $arrayToXml->setDomProperties(['formatOutput' => true],['preserveWhiteSpace' => true]);
        $result = $arrayToXml->prettify()->toXml();

        $dom = new \DOMdocument('1.0','UTF-8');
        $dom->preserveWhiteSpace = true;
        $dom->formatOutput = true;
        $dom->loadXML($result);
        //$xml_pretty = $dom->saveXML();

        $custName = str_replace(' ', '', $customerData['0']['name']);

        $file_name = 'INDIVIDU_'.strtoupper($custName).'_'.date('Ymdhis').'.xml';
        $dom->save($file_name);

        return response()->download($file_name)->deleteFileAfterSend(true);


        //return 'test.xml';

        //dd($xml_pretty);

        //$result = ArrayToXml::convert($array, [], true, 'UTF-8', '1.0', []);

        // return response()->xml($array,
        // $status = 200,
        // $headers = [],
        // $xmlRoot = 'root',
        // $encoding = null);

    }


    public function importExcel(Request $request){
        // $this->validate($request, [
		// 	'file' => 'required|mimes:csv,xls,xlsx'
		// ]);

        $file = $request->file('file');

        $fileName = rand().$file->getClientOriginalName();

        $file->move('name_files',$fileName);

        Excel::import(new NamesImport, public_path('/name_files/'.$fileName));

        $request->session()->flash('message', "Data berhasil diimport");

        $delFiles = File::files(public_path('name_files'));
        File::delete($delFiles);

        return redirect('/index2');
    }

    public function destroy()
    {
        NameList::truncate();

        session()->flash('message', "Data berhasil dihapus");

        return redirect('/index2');
    }


    public function downloadFormat(){
        return response()->download('format_upload_lists_ppatk.xlsx');
    }
}
