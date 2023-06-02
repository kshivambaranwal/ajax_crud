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

    /**
 * @OA\Post(
 *     path="/api/user-store",
 *     tags={"Users"},
 *     summary="Create a new user",
 *     @OA\RequestBody(
 *         description="User data",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"name", "email", "phone", "company", "education", "hobby", "gender", "experience", "message", "file"},
 *                 @OA\Property(property="name", type="string"),
 *                 @OA\Property(property="email", type="string", format="email"),
 *                 @OA\Property(property="phone", type="integer"),
 *                 @OA\Property(property="company", type="integer"),
 *                 @OA\Property(property="education", type="integer"),
 *                 @OA\Property(property="hobby[]", type="array", @OA\Items(type="string")),
 *                 @OA\Property(property="gender", type="string"),
 *                 @OA\Property(property="experience[]", type="array", @OA\Items(type="string")),
 *                 @OA\Property(property="message", type="string"),
 *                 @OA\Property(property="file", type="string", format="binary"),
 *             )
 *         )
 *     ),
 *     @OA\Response(response="200", description="User created successfully"),
 *     @OA\Response(response="422", description="Validation error"),
 * )
 */
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
                'status' => 422,
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

 /**
 * @OA\Post(
 *     path="/api/user-update",
 *     tags={"Users"},
 *     summary="Update a user",
 *     @OA\RequestBody(
 *         description="User data",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"name", "email", "phone", "company", "education", "hobby", "gender", "experience", "message"},
 *                 @OA\Property(property="name", type="string"),
 *                 @OA\Property(property="email", type="string", format="email"),
 *                 @OA\Property(property="phone", type="integer"),
 *                 @OA\Property(property="company", type="integer"),
 *                 @OA\Property(property="education", type="integer"),
 *                 @OA\Property(property="hobby[]", type="array", @OA\Items(type="string")),
 *                 @OA\Property(property="gender", type="string"),
 *                 @OA\Property(property="experience[]", type="array", @OA\Items(type="string")),
 *                 @OA\Property(property="message", type="string"),
 *                 @OA\Property(property="file", type="string", format="binary"),
 *             )
 *         )
 *     ),
 *     @OA\Response(response="200", description="User created successfully"),
 *     @OA\Response(response="422", description="Validation error"),
 * )
 */
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
                        'message' => 'User Update Successfully!',
                        'data' => $createUser,
                    ]);
                }
            } catch (\Exception $e) {
                return $e;
                return response()->json(['error' => 'An error occurred while creating the user. Please try again.']);
            }
        }
    }

    /**
     * @OA\Get(
     *     path="/api/fetch-all",
     *     tags={"User all Record"},
     *     summary="Get all List of users table",
     *     description="Returns a list of all users table",
     *     @OA\Response(response="200", description="List of Users in data key"),
     *     @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function fetchall()
    {
        $data = Employee::all();
        return response()->json([
            'status' => 200,
            'message' => 'List of Users!',
            'data' => $data,
        ]);
    }

/**
 * @OA\Post(
 *     path="/api/user-delete",
 *     tags={"Delete Users"},
 *     summary="Delet a user",
 *      @OA\RequestBody(
 *         description="User ID",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"user_id"},
 *                 @OA\Property(property="user_id", type="integer")
 *             )
 *         )
 *     ),
 *     @OA\Response(response="200", description="User deleted successfully"),
 *     @OA\Response(response="422", description="Validation error"),
 * )
 */
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
