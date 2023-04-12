<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request){
        // Paginate the records with a specified number of items per page
        $perPage = 10; // Value to specify the number of items per page
        $students = Student::paginate($perPage);

        // Transform the paginated students using StudentResource and return as the response
        return StudentResource::collection($students); // Use StudentResource to transform the paginated students and return as the response
    }

    /**
     * Show the form for creating a new resource.
     */
    //unused default method
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'study_course' => 'string|max:255',
        ]);

        // Create a new Student instance with the validated data
        $student = new Student;
        $student->name = $validatedData['name'];
        $student->email = $validatedData['email'];
        $student->address = $validatedData['address'];
        $student->study_course = $request->input('study_course', 'Science');

        // Save the student instance to the database
        $student->save();

        // Return a response indicating successful creation of the student
        return response()->json(['message' => 'Student created successfully', 'data' => new StudentResource($student)], 201);
    }

    /**
    * Display the specified resource.
    */

    public function show(Request $request){
        // Get the search term from the request
        $searchTerm = $request->input('search');

        // Validate the search term
        $request->validate([
            'search' => 'required|string'
        ]);

        // Search the student by name or email
        $student = Student::where('name', $searchTerm)->orWhere('email', $searchTerm)->first();

        // Check if student is found
        if ($student) {
            // Return the student data as a resource
            return new StudentResource($student);
        }else {
            // Return a response indicating that the student is not found
            return response()->json(['message' => 'Student not found'], 404);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    //unused default method
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request){

        /// Get the student by name
        $student = Student::where('name', $request->input('name'))->first();

        // Check if student is found
        if ($student) {
            // Update the student data based on the request, if available
            if ($request->filled('email')) {
                $student->email = $request->input('email');
            }
            if ($request->filled('address')) {
                $student->address = $request->input('address');
            }
            if ($request->filled('study_course')) {
                $student->study_course = $request->input('study_course');
            }
            // Save the updated student data
            $student->save();

            // Return the updated student data as a resource
            return new StudentResource($student);
        } else {
            // Return a response indicating that the student is not found
            return response()->json(['message' => 'Student not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request){

        /// Delete the student by name
        $student = Student::where('name', $request->input('name'))->first();


        if ($student) {
            $student->delete();
            return response()->json(['message' => 'Student deleted successfully', 'data' => new StudentResource($student)]);
        } else {
            return response()->json(['message' => 'Student not found'], 404);
        }
    }
}
