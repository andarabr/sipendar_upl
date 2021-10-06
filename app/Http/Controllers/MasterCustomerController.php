<?php

namespace App\Http\Controllers;

use App\Models\MasterCustomer;
use Illuminate\Http\Request;
use Spatie\ArrayToXml\ArrayToXml;
use File;
use ZipArchive;

class MasterCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $masterCustomer = MasterCustomer::paginate(20);

        return view('mastercustomers.index', ['customers' => $masterCustomer]);
    }

    public function lookupDataIndividu()
    {
        $joinData = MasterCustomer::select('master_customers.*')
                                    ->join('name_lists', 'master_customers.name', '=', 'name_lists.name')
                                    ->where('master_customers.cust_type', '=', 'R')
                                    ->paginate(10);
        
        return view('mastercustomers.individu', ['customers' => $joinData]);
    }

    public function lookupDataKorporasi()
    {
        $joinData = MasterCustomer::select('master_customers.*')
                                    ->join('name_lists', 'master_customers.name', '=', 'name_lists.name')
                                    ->where('master_customers.cust_type', '=', 'C')
                                    ->paginate(10);

        return view('mastercustomers.korporasi', ['customers' => $joinData]);
    }

    public function xmlDownIndividu(MasterCustomer $customer){

        $customerData = MasterCustomer::where('id', $customer->id)->first();

        $customerDatas = MasterCustomer::all()->toArray();

        $array = [
            'proaktif' => [
                'jenis_watchlist' => 'INTERNAL WATCHLIST',
                'tindak_pidana_id' => '1',
                'sumber_informasi_khusus' => '3',
                'organisasi_id' => 'ddasda-adsdas-sdadas-asdas',
                'keterangan' => 'Internal Watchlist Organisasi',
                'individu' => [
                    'nama'=> $customerData['name'],
                    'negara_code' => $customerData['country_code'],
                    'alamat' => $customerData['id_address'],
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
                        'pjk_id' => $customerData['pjk_id'],
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

    public function xmlDownKorporasi(MasterCustomer $customer){

        $customerData = MasterCustomer::where('id', $customer->id)->first();

        $customerDatas = MasterCustomer::all()->toArray();

        $array = [
            'proaktif' => [
                'jenis_watchlist' => 'INTERNAL WATCHLIST',
                'tindak_pidana_id' => '1',
                'sumber_informasi_khusus' => '3',
                'organisasi_id' => 'ddasda-adsdas-sdadas-asdas',
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
                            'pjk_id' => $customerData['pjk_id'],
                            'atms' => [
                                'no_atm' => $customerData['card_num'],
                            ],
                        ],
                    ],
                ]
            ]
        ];


        $arrayToXml = new ArrayToXml($array);
        $arrayToXml->setDomProperties(['formatOutput' => true],['preserveWhiteSpace' => true]);
        $result = $arrayToXml->prettify()->toXml();

        $dom = new \DOMdocument('1.0');
        $dom->preserveWhiteSpace = true;
        $dom->formatOutput = true;
        $dom->loadXML($result);
        //$xml_pretty = $dom->saveXML();

        $custName = str_replace(' ', '', $customerData['name']);

        $file_name = 'KORPORASI_'.strtoupper($custName).'_'.date('Ymdhis').'.xml';
        $dom->save($file_name);

        return response()->download($file_name)->deleteFileAfterSend(true);
    }

    public function xmlZipIndividu($id){

        $customerData = MasterCustomer::where('id', $id)->first();

        $customerDatas = MasterCustomer::all()->toArray();

        $array = [
            'proaktif' => [
                'jenis_watchlist' => 'INTERNAL WATCHLIST',
                'tindak_pidana_id' => '1',
                'sumber_informasi_khusus' => '3',
                'organisasi_id' => 'ddasda-adsdas-sdadas-asdas',
                'keterangan' => 'Internal Watchlist Organisasi',
                'individu' => [
                    'nama'=> $customerData['name'],
                    'negara_code' => $customerData['country_code'],
                    'alamat' => $customerData['id_address'],
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
                        'pjk_id' => $customerData['pjk_id'],
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
        $arrayToXml->setDomProperties(['formatOutput' => true],['preserveWhiteSpace' => true]);
        $result = $arrayToXml->prettify()->toXml();

        $dom = new \DOMdocument('1.0');
        $dom->preserveWhiteSpace = true;
        $dom->formatOutput = true;
        $dom->loadXML($result);

        $custName = str_replace(' ', '', $customerData['name']);

        $file_name = 'INDIVIDU_'.strtoupper($custName).'_'.date('Ymdhis').'.xml';
        $dom->save(public_path('/xml_data/proaktif_individu/'.$file_name));
    }

    public function xmlZipKorporasi($id){

        $customerData = MasterCustomer::where('id', $id)->first();

        $customerDatas = MasterCustomer::all()->toArray();

        $array = [
            'proaktif' => [
                'jenis_watchlist' => 'INTERNAL WATCHLIST',
                'tindak_pidana_id' => '1',
                'sumber_informasi_khusus' => '3',
                'organisasi_id' => 'ddasda-adsdas-sdadas-asdas',
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
                            'pjk_id' => $customerData['pjk_id'],
                            'atms' => [
                                'no_atm' => $customerData['card_num'],
                            ],
                        ],
                    ],
                ]
            ]
        ];


        $arrayToXml = new ArrayToXml($array);
        $arrayToXml->setDomProperties(['formatOutput' => true],['preserveWhiteSpace' => true]);
        $result = $arrayToXml->prettify()->toXml();

        $dom = new \DOMdocument('1.0');
        $dom->preserveWhiteSpace = true;
        $dom->formatOutput = true;
        $dom->loadXML($result);

        $custName = str_replace(' ', '', $customerData['name']);

        $file_name = 'KORPORASI'.strtoupper($custName).'_'.date('Ymdhis').'.xml';
        $dom->save(public_path('/xml_data/proaktif_korporasi/'.$file_name));
    }

    public function xmlAll($cust){
        $customers = MasterCustomer::select('master_customers.*')
                                    ->join('name_lists', 'master_customers.name', '=', 'name_lists.name')
                                    ->where('master_customers.cust_type', '=', $cust)
                                    ->get();

        foreach ($customers as $cust_single) {
            if ($cust == 'R') {
                $this->xmlZipIndividu($cust_single['id']);
            }
            else if ($cust == 'C'){
                $this->xmlZipKorporasi($cust_single['id']);
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

}
