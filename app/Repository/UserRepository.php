<?php

namespace App\Repository;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected $user;
    protected $model ;
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->model = User::query();
    }

    public function create(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return $user;
    }

    public function getUsersFromYesterday()
    {
        return $this->user->where('created_at', '>=', Carbon::yesterday())->get();
    }
}
