<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\ClassesResource;
use App\Http\Resources\StudentResource;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function index(Request $request)
    {
        $studentsQuery = Student::query();
        $this->applySearch($studentsQuery, $request->search);

        $students = StudentResource::collection($studentsQuery->paginate(10));

        return inertia('Students/Index', [
            'students' => $students,
            'search' => $request->search ?? '',
        ]);
    }

    protected function applySearch($query,$search) {
        return $query->when($search, function($query, $search) {
            $query->where('name','like','%' . $search . '%');
        });
    }

    public function create()
    {
        $classes = ClassesResource::collection(Classes::all());

        return inertia('Students/Create', ['classes' => $classes]);
    }

    public function store(StoreStudentRequest $request)
    {
        $validatedData = $request->validated();
        Student::create($validatedData);

        return redirect()->route('students.index');
    }

    public function edit(Student $student)
    {
        $classes = ClassesResource::collection(Classes::all());

        return inertia('Students/Edit', [
            'classes' => $classes,
            'student' => StudentResource::make($student)
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $validatedData = $request->validated();
        $student->update($validatedData);

        return redirect()->route('students.index');
    }

    public function destroy(Student $student){
        $student->delete();

        return redirect()->route('students.index');
    }

    
}
