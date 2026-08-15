<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrainingCompletionRequest;
use App\Models\TrainingCompletion;
use App\Models\TrainingCourse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TrainingCompletionController extends Controller
{
    public function store(StoreTrainingCompletionRequest $request, TrainingCourse $course): RedirectResponse
    {
        // Completions are managed as part of a course, so authorization
        // is checked against the course itself rather than a separate
        // TrainingCompletion policy.
        $this->authorize('update', $course);

        $course->completions()->create($request->validated());

        return redirect()->route('training-courses.show', $course)->with('status', 'Added to roster.');
    }

    public function toggleComplete(TrainingCompletion $completion): RedirectResponse
    {
        $this->authorize('update', $completion->course);

        $completion->update([
            'completed_at' => $completion->isCompleted() ? null : now(),
        ]);

        return redirect()
            ->route('training-courses.show', $completion->training_course_id)
            ->with('status', $completion->isCompleted() ? 'Marked complete.' : 'Marked incomplete.');
    }

    public function update(Request $request, TrainingCompletion $completion): RedirectResponse
    {
        $this->authorize('update', $completion->course);

        $data = $request->validate([
            'staff_name' => ['required', 'string', 'max:255'],
            'staff_email' => ['nullable', 'email', 'max:255'],
            'due_at' => ['nullable', 'date'],
        ]);

        $completion->update($data);

        return redirect()
            ->route('training-courses.show', $completion->training_course_id)
            ->with('status', 'Updated.');
    }

    public function destroy(TrainingCompletion $completion): RedirectResponse
    {
        $this->authorize('delete', $completion->course);

        $courseId = $completion->training_course_id;
        $completion->delete();

        return redirect()->route('training-courses.show', $courseId)->with('status', 'Removed from roster.');
    }
}
