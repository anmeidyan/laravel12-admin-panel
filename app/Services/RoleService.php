<?php

namespace App\Services;

use App\Services\Interfaces\RoleServiceInterface;
use App\Traits\TrackDeletions;
use App\Models\Role;

class RoleService implements RoleServiceInterface
{
    use TrackDeletions;

    public function create(array $data): Role
    {
        $data['slug'] = \Str::slug($data['name']);
        $data['created_by'] = auth()->id();
        $role = Role::create($data);

        return $role;
    }

    public function update(int $id, array $data): Role
    {
        $data['slug'] = \Str::slug($data['name']);
        $data['updated_by'] = auth()->id();
    
        $role = Role::findOrFail($id);
        $role->update($data);

        if (isset($data['permission_ids'])) {
            $role->permissions()->sync($data['permission_ids']);
        }

        return $role;
    }

    public function delete(int $id): void
    {
        Role::find($id)->delete();
    }
}