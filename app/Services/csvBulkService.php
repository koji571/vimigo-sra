<?php
namespace App\Services;

use App\Models\Student;
use App\Http\Resources\StudentResource;

class csvBulkService
{

    public function importStudents(array $rows):array
    {
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
        return $students;
    }

    public function deleteStudents(array $rows): array
    {
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

        return $deletedStudents;
    }

    public function updateStudents(array $rows): array
    {
        // Loop through rows of data
        $header = array_shift($rows);
        $updatedStudents = [];

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
                $updatedStudents[] = $student; // Add updated student resource to array
            }
        }

        return $updatedStudents;
    }
}
