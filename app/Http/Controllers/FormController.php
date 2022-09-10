<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Repositories\FormRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class FormController extends Controller
{
    private FormRepository $formRepository;

    /**
     * @param FormRepository $formRepository
     */
    public function __construct(FormRepository $formRepository)
    {
        $this->formRepository = $formRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Form::class);

        $forms = $this->formRepository->all([
            'includeSoftDeleted' => false,
            'with' => [],
            'paginate' => true,
            'paginationCount' => 15,
            'page' => 1,
            'paginationPath' => 'forms.datatable'
        ]);

        return Inertia::render('Forms/Index', [
            'forms' => $this->formRepository->returnResource("Form", $forms),
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
        $this->authorize('create', Form::class);

        return Inertia::render('Forms/Create');
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
        $this->authorize('create', Form::class);

        $this->formRepository->create($request);

        return redirect()->route('forms.index');
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
     * @param Form $form
     * @return \Inertia\Response
     * @throws AuthorizationException
     */
    public function edit(Form $form)
    {
        $this->authorize('update', $form);

        return Inertia::render('Forms/Manage', [
           'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Form $form
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, Form $form)
    {
        $this->authorize('update', $form);

        $this->formRepository->update($request, $form->id);

        return redirect()->route('forms.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Form $form
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Form $form)
    {
        $this->authorize('delete', $form);

        $this->formRepository->delete($form->id);

        return redirect()->route('forms.index');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function datatable(Request $request)
    {
        return $this->formRepository->datatable($request, [], 'forms.datatable', function ($query, $filterWhereColumnValue, $search) {
            if (!empty($search)) {
                $query->where('forms.id', 'LIKE', "%{$search}%")
                    ->orWhere('forms.name', 'LIKE', "%{$search}%");
            }
            if ( !empty($filterWhereColumnValue) ) {
                $query = $query->where($filterWhereColumnValue);
            }
            return $query;
        });
    }
}
