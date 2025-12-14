<?php

namespace App\Providers;

use App\Models\ClassModel;
use App\Models\Department;
use App\Models\Score;
use App\Policies\ClassModelPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\ScorePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ClassModel::class => ClassModelPolicy::class,
        Department::class => DepartmentPolicy::class,
        Score::class => ScorePolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Register policies
        Gate::policy(ClassModel::class, ClassModelPolicy::class);
        Gate::policy(Department::class, DepartmentPolicy::class);
        Gate::policy(Score::class, ScorePolicy::class);
    }
}
