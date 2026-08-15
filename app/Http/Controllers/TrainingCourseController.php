<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrainingCourseRequest;
use App\Http\Requests\UpdateTrainingCourseRequest;
use App\Models\TrainingCourse;
use App\Support\Exports\RegisterExport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TrainingCourseController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', TrainingCourse::class);

        $courses = TrainingCourse::query()
            ->withCount('completions')
            ->orderBy('name')
            ->paginate(20);

        return view('training.index', compact('courses'));
    }

    public function create(): View
    {
        $this->authorize('create', TrainingCourse::class);

        return view('training.create');
    }

    public function store(StoreTrainingCourseRequest $request): RedirectResponse
    {
        $this->authorize('create', TrainingCourse::class);

        $course = DB::transaction(fn () => TrainingCourse::create($request->validated()));

        return redirect()
            ->route('training-courses.show', $course)
            ->with('status', "Created \"{$course->name}\".");
    }

    public function show(TrainingCourse $course): View
    {
        $this->authorize('view', $course);

        $completions = $course->completions()->orderBy('staff_name')->get();

        return view('training.show', compact('course', 'completions'));
    }

    public function edit(TrainingCourse $course): View
    {
        $this->authorize('update', $course);

        return view('training.edit', compact('course'));
    }

    public function update(UpdateTrainingCourseRequest $request, TrainingCourse $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $course->update($request->validated());

        return redirect()->route('training-courses.show', $course)->with('status', 'Updated.');
    }

    public function destroy(TrainingCourse $course): RedirectResponse
    {
        $this->authorize('delete', $course);

        $course->delete();

        return redirect()->route('training-courses.index')->with('status', 'Deleted.');
    }

    public function exportCsv()
    {
        $this->authorize('viewAny', TrainingCourse::class);

        $headers = ['Course', 'Total Staff', 'Completed', 'Completion %'];

        $rows = TrainingCourse::query()->withCount([
            'completions',
            'completions as completed_count' => fn ($q) => $q->whereNotNull('completed_at'),
        ])->orderBy('name')->get()->map(fn ($course) => [
            $course->name,
            $course->completions_count,
            $course->completed_count,
            $course->completions_count > 0 ? round(100 * $course->completed_count / $course->completions_count).'%' : '—',
        ]);

        return RegisterExport::csv('training-courses.csv', $headers, $rows);
    }

    public function exportPdf()
    {
        $this->authorize('viewAny', TrainingCourse::class);

        $headers = ['Course', 'Total Staff', 'Completed', 'Completion %'];

        $rows = TrainingCourse::query()->withCount([
            'completions',
            'completions as completed_count' => fn ($q) => $q->whereNotNull('completed_at'),
        ])->orderBy('name')->get()->map(fn ($course) => [
            $course->name,
            $course->completions_count,
            $course->completed_count,
            $course->completions_count > 0 ? round(100 * $course->completed_count / $course->completions_count).'%' : '—',
        ]);

        return RegisterExport::pdf('Staff Training Register', $headers, $rows, 'training-courses.pdf');
    }
}
