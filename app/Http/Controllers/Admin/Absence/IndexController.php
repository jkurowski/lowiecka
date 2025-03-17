<?php

namespace App\Http\Controllers\Admin\Absence;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAbsenceRequest;
use App\Models\Absence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function index()
    {
        $absences = Absence::with('user')->get();
        return view('admin.absences.index', compact('absences'));
    }

    public function create()
    {
        $absence = Absence::make();
        $absence->start_date = Carbon::now()->format('Y-m-d\TH:i');
        $absence->end_date = Carbon::now()->addHours(8)->format('Y-m-d\TH:i');
        $users = $this->getUsersForSelect();
        return view('admin.absences.edit', compact('absence', 'users'));
    }

    public function store(StoreAbsenceRequest $request)
    {
        $absence = Absence::create($request->validated());
        return redirect()->route('admin.absences.index')->with('success', 'Nieobecność została dodana');
    }
    public function edit(Absence $absence)
    {
        $users = $this->getUsersForSelect();
        return view('admin.absences.edit', compact('absence', 'users'));
    }
    public function update(StoreAbsenceRequest $request, Absence $absence)
    {
        $absence->update($request->validated());
        return redirect()->route('admin.absences.index')->with('success', 'Nieobecność została zaktualizowana');
    }
    public function destroy(Request $request, Absence $absence)
    {
        $absence->delete();

        return response()->json(['success' => true]);
    }

    private function getUsersForSelect()
    {
        $users = User::all()->map(function (User $user) {
            return [
                'id' => $user->id,
                'name' => $user->name . ' ' . $user->surname,
            ];
        })->toArray();
        return array_column($users, 'name', 'id');
    }
}
