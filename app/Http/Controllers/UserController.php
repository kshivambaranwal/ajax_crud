<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Education;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function index()
    {
        $company = Company::get();
        $education = Education::get();
        return view('welcome', compact('company', 'education'));
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
            return response()->json(['validation' => $validation->errors()->all()]);
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
        $output = '';
        if ($data->count() > 0) {
            $output .= '<table class="table table-striped table-inverse">
        <thead class="thead-inverse">
            <tr>
                <th>Sr.no</th>
                <th>name</th>
                <th>email</th>
                <th>phone</th>
                <th>company</th>
                <th>education</th>
                <th>hobby</th>
                <th>gender</th>
                <th>experience</th>
                <th>Image</th>
                <th>message</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>';
            foreach ($data as $key => $dt) {
                $output .= '<tr>
                <td>' . ($key + 1) . '</td>
                <td>' . $dt->name . '</td>
                <td>' . $dt->email . '</td>
                <td>' . $dt->phone . '</td>
                <td>' . $dt->companyname->name . '</td>
                <td>' . $dt->educationname->name . '</td>
                <td>';
                foreach (json_decode($dt->hobby) as $hb) {
                    $output .= '<span>' . $hb . ',</span>';
                }
                $output .= '</td>
                <td>' . $dt->gender . '</td>
                <td>';
                foreach (json_decode($dt->experience) as $hb) {
                    $output .= '<span>' . $hb . ',</span>';
                }
                $output .= '</td>
                <td><img src="' . $dt->file . '" width="50" class="img-thumbnail rounded-circle"></td>
                <td>' . $dt->message . '</td>
                <td>
                <a href="#" id="' . $dt->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"><i class="bi-pencil-square h4"></i></a>
                <a href="#" id="' . $dt->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
            </tr>';
            }
            $output .= '</tbody></table>';

            return $output;
        } else {
            return $output .= '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }
    }

    public function delete(Request $request)
    {
        $imagePath = Employee::find($request->id)->file;
        if (File::exists($imagePath)) {
            // Delete the file from the filesystem
            File::delete($imagePath);
        }
        Employee::find($request->id)->delete();
        return response()->json([
            'status' => 200,
            'message' => 'User Deleted Successfully!',
        ]);
    }

    public function edit(Request $request)
    {
        $company = Company::get();
        $education = Education::get();
        $empdata = Employee::find($request->id);
        return view('edit', compact('empdata', 'company', 'education'));
    }

    public function update(Request $request)
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
            'message' => 'required',
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
                    ]);
                }
            } catch (\Exception $e) {
                return $e;
                return response()->json(['error' => 'An error occurred while creating the user. Please try again.']);
            }
        }
    }
}
