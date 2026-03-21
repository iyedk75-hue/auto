<?php

namespace App\Http\Controllers;

use App\Models\AutoSchool;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AdminAutoSchoolAdminController extends Controller
{
    public function index(AutoSchool $autoSchool): View
    {
        return view('admin.auto-school-admins.index', [
            'school' => $autoSchool->loadCount(['admins', 'candidates']),
            'admins' => $autoSchool->admins()
                ->latest()
                ->paginate(12),
        ]);
    }

    public function create(AutoSchool $autoSchool): View
    {
        return view('admin.auto-school-admins.create', [
            'school' => $autoSchool,
            'admin' => new User(),
        ]);
    }

    public function store(Request $request, AutoSchool $autoSchool): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
            'role' => User::ROLE_ADMIN,
            'auto_school_id' => $autoSchool->id,
            'email_verified_at' => now(),
            'registered_at' => now(),
        ]);

        return redirect()
            ->route('admin.auto-schools.admins.index', $autoSchool)
            ->with('status', 'Compte admin créé avec succès.');
    }

    public function edit(AutoSchool $autoSchool, User $admin): View
    {
        $this->ensureSchoolAdmin($autoSchool, $admin);

        return view('admin.auto-school-admins.edit', [
            'school' => $autoSchool,
            'admin' => $admin,
        ]);
    }

    public function update(Request $request, AutoSchool $autoSchool, User $admin): RedirectResponse
    {
        $this->ensureSchoolAdmin($autoSchool, $admin);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
            'auto_school_id' => $autoSchool->id,
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        $admin->update($payload);

        return redirect()
            ->route('admin.auto-schools.admins.index', $autoSchool)
            ->with('status', 'Compte admin mis à jour.');
    }

    public function destroy(AutoSchool $autoSchool, User $admin): RedirectResponse
    {
        $this->ensureSchoolAdmin($autoSchool, $admin);

        $admin->delete();

        return redirect()
            ->route('admin.auto-schools.admins.index', $autoSchool)
            ->with('status', 'Compte admin supprimé.');
    }

    private function ensureSchoolAdmin(AutoSchool $autoSchool, User $admin): void
    {
        abort_unless($admin->isSchoolAdmin() && (int) $admin->auto_school_id === (int) $autoSchool->id, 404);
    }
}