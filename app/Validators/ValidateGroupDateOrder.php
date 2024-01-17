<?php

namespace App\Validators;

use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class ValidateGroupDateOrder
{
    public function __invoke(Validator $validator)
    {
        $courses = collect($validator->getData()['courses'] ?? []);

        foreach ($courses as $key => $course) {
            $hasError = $courses->contains(
                fn(array $c, int $key1) => $key !== $key1 &&
                    ($c['number'] < $course['number'] &&
                        (Carbon::parse($c['start_education'])->greaterThanOrEqualTo($course['start_education']) ||
                            Carbon::parse($c['end_education'])->greaterThanOrEqualTo($course['end_education'])))
            );

            if ($hasError) {
                $validator->errors()->add("courses.$key.start_education", 'Нарушен порядок в датах курсов обучения.');
                break;
            }
        }
    }
}
