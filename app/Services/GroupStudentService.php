<?php

namespace App\Services;

use App\Http\Requests\GroupStudentRequest;
use App\Models\Address;
use App\Models\Group;
use App\Models\Passport;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GroupStudentService
{
    public function create(Group $group, GroupStudentRequest $request): void
    {
        $validated = $request->validated();

        $attributes = [...$validated, 'image_url' => $this->uploadImage($request)];

        if (isset($validated['address']) && $validated['address']) {
            $address = Address::create($validated['address']);
            $attributes['address_id'] = $address->id;
        }

        if (isset($validated['study_address']) && $validated['study_address']) {
            $studyAddress = Address::create($validated['study_address']);
            $attributes['study_address_id'] = $studyAddress->id;
        }

        if (isset($validated['passport']) && $validated['passport']) {
            $passport = Passport::create($validated['passport']);
            $attributes['passport_id'] = $passport->id;
        }

        $group->students()->create($attributes);
    }

    public function update(Student $student, GroupStudentRequest $request): void
    {
        $validated = $request->validated();

        $attributes = [...$validated, 'image_url' => $this->uploadImage($request, $student)];

        if (isset($validated['address']) && $validated['address']) {
            if ($student->address()->exists()) {
                $student->address()->update($validated['address']);
            } else {
                $address = Address::create($validated['address']);
                $attributes['address_id'] = $address->id;
            }
        } else {
            $student->address()->delete();
        }

        if (isset($validated['study_address']) && $validated['study_address']) {
            if ($student->study_address_id !== 1 && $student->studyAddress()->exists()) {
                $student->studyAddress()->update($validated['study_address']);
            } else {
                $address = Address::create($validated['study_address']);
                $attributes['study_address_id'] = $address->id;
            }
        } else {
            if ($student->study_address_id !== 1) {
                $student->studyAddress()->delete();
            }

            $attributes['study_address_id'] = $attributes['study_address_id'] ?? null;
        }

        if (isset($validated['passport']) && $validated['passport']) {
            if ($student->passport()->exists()) {
                $student->passport()->update($validated['passport']);
            } else {
                $passport = Passport::create($validated['passport']);
                $attributes['passport_id'] = $passport->id;
            }
        } else {
            $student->passport()->delete();
        }

        $student->update($attributes);
    }

    public function delete(Student $student): void
    {
        DB::transaction(function () use ($student) {
            $student->address()->delete();
            $student->passport()->delete();

            if ($student->study_address_id !== 1) {
                $student->studyAddress()->delete();
            }

            $student->delete();
        });

        $this->removeImage($student);
    }

    protected function isImageUploaded(GroupStudentRequest $request): bool
    {
        return $request->hasFile('image') && $request->file('image')->isValid();
    }

    protected function removeImage(Student $student)
    {
        if ($student->image_url) {
            Storage::disk('public')->delete($student->getRawOriginal('image_url'));
        }
    }

    protected function uploadImage(GroupStudentRequest $request, ?Student $student = null): mixed
    {
        if ($this->isImageUploaded($request)) {
            /** Удаление предыдущего загруженного фото */
            if ($student) {
                $this->removeImage($student);
            }

            return $request->image->store('images', 'public');
        }

        $removeImage = $request->has('image') && $request->get('image') === null;

        if ($removeImage) {
            $this->removeImage($student);
        }

        return $removeImage ? null : $student?->getRawOriginal('image_url');
    }
}
