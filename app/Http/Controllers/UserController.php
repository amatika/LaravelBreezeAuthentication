<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Allowance;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use PDF;
use App\Mail\UserReportEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function show_users()
    {
       // $users = User::all();
        $users = User::paginate(10);

        return view('user.index', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/users')->with('success', 'User deleted successfully');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return redirect('/users')->with('success', 'User updated successfully');
    }
    
    //function for exporting user data to excel
    public function exportToExcel()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    //function for creating report from user data
    public function showPDF($id)
    {
        return view('pdf.user_report', compact('id'));
    }

    //generating multiple pdf files
   /* public function generateMultiplePDF(Request $request)
    {
        $selectedUserIds = $request->input('selected_users', []);

        // Fetch selected users
        $selectedUsers = User::whereIn('id', $selectedUserIds)->get();

        foreach ($selectedUsers as $user) {
            $pdf = PDF::loadView('pdf.user_report', compact('user'));
            $pdf->save(storage_path("user_reports/user_{$user->id}_report.pdf")); // Save PDF to storage
        }

        return redirect()->back()->with('success', 'PDFs generated successfully.');
    }*/

    public function generateMultiplePDFold(Request $request)
    {
        // Get the selected user IDs from the form submission
        $selectedUserIds = $request->input('selected_users', []);

        // Fetch the selected users from the database using the User model
        $selectedUsers = User::whereIn('id', $selectedUserIds)->get();

        // Get the user-specified directory from the form submission or use a default directory
        $storageDirectory = $request->input('storage_directory', 'user_reports');

        // Loop through each selected user
        foreach ($selectedUsers as $user) {
            // Generate a PDF for the current user using the user_report view
            $pdf = PDF::loadView('pdf.user_report', compact('user'));

            // Save the generated PDF to the specified storage directory with a filename based on the user's ID
            $pdf->save(storage_path("$storageDirectory/{$user->id}_report.pdf"));
        }

        // Redirect back to the previous page with a success message
        return redirect()->back()->with('success', 'PDFs generated successfully.');
    }

    public function generateMultiplePDF(Request $request)
    {
        $selectedUserIds = $request->input('selected_users', []);
        $selectedUsers = User::whereIn('id', $selectedUserIds)->get();

        // Create a zip file to bundle multiple PDFs
        $zipFileName = 'user_reports.zip';
        $zip = new \ZipArchive();
        $zip->open(storage_path($zipFileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($selectedUsers as $user) {
            $pdf = PDF::loadView('pdf.user_report', compact('user'));
            $pdfFileName = "user_{$user->id}_report.pdf";
            
            // Save PDF to the storage directory
            $pdf->save(storage_path("user_reports/{$pdfFileName}"));

            // Add PDF to the zip file
            $zip->addFile(storage_path("user_reports/{$pdfFileName}"), $pdfFileName);
        }
        $zip->close();
        // Provide the zip file for download
        return response()->download(storage_path($zipFileName))->deleteFileAfterSend(true);
    }

    public function downloadZip()
    {
        $zipFileName = 'user_reports.zip';

        return response()->download(storage_path($zipFileName))->deleteFileAfterSend(true);
    }

   //function that mails a report to each user    
    public function generateMultiplePDF_and_mail(Request $request)
    {
        $selectedUserIds = $request->input('selected_users', []);
        $selectedUsers = User::whereIn('id', $selectedUserIds)->get();
    
        foreach ($selectedUsers as $user) {
            $pdf = \PDF::loadView('pdf.user_report', compact('user'));
    
            // Save the PDF to the storage directory
            $pdfPath = storage_path("user_reports/user_{$user->id}_report.pdf");
            $pdf->save($pdfPath);
    
            // Send email with the PDF attached
            Mail::to($user->email)
                ->send(new UserReportEmail($user));
    
            // Delete the saved PDF file after sending the email
            //unlink($pdfPath);
        }
    
        return redirect()->back()->with('success', 'PDFs and emails sent successfully.');
    }


    public function index_old()
    {
      
        $years = Allowance::select('year')->distinct()->pluck('year');

        return view('users.allowances', compact('years'));
    }

    public function index_old2()
    {
        // Get unique years from the allowance_user table
        $years = Allowance::select('year')->distinct()->pluck('year');

        // Get users with allowances and amounts for the selected year
        $selectedYear = request()->input('year', now()->year); // Default to the current year
        $users = User::with(['allowances' => function ($query) use ($selectedYear) {
            $query->select('allowances.id', 'name', 'amount', 'year')
                ->join('allowance_user', 'allowance_user.allowance_id', '=', 'allowances.id')
                ->where('allowance_user.year', $selectedYear);
        }])->get();

        return view('users.allowances', compact('years'));
    }

    public function index_old3()
    {
        // Get unique years from the allowance_user table
        $years = DB::table('allowance_user')->select('year')->distinct()->pluck('year');

        $allows = DB::table('allowances')->pluck('name');

        // Get users with allowances and amounts for the selected year
        $selectedYear = request()->input('year', now()->year);

        $users = DB::table('users')
            ->select('users.id', 'users.name', 'au1.amount', 'au1.year')
            ->leftJoin('allowance_user as au1', function ($join) use ($selectedYear) {
                $join->on('users.id', '=', 'au1.user_id')
                    ->where('au1.year', '=', $selectedYear);
            })
            ->leftJoin('allowances', 'au1.allowance_id', '=', 'allowances.id')
            ->get();
        
        

        return view('allowances', compact('users', 'years', 'selectedYear',allows));
    }

    public function index_old4()
    {
        // Get unique years from the allowance_user table
        $years = DB::table('allowance_user')->select('year')->distinct()->pluck('year');

        // Get users with allowances and amounts for the selected year
        $selectedYear = request()->input('year', now()->year); // Default to the current year
        $users = DB::table('users')
            ->select('users.*', 'allowance_user.amount', 'allowance_user.year')
            ->leftJoin('allowance_user', function ($join) use ($selectedYear) {
                $join->on('users.id', '=', 'allowance_user.user_id')
                    ->where('allowance_user.year', '=', $selectedYear);
            })
            ->get();

        $allowances = DB::table('allowances')->pluck('name');


        return view('allowances', compact('users', 'years', 'selectedYear','allowances'));
    }


    public function index_old5()
    {
        $years = DB::table('allowance_user')->select('year')->distinct()->pluck('year');

        $users = DB::table('users')->select('id', 'name')->get();

        $allowances = DB::table('allowances')->select('id', 'name')->get();

        $tableData = DB::table('users')
            ->join('allowance_user', 'users.id', '=', 'allowance_user.user_id')
            ->join('allowances', 'allowance_user.allowance_id', '=', 'allowances.id')
            ->select('users.id as user_id', 'users.name as user_name', 'allowances.id as allowance_id', 'allowances.name as allowance_name', 'allowance_user.amount', 'allowance_user.year')
            ->get();

        return view('allowances', compact('years', 'users', 'allowances', 'tableData'));
    }

    public function index_old6(Request $request)
    {
        $selectedYear = $request->input('year');

        $years = DB::table('allowance_user')->select('year')->distinct()->pluck('year');

        $users = DB::table('users')->select('id', 'name')->get();

        $allowances = DB::table('allowances')->select('id', 'name')->get();

        $tableData = DB::table('users')
            ->join('allowance_user', 'users.id', '=', 'allowance_user.user_id')
            ->join('allowances', 'allowance_user.allowance_id', '=', 'allowances.id')
            ->select('users.id as user_id', 'users.name as user_name', 'allowances.id as allowance_id', 'allowances.name as allowance_name', 'allowance_user.amount', 'allowance_user.year')
            ->when($selectedYear, function ($query, $selectedYear) {
                return $query->where('allowance_user.year', $selectedYear);
            })
            ->get();

        return view('allowances', compact('selectedYear', 'years', 'users', 'allowances', 'tableData'));
    }


    public function index(Request $request)
    {
        $selectedYear = $request->input('year');

        $years = DB::table('allowance_user')->select('year')->distinct()->pluck('year');

        $users = DB::table('users')->select('id', 'name')->get();

        $allowances = DB::table('allowances')->select('id', 'name')->get();

        $tableData = DB::table('users')
            ->join('allowance_user', 'users.id', '=', 'allowance_user.user_id')
            ->join('allowances', 'allowance_user.allowance_id', '=', 'allowances.id')
            ->select('users.id as user_id', 'users.name as user_name', 'allowances.id as allowance_id', 'allowances.name as allowance_name', 'allowance_user.amount', 'allowance_user.year')
            ->when($selectedYear, function ($query, $selectedYear) {
                return $query->where('allowance_user.year', $selectedYear);
            })
            ->get();

        // Calculate averages, positions, and grades
        $averages = $tableData->groupBy('user_id')->map(function ($userRows) {
            return $userRows->avg('amount');
        });

        $positions = $averages->sortDesc();

        $grades = $averages->map(function ($average) {
            if ($average >= 9001) {
                return 'A';
            } elseif ($average >= 7001) {
                return 'B';
            } elseif ($average >= 5001) {
                return 'C';
            } elseif ($average >= 3001) {
                return 'D';
            } else {
                return 'F';
            }
        });

        return view('allowances', compact('selectedYear', 'years', 'users', 'allowances', 'tableData', 'averages', 'positions', 'grades'));
    }



    public function getData(Request $request)
    {
        $year = $request->input('year');

        $users = User::with(['allowances' => function ($query) use ($year) {
            $query->where('year', $year);
        }])->get();

        return response()->json(['data' => $users]);
    }


}
