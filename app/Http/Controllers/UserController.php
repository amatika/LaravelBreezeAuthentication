<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use PDF;
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

   

}
