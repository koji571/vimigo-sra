<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\csvRequest;
use App\Services\csvBulkService;

class ImportController extends Controller
{

    /**
     * Handle which method it goes to
     */

    public function handleRequest( csvRequest $request){

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
    private function import(csvRequest $request)
    {

        $file = $request->file('file');
        $csvData = file_get_contents($file);
        $rows = array_map("str_getcsv", explode("\n", $csvData));

        // Instantiate the csvBulkService and call the importStudents() method
        $csvBulkService = app(csvBulkService::class);
        $students = $csvBulkService->importStudents($rows);


        // Return a response indicating successful creation of the students and the students data as resources
        return response()->json([
            'message' => 'Students created successfully',
            'data' => $students
        ], 201);
}

    //Bulk Delete
    private function delete(csvRequest $request)
    {

        $file = $request->file('file');
        $csvData = file_get_contents($file);
        $rows = array_map("str_getcsv", explode("\n", $csvData));

        // Instantiate the csvBulkService and call the deleteStudents() method
        $csvBulkService = app(csvBulkService::class);
        $deletedStudents = $csvBulkService -> deleteStudents($rows);

        // Return response with deleted students
        return response()->json(['message' => 'Students deleted successfully', 'data' => $deletedStudents], 200);
    }

    //Bulk Update
    private function update(csvRequest $request){

        // Get file and parse CSV data
        $file = $request->file('file');
        $csvData = file_get_contents($file);
        $rows = array_map("str_getcsv", explode("\n", $csvData));

        // Instantiate the csvBulkService and call the updateStudents() method
        $csvBulkService = app(csvBulkService::class);
        $updatedStudents = $csvBulkService->updateStudents($rows);

        // Return response with updated students
        return response()->json(['message' => 'Students updated successfully', 'data' => $updatedStudents], 200);
    }
}
