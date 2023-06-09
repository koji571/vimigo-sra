<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller
{

    /**
     * Handle which method it goes to
     */

    public function handleRequest(Request $request){

        //check which action is selected
        $action = $request['action'];

        // Check if the method exists in the controller
        switch ($action) {
            case 'import':
                return $this->import($request);
            case 'delete':
                return $this->delete($request);
            case 'update':
                return $this->update($request);
            default:
                // Handle unknown action
                return response()->json('Unknown action');
        }
     }

    /**
     * Methods to handle bulk import of data
     */

     //Bulk Create
    private function import(Request $request)
    {
        // Validate file input
        $validator = Validator::make($request->all(), [
            'file' => 'required'
        ]);
        // Get file and parse .csv data
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }


        $file = $request->file('file');
        $csvData = file_get_contents($file);
        $rows = array_map("str_getcsv", explode("\n", $csvData));
        $header = array_shift($rows);

        $students = [];

        foreach ($rows as $row) {
            $row = array_combine($header, $row);

            // Create a new Student instance with the row data
            $student = new Student;
            $student->name = $row['name'];
            $student->email = $row['email'];
            $student->address = $row['address'];
            $student->study_course = $row['study_course'];

            // Save the student instance to the database
            $student->save();

            // Add the student data as a resource to the array
            $students[] = new StudentResource($student);
        }

        // Return a response indicating successful creation of the students and the students data as resources
        return response()->json([
            'message' => 'Students created successfully',
            'data' => $students
        ], 201);
}

    //Bulk Delete
    private function delete(Request $request)
    {
        // Validate file input
        $validator = Validator::make($request->all(), [
            'file' => 'required'
        ]);

        // Get file and parse CSV data
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }


        $file = $request->file('file');
        $csvData = file_get_contents($file);
        $rows = array_map("str_getcsv", explode("\n", $csvData));
        $header = array_shift($rows);

        $deletedStudents = []; // Array to store deleted students

        // Loop through rows of data
        foreach ($rows as $row) {
            $row = array_combine($header, $row);
            $student = Student::where('name', $row['name'])->first(); // Find student by name
            if ($student) {
                $student->delete(); // Delete student
                $deletedStudents[] = new StudentResource($student); // Add deleted student to array
            }
        }

        // Return response with deleted students
        return response()->json(['message' => 'Students deleted successfully', 'data' => $deletedStudents], 200);
    }

    //Bulk Update
    private function update(Request $request){
        // Validate file input
        $validator = Validator::make($request->all(), [
            'file' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        // Get file and parse CSV data
        $file = $request->file('file');
        $csvData = file_get_contents($file);
        $rows = array_map("str_getcsv", explode("\n", $csvData));
        $header = array_shift($rows);

        $updatedStudents = []; // Array to store updated students

        // Loop through rows of data
        foreach ($rows as $row) {
            $row = array_combine($header, $row);
            $student = Student::where('name', $row['name'])->first(); // Find student by name

            if ($student) {
                // Update student data based on input, if available
                if (!empty($row['email'])) {
                    $student->email = $row['email'];
                }
                if (!empty($row['address'])) {
                    $student->address = $row['address'];
                }
                if (!empty($row['study_course'])) {
                    $student->study_course = $row['study_course'];
                }

                $student->save(); // Save updated student data
                $updatedStudents[] = new StudentResource($student); // Add updated student resource to array
            }
        }

        // Return response with updated students
        return response()->json(['message' => 'Students updated successfully', 'data' => $updatedStudents], 200);
    }
}
