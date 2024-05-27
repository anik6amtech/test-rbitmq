<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class InterfaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->bindInterfaceWithRepository();
    }

    private function bindInterfaceWithRepository(): void
    {
        $repositoryFiles = File::glob(app_path('Contracts/Repositories/*.php'));

        foreach ($repositoryFiles as $file) {
            $interface = 'App\Contracts\Interfaces\\' . basename($file, '.php') . 'Interface';
            $repository = 'App\Contracts\Repositories\\' . basename($file, '.php');

            if (interface_exists($interface) && class_exists($repository)) {
                $this->app->bind($interface, $repository);
            }
        }
    }

    public function boot(): void
    {
        //
    }
}
