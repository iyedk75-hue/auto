<?php

namespace App\Http\Controllers;

use App\Models\AutoSchool;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAutoSchoolController extends Controller
{
    public function index(): View
    {
        return view('admin.auto-schools.index', [
            'schools' => AutoSchool::query()
                ->withCount([
                    'admins',
                    'candidates',
                ])
                ->orderBy('name')
                ->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.auto-schools.create', [
            'school' => new AutoSchool(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'whatsapp_phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        AutoSchool::create($validated);

        return redirect()
            ->route('admin.auto-schools.index')
            ->with('status', 'Auto-école créée avec succès.');
    }

    public function edit(AutoSchool $autoSchool): View
    {
        return view('admin.auto-schools.edit', [
            'school' => $autoSchool,
        ]);
    }

    public function update(Request $request, AutoSchool $autoSchool): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'whatsapp_phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $autoSchool->update($validated);

        return redirect()
            ->route('admin.auto-schools.index')
            ->with('status', 'Auto-école mise à jour.');
    }

    public function destroy(AutoSchool $autoSchool): RedirectResponse
    {
        $autoSchool->delete();

        return redirect()
            ->route('admin.auto-schools.index')
            ->with('status', 'Auto-école supprimée.');
    }
}
