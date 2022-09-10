<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Repositories\TeacherRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class TeacherController extends Controller
{
    private TeacherRepository $teacherRepository;

    /**
     * @param TeacherRepository $teacherRepository
     */
    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Teacher::class);

        $teachers = $this->teacherRepository->all([
            'includeSoftDeleted' => false,
            'with' => [],
            'paginate' => true,
            'paginationCount' => 15,
            'page' => 1,
            'paginationPath' => 'teachers.datatable'
        ]);

        return Inertia::render('Teachers/Index', [
            'teachers' => $this->teacherRepository->returnResource("Teacher", $teachers),
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
        $this->authorize('create', Teacher::class);

        return Inertia::render('Teachers/Create');
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
        $this->authorize('create', Teacher::class);

        $this->teacherRepository->create($request);

        return redirect()->route('teachers.index');
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
     * @param Teacher $teacher
     * @return \Inertia\Response
     * @throws AuthorizationException
     */
    public function edit(Teacher $teacher)
    {
        $this->authorize('update', $teacher);

        return Inertia::render('Teachers/Manage', [
           'teacher' => $teacher
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Teacher $teacher
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, Teacher $teacher)
    {
        $this->authorize('update', $teacher);

        $this->teacherRepository->update($request, $teacher->id);

        return redirect()->route('teachers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Teacher $teacher
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Teacher $teacher)
    {
        $this->authorize('delete', $teacher);

        $this->teacherRepository->delete($teacher->id);

        return redirect()->route('teachers.index');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function datatable(Request $request)
    {
        return $this->teacherRepository->datatable($request, [], 'teachers.datatable', function ($query, $filterWhereColumnValue, $search) {
            if (!empty($search)) {
                $query->where('teachers.id', 'LIKE', "%{$search}%")
                    ->orWhere('teachers.name', 'LIKE', "%{$search}%");
            }
            if ( !empty($filterWhereColumnValue) ) {
                $query = $query->where($filterWhereColumnValue);
            }
            return $query;
        });
    }
}
