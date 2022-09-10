<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Repositories\StudentRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class StudentController extends Controller
{
    private StudentRepository $studentRepository;

    /**
     * @param StudentRepository $studentRepository
     */
    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Student::class);

        $students = $this->studentRepository->all([
            'includeSoftDeleted' => false,
            'with' => [],
            'paginate' => true,
            'paginationCount' => 15,
            'page' => 1,
            'paginationPath' => 'students.datatable'
        ]);

        return Inertia::render('Students/Index', [
            'students' => $this->studentRepository->returnResource("Student", $students),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Student::class);

        return Inertia::render('Students/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Student::class);

        $this->studentRepository->create($request);

        return redirect()->route('students.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Student $student
     * @return \Inertia\Response
     * @throws AuthorizationException
     */
    public function edit(Student $student)
    {
        $this->authorize('update', $student);

        return Inertia::render('Students/Manage', [
           'student' => $student
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Student $student
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, Student $student)
    {
        $this->authorize('update', $student);

        $this->studentRepository->update($request, $student->id);

        return redirect()->route('students.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Student $student
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);

        $this->studentRepository->delete($student->id);

        return redirect()->route('students.index');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function datatable(Request $request)
    {
        return $this->studentRepository->datatable($request, [], 'students.datatable', function ($query, $filterWhereColumnValue, $search) {
            if (!empty($search)) {
                $query->where('students.id', 'LIKE', "%{$search}%")
                    ->orWhere('students.name', 'LIKE', "%{$search}%");
            }
            if ( !empty($filterWhereColumnValue) ) {
                $query = $query->where($filterWhereColumnValue);
            }
            return $query;
        });
    }
}
