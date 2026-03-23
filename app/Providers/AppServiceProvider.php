<?php

namespace App\Providers;

use App\Models\LeaveRequest;
use App\Models\OtRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            $view->with([
                'sidebarOtPending'    => OtRequest::pending()->count(),
                'sidebarLeavePending' => LeaveRequest::pending()->count(),
            ]);
        });
    }
}
