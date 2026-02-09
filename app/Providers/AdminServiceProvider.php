<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Menu;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!request()->Is('admin-panel*')) {
            return;
        }

        $this->viewMenu();
        $this->permission();
    }

    protected function viewMenu(): void
    {
        \View::composer('*', function ($view) {
            if (!auth()->check()) return;

            $permissionIds = auth()->user()->role->permissions->pluck('id');

            $menus = Menu::with(['children' => function ($q) use ($permissionIds) {
                        $q->whereHas('permissions', function ($q) use ($permissionIds) {
                            $q->whereIn('permissions.id', $permissionIds);
                        });
                    }])
                    ->where('is_active', 1)
                    ->whereNull('parent_id')
                    ->WhereHas('permissions', function ($q) use ($permissionIds) {
                        $q->whereIn('permissions.id', $permissionIds);
                    })
                    ->orderBy('order', 'asc')
                    ->get();

            $view->with('sideMenus', $menus);
        });
    }

    protected function permission(): void
    {
        Gate::before(function ($user, $ability) {
            if (!$user->role) return false;
            
            return $user->role->permissions->contains('slug', $ability);
        });
    }
}
