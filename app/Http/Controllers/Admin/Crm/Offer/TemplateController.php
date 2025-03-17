<?php

namespace App\Http\Controllers\Admin\Crm\Offer;

use App\Http\Controllers\Controller;

// CMS
use App\Http\Requests\OfferTemplateFormRequest;
use App\Repositories\EmailTemplateRepository;

use App\Helpers\TemplateTypes;

use App\Models\EmailTemplate;
use App\Models\Investment;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    private EmailTemplateRepository $repository;

    public function __construct(EmailTemplateRepository $repository)
    {
        //        $this->middleware('permission:box-list|box-create|box-edit|box-delete', [
        //            'only' => ['index','store']
        //        ]);
        //        $this->middleware('permission:box-create', [
        //            'only' => ['create','store']
        //        ]);
        //        $this->middleware('permission:box-edit', [
        //            'only' => ['edit','update']
        //        ]);
        //        $this->middleware('permission:box-delete', [
        //            'only' => ['destroy']
        //        ]);

        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emailTemplates = DB::table('email_templates')
            ->get()
            ->map(function ($template) {
                // Decode the meta field
                $meta = json_decode($template->meta, true);

                // Check if template_type is 20
                if (isset($meta['template_type']) && $meta['template_type'] == TemplateTypes::OFFER) {
                    // Retrieve the user associated with the email template
                    $user = DB::table('users')->where('id', $template->user_id)->first();

                    // Retrieve the investment associated with the email template
                    $investment = DB::table('investments')->where('id', $template->investment_id)->first();

                    // Add user and investment data to the template object
                    $template->user = $user ? $user->name : null; // Assuming the 'users' table has a 'name' field
                    $template->investment = $investment ? $investment->name : null; // Assuming 'investments' table has a 'name' field

                    return $template;
                }

                return null; // If template_type is not 20
            })
            ->filter();

        return view('admin.crm.offer.template.index',
        [
            'emailTemplates' => $emailTemplates
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.crm.offer.template.form', [
            'cardTitle' => 'Dodaj szablon',
            'backButton' => route('admin.crm.offer.templates'),
            'investments' => Investment::all()->pluck('name', 'id')->prepend('Brak', null)
        ])->with('entry', EmailTemplate::make());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OfferTemplateFormRequest $request)
    {
        $validatedData = $request->validated();
        $user_id = auth()->id();
        $validatedData['user_id'] = $user_id;
        $validatedData['meta'] = ['template_type' => TemplateTypes::OFFER];
        $this->repository->create($validatedData);

        return redirect(route('admin.crm.offer.templates'))->with('success', 'Nowy szablon dodany');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $email_template = $this->repository->find($id);

        $emailTemplateType = $generator->meta ?? (is_array($email_template->meta) ? $email_template->meta : json_decode($email_template->meta, true));
        $templateType = TemplateTypes::mapTypeToLayout($emailTemplateType['template_type']);

        return view('admin.email.generator.form', [
            'id' => $email_template->id,
            'cardTitle' => 'Kreator szablonu: ' . $email_template->name,
            'investment_id' => $email_template->investment_id,
            'backButton' => route('admin.crm.offer.templates'),
            'template' => $email_template->content,
            'template_json' => json_decode($email_template->content, true) ?? '',
            'template_type' => $templateType,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        return view('admin.crm.offer.template.form', [
            'entry' => EmailTemplate::find($id),
            'cardTitle' => 'Edytuj szablon',
            'investments' => Investment::all()->pluck('name', 'id')->prepend('Brak', null),
            'backButton' => route('admin.crm.offer.templates')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OfferTemplateFormRequest $request, int $id)
    {
        $email_template = $this->repository->find($id);

        $validatedData = $request->validated();
        $user_id = auth()->id();
        $validatedData['user_id'] = $user_id;
        $email_template->update($validatedData);

        return redirect(route('admin.crm.offer.templates'))->with('success', 'Szablon zaktualizowany');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }
}
