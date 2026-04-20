<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('created_at')->get()->toArray();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create', [
            'old' => session()->pull('old', []),
        ]);
    }

    public function store(Request $request)
    {
        $data = [
            'username'  => trim($request->input('username', '')),
            'password'  => $request->input('password', ''),
            'full_name' => trim($request->input('full_name', '')),
            'email'     => trim($request->input('email', '')) ?: null,
            'role'      => $request->input('role', 'staff'),
        ];

        if (empty($data['username']) || empty($data['password']) || empty($data['full_name'])) {
            session(['old' => $request->all()]);
            return redirect()->route('users.create')->with('error', 'All fields are required.');
        }

        if (strlen($data['password']) < 6) {
            session(['old' => $request->all()]);
            return redirect()->route('users.create')->with('error', 'Password must be at least 6 characters.');
        }

        if (!in_array($data['role'], ['admin', 'staff'])) {
            $data['role'] = 'staff';
        }

        if (User::where('username', $data['username'])->exists()) {
            session(['old' => $request->all()]);
            return redirect()->route('users.create')->with('error', 'Username already exists. Please choose another.');
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $user = User::create($data);

        AuditLog::log('created', 'user', $user->id,
            "Created user account \"{$data['username']}\" ({$data['full_name']}) with role: {$data['role']}."
        );

        return redirect()->route('users.index')->with('success', 'User account for "' . $data['username'] . '" created successfully.');
    }

    public function edit(int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        return view('users.edit', ['user' => $user->toArray()]);
    }

    public function update(Request $request, int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        $data = [
            'username'  => trim($request->input('username', '')),
            'password'  => $request->input('password', ''),
            'full_name' => trim($request->input('full_name', '')),
            'email'     => trim($request->input('email', '')) ?: null,
            'role'      => $request->input('role', 'staff'),
        ];

        if (empty($data['username']) || empty($data['full_name'])) {
            return redirect()->route('users.edit', $id)->with('error', 'Username and full name are required.');
        }

        if (!empty($data['password']) && strlen($data['password']) < 6) {
            return redirect()->route('users.edit', $id)->with('error', 'Password must be at least 6 characters.');
        }

        if (User::where('username', $data['username'])->where('id', '!=', $id)->exists()) {
            return redirect()->route('users.edit', $id)->with('error', 'Username already exists. Please choose another.');
        }

        $updateData = [
            'username'  => $data['username'],
            'full_name' => $data['full_name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $user->update($updateData);

        AuditLog::log('updated', 'user', $id,
            "Updated user account \"{$data['username']}\" ({$data['full_name']}) — role: {$data['role']}."
        );

        return redirect()->route('users.edit', $id)->with('success', 'User account updated successfully.');
    }

    public function delete(Request $request, int $id)
    {
        $currentUserId = session('user.id');

        if ($id === (int) $currentUserId) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        AuditLog::log('deleted', 'user', $id,
            "Deleted user account \"{$user->username}\" ({$user->full_name})."
        );

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User account deleted successfully.');
    }
}
