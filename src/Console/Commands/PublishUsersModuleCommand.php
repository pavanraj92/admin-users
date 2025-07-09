<?php

namespace admin\users\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishUsersModuleCommand extends Command
{
    protected $signature = 'users:publish {--force : Force overwrite existing files}';
    protected $description = 'Publish Users module files with proper namespace transformation';

    public function handle()
    {
        $this->info('Publishing Users module files...');

        // Check if module directory exists
        $moduleDir = base_path('Modules/Users');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        // Publish with namespace transformation
        $this->publishWithNamespaceTransformation();
        
        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'user',
            '--force' => $this->option('force')
        ]);

        // Update composer autoload
        $this->updateComposerAutoload();

        $this->info('Users module published successfully!');
        $this->info('Please run: composer dump-autoload');
    }

    protected function publishWithNamespaceTransformation()
    {
        $basePath = dirname(dirname(__DIR__)); // Go up to packages/admin/users/src
        
        $filesWithNamespaces = [
            // Controllers
            $basePath . '/Controllers/UserManagerController.php' => base_path('Modules/Users/app/Http/Controllers/Admin/UserManagerController.php'),
            
            // Models
            $basePath . '/Models/User.php' => base_path('Modules/Users/app/Models/User.php'),
            $basePath . '/Models/UserRole.php' => base_path('Modules/Users/app/Models/UserRole.php'),

            // Mail
            $basePath . '/Mail/WelcomeMail.php' => base_path('Modules/Users/app/Mail/WelcomeMail.php'),
            
            // Requests
            $basePath . '/Requests/UserCreateRequest.php' => base_path('Modules/Users/app/Http/Requests/UserCreateRequest.php'),
            $basePath . '/Requests/UserUpdateRequest.php' => base_path('Modules/Users/app/Http/Requests/UserUpdateRequest.php'),
            
            // Routes
            $basePath . '/routes/web.php' => base_path('Modules/Users/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }
    }

    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\users\\Controllers;' => 'namespace Modules\\Users\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\users\\Models;' => 'namespace Modules\\Users\\app\\Models;',
            'namespace admin\\users\\Mail;' => 'namespace Modules\\Users\\app\\Mail;',
            'namespace admin\\users\\Requests;' => 'namespace Modules\\Users\\app\\Http\\Requests;',
            
            // Use statements transformations
            'use admin\\users\\Controllers\\' => 'use Modules\\Users\\app\\Http\\Controllers\\Admin\\',
            'use admin\\users\\Mail\\' => 'use Modules\\Users\\app\\Mail\\',
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
            $content = str_replace('use admin\\users\\Models\\User;', 'use Modules\\Users\\app\\Models\\User;', $content);
            $content = str_replace('use admin\\users\\Mail\\WelcomeMail;', 'use Modules\\Users\\app\\Mail\\WelcomeMail;', $content);
            $content = str_replace('use admin\\users\\Models\\UserRole;', 'use Modules\\Users\\app\\Models\\UserRole;', $content);
            $content = str_replace('use admin\\users\\Requests\\UserCreateRequest;', 'use Modules\\Users\\app\\Http\\Requests\\UserCreateRequest;', $content);
            $content = str_replace('use admin\\users\\Requests\\UserUpdateRequest;', 'use Modules\\Users\\app\\Http\\Requests\\UserUpdateRequest;', $content);
        }

        return $content;
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);

        // Add module namespace to autoload
        if (!isset($composer['autoload']['psr-4']['Modules\\Users\\'])) {
            $composer['autoload']['psr-4']['Modules\\Users\\'] = 'Modules/Users/app/';
            
            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}
