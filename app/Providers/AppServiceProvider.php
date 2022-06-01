<?php

namespace App\Providers;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Response::macro('serverError', function ($data = [
            'message' => 'Server error',
        ]) {
            return response()->json($data, 500);
        });

        Response::macro('created', function ($data = [
            'message' => 'Record Created Successfully.',
        ]) {
            return response()->json($data, 201);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
