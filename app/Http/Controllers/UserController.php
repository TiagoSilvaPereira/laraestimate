<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CreateUser;
use App\Services\UpdateUser;
use Illuminate\Http\Request;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    
    public function index(Request $request)
    {
        $this->authorize('view-any', [User::class]);

        $search = $request->search ?? '';
        $users = User::searchLatestPaginated($search);

        return view('users.index', compact('search', 'users'));
    }

    public function create()
    {
        $this->authorize('create', [User::class]);

        return view('users.create');
    }

    public function store(UserStoreRequest $request, CreateUser $create)
    {
        $this->authorize('create', User::class);

        $userData = $request->all();
        $user = $create->execute($userData);

        return redirect()->route('users.edit', compact('user'));
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, User $user, UpdateUser $update)
    {
        $this->authorize('update', $user);

        $data = $request->all();

        $update->execute($user, $data);

        return redirect()->route('users.edit', compact('user'));
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index');
    }
}
