<?php

namespace App\Services;

use App\Services\Interfaces\UserServiceInterface;
use App\Traits\TrackDeletions;
use App\Models\User;

class UserService implements UserServiceInterface
{
    use TrackDeletions;

    public function create(array $data): User
    {
        $data['password'] = \Hash::make($data['password']);
        $data['created_by'] = auth()->id();

        $user = User::create($data);

        return $user;
    }

    public function update(int $id, array $data): User
    {
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = \Hash::make($data['password']);
        }
        $data['updated_by'] = auth()->id();

        $user = User::findOrFail($id);
        $user->update($data);

        return $user;
    }

    public function delete(int $id): void
    {
        User::find($id)->delete();
    }
}