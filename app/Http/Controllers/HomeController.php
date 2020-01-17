<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $files = Storage::disk('local')->files();
        return view('home', ['files' => $files]);
    }

    /**
     * Downloads a given file specified by $id
     * 
     * @return File given from $id as download
     */
    public function download($id = null)
    {
        return Storage::download($id);
    }

    /**
     * Generates a new excel report with random data
     * 
     * This kind of code should not be in the controller, but I wasn't 
     * sure where else to put it for a small project like this.
     * 
     * @return back()
     */
    public function create()
    {
        // 01 01 2019: 1546300800
        // 31 12 2019: 1577750400
        $report = new ReportExport([
            [12, 34, 45, 235],
            [12, 34, 34, 3, 1, 2, 3, 2]
        ]);

        Excel::store($report, 'report.xlsx');
        return back();
    }
}
