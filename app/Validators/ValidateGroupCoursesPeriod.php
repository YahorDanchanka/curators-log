<?php

namespace App\Validators;

use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class ValidateGroupCoursesPeriod
{
    public function __invoke(Validator $validator)
    {
        $courses = collect($validator->getData()['courses'] ?? []);

        foreach ($courses as $key => $course) {
            $courseNumber = $course['number'];
            $hasError = Carbon::parse($course['start_education'])->diffInMonths($course['end_education']) > 12;

            if ($hasError) {
                $validator
                    ->errors()
                    ->add(
                        "courses.$key.start_education",
                        "Разница срока обучения для $courseNumber курса не может превышать 1 года."
                    );
                break;
            }
        }
    }
}
