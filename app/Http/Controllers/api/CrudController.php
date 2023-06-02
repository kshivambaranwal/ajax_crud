<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Education;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Users Api",
 *     version="1.0.0",
 *     description="API documentation for Users Api"
 * )
 */
class CrudController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/index",
     *     tags={"Company and Education"},
     *     summary="Get all List of Company and Education",
     *     description="Returns a list of all Company and Education",
     *     @OA\Response(response="200", description="list of company and Education"),
     *     @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function index()
    {
        $company = Company::get();
        $education = Education::get();
        return response()->json([
            'company' => $company,
            'education' => $education,
        ]);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|regex:/^[A-Za-z\s]+$/',
            'email' => 'required|email|unique:users',
            'phone' => 'required|digits:10',
            'company' => 'required',
            'education' => 'required',
            'hobby' => 'required',
            'gender' => 'required',
            'experience' => 'required',
            'file' => 'required',
            'message' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'status'=>301,
                'validation' => $validation->errors()->all()
            ]);
        } else {

            if ($request->file) {
                $imageName = time() . '.' . $request->file->extension();
                $request->file->move(public_path('images'), $imageName);
                // $request->file->storeAs('public/images', $imageName);
                $fileName = 'images/' . $imageName;
            }
            try {
                $createUser = Employee::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'company' => $request->company,
                    'education' => $request->education,
                    'hobby' => json_encode($request->hobby),
                    'gender' => $request->gender,
                    'experience' => json_encode($request->experience),
                    'message' => $request->message,
                    'file' => $fileName,
                ]);
                if ($createUser) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'User craeted Successfully!',
                        'data' => $createUser,
                    ]);
                }
            } catch (\Exception $e) {
                return $e;
                return response()->json(['error' => 'An error occurred while creating the user. Please try again.']);
            }
        }
    }

    public function update(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name' => 'required|regex:/^[A-Za-z\s]+$/',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'phone' => 'required|digits:10',
            'company' => 'required',
            'education' => 'required',
            'hobby' => 'required',
            'gender' => 'required',
            'experience' => 'required',
            'message' => 'required',
            'user_id' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['validation' => $validation->errors()->all()]);
        } else {

            if ($request->file) {
                $imagePath = Employee::find($request->user_id)->file;
                if (File::exists($imagePath)) {
                    // Delete the file from the filesystem
                    File::delete($imagePath);
                }

                $imageName = time() . '.' . $request->file->extension();
                $request->file->move(public_path('images'), $imageName);
                // $request->file->storeAs('public/images', $imageName);
                $fileName = 'images/' . $imageName;
                Employee::find($request->user_id)->update([
                    'file' => $fileName,
                ]);
            }
            try {
                $createUser = Employee::find($request->user_id)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'company' => $request->company,
                    'education' => $request->education,
                    'hobby' => json_encode($request->hobby),
                    'gender' => $request->gender,
                    'experience' => json_encode($request->experience),
                    'message' => $request->message,
                ]);
                if ($createUser) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'User craeted Successfully!',
                        'data' => $createUser,
                    ]);
                }
            } catch (\Exception $e) {
                return $e;
                return response()->json(['error' => 'An error occurred while creating the user. Please try again.']);
            }
        }
    }

    public function fetchall()
    {
        $data = Employee::all();
        return response()->json([
            'status' => 200,
            'message' => 'List of Users!',
            'data' => $data,
        ]);
    }

    public function delete(Request $request)
    {
        try {
            $imagePath = Employee::find($request->user_id)->file;
            if (File::exists($imagePath)) {
                // Delete the file from the filesystem
                File::delete($imagePath);
            }
            Employee::find($request->user_id)->delete();
            return response()->json([
                'status' => 200,
                'message' => 'User Deleted Successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 302,
                'message' => $e,
            ]);
        }
    }
}
