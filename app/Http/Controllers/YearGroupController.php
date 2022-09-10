<?php

namespace App\Http\Controllers;

use App\Models\YearGroup;
use App\Repositories\YearGroupRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class YearGroupController extends Controller
{
    private YearGroupRepository $yeargroupRepository;

    /**
     * @param YearGroupRepository $yeargroupRepository
     */
    public function __construct(YearGroupRepository $yeargroupRepository)
    {
        $this->yeargroupRepository = $yeargroupRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', YearGroup::class);

        $yeargroups = $this->yeargroupRepository->all([
            'includeSoftDeleted' => false,
            'with' => [],
            'paginate' => true,
            'paginationCount' => 15,
            'page' => 1,
            'paginationPath' => 'yeargroups.datatable'
        ]);

        return Inertia::render('YearGroups/Index', [
            'yeargroups' => $this->yeargroupRepository->returnResource("YearGroup", $yeargroups),
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
        $this->authorize('create', YearGroup::class);

        return Inertia::render('YearGroups/Create');
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
        $this->authorize('create', YearGroup::class);

        $this->yeargroupRepository->create($request);

        return redirect()->route('yeargroups.index');
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
     * @param YearGroup $yeargroup
     * @return \Inertia\Response
     * @throws AuthorizationException
     */
    public function edit(YearGroup $yeargroup)
    {
        $this->authorize('update', $yeargroup);

        return Inertia::render('YearGroups/Manage', [
           'yeargroup' => $yeargroup
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param YearGroup $yeargroup
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, YearGroup $yeargroup)
    {
        $this->authorize('update', $yeargroup);

        $this->yeargroupRepository->update($request, $yeargroup->id);

        return redirect()->route('yeargroups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param YearGroup $yeargroup
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(YearGroup $yeargroup)
    {
        $this->authorize('delete', $yeargroup);

        $this->yeargroupRepository->delete($yeargroup->id);

        return redirect()->route('yeargroups.index');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function datatable(Request $request)
    {
        return $this->yeargroupRepository->datatable($request, [], 'yeargroups.datatable', function ($query, $filterWhereColumnValue, $search) {
            if (!empty($search)) {
                $query->where('yeargroups.id', 'LIKE', "%{$search}%")
                    ->orWhere('yeargroups.name', 'LIKE', "%{$search}%");
            }
            if ( !empty($filterWhereColumnValue) ) {
                $query = $query->where($filterWhereColumnValue);
            }
            return $query;
        });
    }
}
