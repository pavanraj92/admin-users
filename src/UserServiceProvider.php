<?php

namespace admin\users;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Load routes, views, migrations from the package  
        $this->loadViewsFrom([
            base_path('Modules/Users/resources/views'), // Published module views first
            resource_path('views/admin/user'), // Published views second
            __DIR__ . '/../resources/views'      // Package views as fallback
        ], 'user');

        $this->mergeConfigFrom(__DIR__.'/../config/user.php', 'user.constants');
        
        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/Users/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/Users/resources/views'), 'users-module');
        }
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // Also load migrations from published module if they exist
        if (is_dir(base_path('Modules/Users/database/migrations'))) {
            $this->loadMigrationsFrom(base_path('Modules/Users/database/migrations'));
        }

        // Only publish automatically during package installation, not on every request
        // Use 'php artisan users:publish' command for manual publishing
        // $this->publishWithNamespaceTransformation();
        
        // Standard publishing for non-PHP files
        $this->publishes([
            __DIR__ . '/../database/migrations' => base_path('Modules/Users/database/migrations'),
            __DIR__ . '/../resources/views' => base_path('Modules/Users/resources/views/'),
            __DIR__ . '/../database/seeders/SeedUserRolesSeeder.php' => base_path('Modules/Users/database/seeders/SeedUserRolesSeeder.php'),
        ], 'user');
       
        $this->registerAdminRoutes();

    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $admin = DB::table('admins')
            ->orderBy('created_at', 'asc')
            ->first();
            
        $slug = $admin->website_slug ?? 'admin';

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/routes/web.php');
            });
    }

    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\users\Console\Commands\PublishUsersModuleCommand::class,
                \admin\users\Console\Commands\CheckModuleStatusCommand::class,
                \admin\users\Console\Commands\DebugUsersCommand::class,
                \admin\users\Console\Commands\TestViewResolutionCommand::class,
            ]);
        }
    }

    /**
     * Publish files with namespace transformation
     */
    protected function publishWithNamespaceTransformation()
    {
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            __DIR__ . '/../src/Controllers/UserManagerController.php' => base_path('Modules/Users/app/Http/Controllers/Admin/UserManagerController.php'),
            
            // Models
            __DIR__ . '/../src/Models/User.php' => base_path('Modules/Users/app/Models/User.php'),
            __DIR__ . '/../src/Models/UserRole.php' => base_path('Modules/Users/app/Models/UserRole.php'),

              // Mail
              __DIR__ . '/../src/Mail/WelcomeMail.php' => base_path('Modules/Users/app/Mail/WelcomeMail.php'),
            
            // Requests
            __DIR__ . '/../src/Requests/UserCreateRequest.php' => base_path('Modules/Users/app/Http/Requests/UserCreateRequest.php'),
            __DIR__ . '/../src/Requests/UserUpdateRequest.php' => base_path('Modules/Users/app/Http/Requests/UserUpdateRequest.php'),
            
            // Routes
            __DIR__ . '/routes/web.php' => base_path('Modules/Users/routes/web.php'),

              // Seeders
             __DIR__ . '/../database/seeders/SeedUserRolesSeeder.php' => base_path('Modules/Users/database/seeders/SeedUserRolesSeeder.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                // Create destination directory if it doesn't exist
                File::ensureDirectoryExists(dirname($destination));
                
                // Read the source file
                $content = File::get($source);
                
                // Transform namespaces based on file type
                $content = $this->transformNamespaces($content, $source);
                
                // Write the transformed content to destination
                File::put($destination, $content);
            }
        }
    }

    /**
     * Transform namespaces in PHP files
     */
    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\users\\Controllers;' => 'namespace Modules\\Users\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\users\\Models;' => 'namespace Modules\\Users\\app\\Models;',
            'namespace admin\\users\\Mail;' => 'namespace Modules\\Users\\app\\Mail;',
            'namespace admin\\users\\Requests;' => 'namespace Modules\\Users\\app\\Http\\Requests;',
            'namespace packages\\admin\\users\\database\\seeders;' => 'namespace Modules\\Users\\database\\seeders;',
            
            // Use statements transformations
            'use admin\\users\\Controllers\\' => 'use Modules\\Users\\app\\Http\\Controllers\\Admin\\',
            'use admin\\users\\Models\\' => 'use Modules\\Users\\app\\Models\\',
            'use admin\\users\\Mail\\' => 'use Modules\\Users\\app\\Mail\\',
            'use admin\\users\\Requests\\' => 'use Modules\\Users\\app\\Http\\Requests\\',
            
            // Class references in routes
            'admin\\users\\Controllers\\UserManagerController' => 'Modules\\Users\\app\\Http\\Controllers\\Admin\\UserManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = $this->transformControllerNamespaces($content);
        } elseif (str_contains($sourceFile, 'Models')) {
            $content = $this->transformModelNamespaces($content);
        } elseif (str_contains($sourceFile, 'Mail')) {
            $content = $this->transformMailNamespaces($content);
        } elseif (str_contains($sourceFile, 'Requests')) {
            $content = $this->transformRequestNamespaces($content);
        } elseif (str_contains($sourceFile, 'routes')) {
            $content = $this->transformRouteNamespaces($content);
        } elseif (str_contains($sourceFile, 'seeders')) {
            $content = $this->transformSeederNamespaces($content);
        }

        return $content;
    }

    /**
     * Transform controller-specific namespaces
     */
    protected function transformControllerNamespaces($content)
    {
        // Update use statements for models and requests
        $content = str_replace(
            'use admin\\users\\Models\\User;',
            'use Modules\\Users\\app\\Models\\User;',
            $content
        );

        $content = str_replace(
            'use admin\\users\\Models\\UserRole;',
            'use Modules\\Users\\app\\Models\\UserRole;',
            $content
        );

        $content = str_replace(
            'use admin\\users\\Mail\\WelcomeMail;',
            'use Modules\\Users\\app\\Mail\\WelcomeMail;',
            $content
        );
        
        $content = str_replace(
            'use admin\\users\\Requests\\UserCreateRequest;',
            'use Modules\\Users\\app\\Http\\Requests\\UserCreateRequest;',
            $content
        );
        
        $content = str_replace(
            'use admin\\users\\Requests\\UserUpdateRequest;',
            'use Modules\\Users\\app\\Http\\Requests\\UserUpdateRequest;',
            $content
        );

        return $content;
    }

    /**
     * Transform model-specific namespaces
     */
    protected function transformModelNamespaces($content)
    {
        // Any model-specific transformations
        return $content;
    }

       /**
     * Transform mail-specific namespaces
     */
    protected function transformMailNamespaces($content)
    {
        // Any mail-specific transformations
        return $content;
    }


    /**
     * Transform request-specific namespaces
     */
    protected function transformRequestNamespaces($content)
    {
        // Any request-specific transformations
        return $content;
    }

    protected function transformSeederNamespaces($content)
    {
        // Add any seeder-specific transformations here if needed
        return $content;
    }

    /**
     * Transform route-specific namespaces
     */
    protected function transformRouteNamespaces($content)
    {
        // Update controller references in routes
        $content = str_replace(
            'admin\\users\\Controllers\\UserManagerController',
            'Modules\\Users\\app\\Http\\Controllers\\Admin\\UserManagerController',
            $content
        );

        return $content;
    }
}

