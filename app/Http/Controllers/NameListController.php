<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NameList;
use App\Imports\NamesImport;
use App\DataTables\NameListDataTable;
use Maatwebsite\Excel\Facades\Excel;

class NameListController extends Controller
{
    public function index()
    {
        $nameList = NameList::paginate(20);

        //return view('dashboards.index', ['biodatas' => $biodata]);
        return view('namelists.index', ['names' => $nameList]);
    }

    public function index2(NameListDataTable $dataTable)
    {
        return $dataTable->render('namelists.index2');
    }

    // public function json(){
    //     return Datatables::of(NameList::all())->make(true);
    // }


    public function importExcel(Request $request){
        // $this->validate($request, [
		// 	'file' => 'required|mimes:csv,xls,xlsx'
		// ]);

        $file = $request->file('file');

        $fileName = rand().$file->getClientOriginalName();

        $file->move('name_files',$fileName);

        Excel::import(new NamesImport, public_path('/name_files/'.$fileName));

        $request->session()->flash('message', "Data berhasil diimport");

        return redirect('/index2');
    }

    public function destroy()
    {
        NameList::truncate();

        session()->flash('message', "Data berhasil dihapus");

        return redirect('/index2');
    }
}
