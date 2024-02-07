<?php

namespace App\Http\Controllers;
use App\Models\YourModel;
use Illuminate\Http\Request;
use App\Models\User;
class AgeController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);

        return view('user.add_age', compact('users'));
    }

    public function save(Request $request)
    {
        $input = $request->all();

        foreach ($input['ages'] as $id => $age) {
            User::where('id', $id)->update(['age' => $age]);
        }

        return redirect()->route('ages.index')->with('success', 'Ages saved successfully');
    }
}
