<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Student::class;

    public function definition(): array
    {
        //shortcut to randomize study course of students
        $course = ['Science', 'Art', 'Bussiness', 'ICT']; //the types of courses
        $randomcourse =$this-> faker->randomElement($course); // Generate a sentence with one word


        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'address' => $this->faker->streetAddress(),
            'study_course' => $randomcourse,

        ];
    }
}
