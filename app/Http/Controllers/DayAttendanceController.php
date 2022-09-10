<?php

namespace App\Http\Controllers;

use App\Models\DayAttendance;
use App\Repositories\DayAttendanceRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class DayAttendanceController extends Controller
{
    private DayAttendanceRepository $dayattendanceRepository;

    /**
     * @param DayAttendanceRepository $dayattendanceRepository
     */
    public function __construct(DayAttendanceRepository $dayattendanceRepository)
    {
        $this->dayattendanceRepository = $dayattendanceRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', DayAttendance::class);

        $dayattendances = $this->dayattendanceRepository->all([
            'includeSoftDeleted' => false,
            'with' => [],
            'paginate' => true,
            'paginationCount' => 15,
            'page' => 1,
            'paginationPath' => 'dayattendances.datatable'
        ]);

        return Inertia::render('DayAttendances/Index', [
            'dayattendances' => $this->dayattendanceRepository->returnResource("DayAttendance", $dayattendances),
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
        $this->authorize('create', DayAttendance::class);

        return Inertia::render('DayAttendances/Create');
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
        $this->authorize('create', DayAttendance::class);

        $this->dayattendanceRepository->create($request);

        return redirect()->route('dayattendances.index');
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
     * @param DayAttendance $dayattendance
     * @return \Inertia\Response
     * @throws AuthorizationException
     */
    public function edit(DayAttendance $dayattendance)
    {
        $this->authorize('update', $dayattendance);

        return Inertia::render('DayAttendances/Manage', [
           'dayattendance' => $dayattendance
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param DayAttendance $dayattendance
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, DayAttendance $dayattendance)
    {
        $this->authorize('update', $dayattendance);

        $this->dayattendanceRepository->update($request, $dayattendance->id);

        return redirect()->route('dayattendances.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DayAttendance $dayattendance
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(DayAttendance $dayattendance)
    {
        $this->authorize('delete', $dayattendance);

        $this->dayattendanceRepository->delete($dayattendance->id);

        return redirect()->route('dayattendances.index');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function datatable(Request $request)
    {
        return $this->dayattendanceRepository->datatable($request, [], 'dayattendances.datatable', function ($query, $filterWhereColumnValue, $search) {
            if (!empty($search)) {
                $query->where('dayattendances.id', 'LIKE', "%{$search}%")
                    ->orWhere('dayattendances.name', 'LIKE', "%{$search}%");
            }
            if ( !empty($filterWhereColumnValue) ) {
                $query = $query->where($filterWhereColumnValue);
            }
            return $query;
        });
    }
}
