<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsAuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', ['users' => User::query()->orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12'],
        ]);
        $data['email'] = mb_strtolower(trim($data['email']));
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true;
        $user = User::query()->create($data);
        $this->audit($request, 'user.create', $user);

        return back()->with('success', 'User account created.');
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate(['password' => ['required', 'string', 'min:12']]);
        $user->forceFill(['password' => Hash::make($data['password'])])->save();
        DB::table('sessions')->where('user_id', $user->id)->delete();
        $this->audit($request, 'user.password', $user);

        return back()->with('success', 'Password updated. The user will need to sign in again.');
    }

    public function setActive(Request $request, User $user): RedirectResponse
    {
        $active = $request->boolean('active');
        $actor = $request->user();
        if (!$active && $actor?->is($user)) {
            return back()->withErrors(['user' => 'You cannot deactivate your own account.']);
        }
        if (!$active && User::query()->where('is_active', true)->where('id', '<>', $user->id)->count() === 0) {
            return back()->withErrors(['user' => 'The last active account cannot be deactivated.']);
        }

        $user->forceFill(['is_active' => $active])->save();
        if (!$active) {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }
        $this->audit($request, $active ? 'user.activate' : 'user.deactivate', $user);

        return back()->with('success', $active ? 'User account reactivated.' : 'User account deactivated.');
    }

    private function audit(Request $request, string $action, User $user): void
    {
        CmsAuditLog::query()->create([
            'user_id' => $request->user()?->id,
            'action' => $action,
            'entity_type' => 'user',
            'entity_key' => (string) $user->id,
            'metadata' => ['email' => $user->email],
            'created_at' => now(),
        ]);
    }
}
