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
     * This kind of code would normally not be in the controller,
     * but I wasn't sure where else to put it.
     */
    public function create()
    {
        // 01 01 2019: 1546300800
        // 31 12 2019: 1577750400

        $alphabet = range('A', 'Z');

        //First row of excel sheet - All the titles
        $outerArray = [[
            "2019 data",
            "Company 1",
            "Company 2",
            "Company 3",
            "Company 4",
            "Company 5",
            "Company 6",
            "Company 7",
            "Company 8",
            "Company 9",
            "Company 10",
            "2019 Total",
            "2018 Total",
            "Difference"
        ]];

        //Ugly loop to generate all the random data
        for ($i = 1; $i < 24; $i++) {
            if ($i < 21) {
                $innerArray = [];
                $innerTotal = 0;
                for ($x = 0; $x < 14; $x++) {
                    if ($x == 0) {
                        $innerArray[$x] = date("d-m-Y", rand(1546300800, 1577750400));
                    } elseif ($x < 11) {
                        $random = rand(1, 10000);
                        $innerTotal += $random;
                        $innerArray[$x] = $random;
                    } elseif ($x == 11) {
                        $innerArray[$x] = $innerTotal;
                    } elseif ($x == 12) {
                        $innerArray[$x] = rand(20000, 80000);
                    } else {
                        $innerArray[$x] = (($innerArray[11] - $innerArray[12]) / $innerArray[12]) + 1;
                    }
                }
                $outerArray[$i] = $innerArray;
            } elseif ($i == 21) {
                $outerArray[$i][0] = "Total for 2019";
                for ($x = 1; $x <= 10; $x++) {
                    $verticalTotal = 0;
                    for ($j = 1; $j < 21; $j++) {
                        $verticalTotal += $outerArray[$j][$x];
                    }
                    $outerArray[$i][$x] = $verticalTotal;
                }
                $outerArray[21][13] = "";
            } elseif ($i == 22) {
                $outerArray[$i][0] = "Total for 2018";
                for ($x = 1; $x <= 10; $x++) {
                    $outerArray[$i][$x] = rand(40000, 160000);
                }
            } else {
                $outerArray[$i][0] = "Difference";
                for ($x = 1; $x <= 10; $x++) {
                    $outerArray[$i][$x] = (($outerArray[$i - 2][$x] - $outerArray[$i - 1][$x]) / $outerArray[$i - 1][$x]) + 1;
                }
            }
        }

        $report = new ReportExport($outerArray);

        //Counter used to make new reports with increasing suffix number
        $reportNumber = 0;
        $files = Storage::disk('local')->files();
        foreach ($files as $file) {
            $reportNumber++;
        }

        Excel::store($report, 'report' . $reportNumber . '.xlsx');

        //Returns to the same page, effectively refreshing it, so the user can see the newly generated files.
        return back();
    }
}
