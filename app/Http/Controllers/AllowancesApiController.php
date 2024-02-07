<?php

namespace App\Http\Controllers;
use App\Models\Allowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
//use App\Models\Allowance;

class AllowancesApiController extends Controller
{
    public function index()
    {
        return Allowance::all();
    }

    public function show($id)
    {
        return Allowance::findOrFail($id);
    }

    public function store(Request $request)
    {
        /*$validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }*/

        $allowance = Allowance::create($request->all());
        return response()->json($allowance, 201);
    }

    public function update(Request $request, $id)
    {
       /* $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }*/

        $allowance = Allowance::findOrFail($id);
        $allowance->update($request->all());
        return response()->json($allowance, 200);
    }

    public function destroy($id)
    {
        Allowance::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    //fetching allowance data from the pivot table
    public function showUserAllowances($id)
    {
        $user = User::findOrFail($id);
        $user->allowances = $user->allowances()->get(['allowances.id', 'allowances.name', 'allowance_user.amount', 'allowance_user.year']);
        
        return $user;
    }
}
