<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        try {
            $employees = Employees::orderBy('id', 'desc')->get();
            return response()->json(['success' => true, 'data' => $employees]);
        } catch (\Throwable $th) {
            Log::error('Failed to fetch data employees: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
            
        }
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'phone' => 'required',

        ]);
        
        if ($validate->fails()) {
            return response()->json([
                'success' => false, 
                'message' => $validate->errors(),
                
            ],422);
        }
        
        try {
            $employees = Employees::create([
                'user_id' => $request->user_id,
                'phone' => $request->phone,
                'address' => $request->address,
                'is_active' => $request->is_active,
                'gender' => $request->gender,
                'nip' => $request->nip,
            ]);
            return response()->json(['success' => true, 'message' => 'Employees added successfully', 'data' => $employees]);
        } catch (\Throwable $th) {
            Log::error('Failed insert : ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed Create Employees'], 500);
        }

    }

    public function show($id){
        try {
            $employees = Employees::with('user')->findOrFail($id);
            return response()->json(['success' => true, 'message' =>'Show Data Success', 'data' => $employees]);
        } catch (\Throwable $th) {
            Log::error('Failed insert : ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed Create Employees'], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'phone' => 'required',

        ]);
        
        if ($validate->fails()) {
            return response()->json([
                'success' => false, 
                'message' => $validate->errors(),
                
            ],422);
        }
        
        try {
            $data = [
                'user_id' => $request->user_id,
                'phone' => $request->phone,
                'address' => $request->address,
                'is_active' => $request->is_active,
                'gender' => $request->gender,
                'nip' => $request->nip,
            ];

            $employees = Employees::findOrFail($id);
            $employees->update($data);
            return response()->json(['success' => true, 'message' => 'Employees added successfully', 'data' => $employees]);
        } catch (\Throwable $th) {
            Log::error('Failed insert : ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed Create Employees'], 500);
        }

    }

    public function destroy($id)
    {
        try {
            $employees = Employees::findOrFail($id)->delete();
            return response()->json(['message' => 'Employees deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    // Method: delete
    // endpoint:


}


