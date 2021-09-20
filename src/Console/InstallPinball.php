<?php

namespace Vio\Pinball\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

/**
 * Installs and scaffolds the current Pinball stack:
 * - InertiaJS
 * - Fortify
 * - Telescope
 * - Vue3
 * - Bootstrap 4.6
 * - and various other common dependencies
 *
 * Much of this code has been adapted from the InstallCommand of
 * the Laravel\Jetstream starter kit.
 *
 * @package Vio\Pinball\Console
 */
class InstallPinball extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pb:install {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Pinball stack';

    /**
     * Execute the command
     */
    public function handle()
    {
        $this->installPinballStack();
    }

    protected function installExtras()
    {
        $this->info("Installing common extras...");
        $this->requireComposerPackages([
            'laracasts/flash:^3.2',
            'aws/aws-sdk-php:^3',
            'doctrine/dbal:^3',
        ]);
    }

    /**
     * Installs the stack.
     *
     * @return void
     */
    protected function installPinballStack()
    {
        // Install Inertia...
        $this->info("Installing essential Laravel packages...");
        $this->requireComposerPackages([
            'inertiajs/inertia-laravel:^0.4.2',
            'laravel/sanctum:^2.6',
            'laravel/fortify:^1.7',
            'laravel/telescope:^4',
        ]);

        $this->installExtras();

        // Install NPM packages...
        $this->info("Adding common NPM packages to package.json...");

        // Non-dev dependencies
        $this->updateNodePackages(function ($packages) {
            return [
                '@inertiajs/inertia' => '^0.9.2',
                '@inertiajs/inertia-vue3' => '^0.4.7',
                '@inertiajs/progress' => '^0.2.5',
                'vue' => '^3.1.2',
                '@vueform/multiselect' => '^1.5.0',
                'bootstrap' => '^4.6',
                'flatpickr' => '^4.6.9',
                'autosize' => '^5.0.0',
                'font-awesome' => '^4.7.0',
                'lodash' => '^4.17.21',
                'luxon' => '^1.27.0',
                'numeral' => '^2.0.6',
                'popper.js' => '^1.16.1',
                'stacked-menu' => '^1.1.12',
                'timeago.js' => '^4.0.2',
                'axios' => '^0.21',
                'jquery' => '^3.6',
            ] + $packages;
        }, false);

        // Dev Dependencies
        $this->updateNodePackages(function ($packages) {
            return [
                '@vue/compiler-sfc' => '^3.1.2',
                'vue-loader' => '^16.2.0',
                'laravel-mix-clean' => '^0.1',
                'laravel-mix-definitions' => '^1.1',
            ] + $packages;
        });

        // Telescope...
        (new Process(['php', 'artisan', 'telescope:install'], base_path()))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });

        // Fortify...
        (new Process(['php', 'artisan', 'vendor:publish', '--provider=Laravel\Fortify\FortifyServiceProvider', '--force'], base_path()))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });

        $this->replaceInFile('// Features::emailVerification()', 'Features::emailVerification()', base_path('config/fortify.php'));

        // Sanctum...
        (new Process(['php', 'artisan', 'vendor:publish', '--provider=Laravel\Sanctum\SanctumServiceProvider', '--force'], base_path()))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });

        // Directories...
        (new Filesystem)->ensureDirectoryExists(app_path('Actions/Fortify'));
        (new Filesystem)->ensureDirectoryExists(resource_path('css'));
        (new Filesystem)->ensureDirectoryExists(resource_path('sass'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages/Auth'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views'));

        // Service Providers...
        copy(__DIR__ . '/../../stubs/app/Providers/PinballServiceProvider.php', app_path('Providers/PinballServiceProvider.php'));
        copy(__DIR__ . '/../../stubs/inertia/app/Providers/FortifyServiceProvider.php', app_path('Providers/FortifyServiceProvider.php'));

        $this->installServiceProviderAfter('AuthServiceProvider', 'PinballServiceProvider');
        $this->installServiceProviderAfter('AuthServiceProvider', 'FortifyServiceProvider');

        // Middleware...
        (new Process(['php', 'artisan', 'inertia:middleware', 'HandleInertiaRequests', '--force'], base_path()))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });

        $this->installMiddlewareAfter('SubstituteBindings::class', '\App\Http\Middleware\HandleInertiaRequests::class');

        // Models...
        copy(__DIR__ . '/../../stubs/app/Models/User.php', app_path('Models/User.php'));

        // Factories...
        // ...

        // Actions...
        copy(__DIR__ . '/../../stubs/app/Actions/Fortify/CreateNewUser.php', app_path('Actions/Fortify/CreateNewUser.php'));

        // Blade Views...
        copy(__DIR__ . '/../../stubs/inertia/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        if (file_exists(resource_path('views/welcome.blade.php'))) {
            unlink(resource_path('views/welcome.blade.php'));
        }

        // Inertia Pages...
        copy(__DIR__ . '/../../stubs/inertia/resources/js/Pages/Home.vue', resource_path('js/Pages/Home.vue'));
        copy(__DIR__ . '/../../stubs/inertia/resources/js/Pages/Error.vue', resource_path('js/Pages/Error.vue'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia/resources/js/Layouts', resource_path('js/Layouts'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia/resources/js/Pages/Auth', resource_path('js/Pages/Auth'));

        // Inertia Middleware
        copy(__DIR__ . '/../../stubs/inertia/app/Http/Middleware/HandleInertiaRequests.php', app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Routes...
        $this->replaceInFile('auth:api', 'auth:sanctum', base_path('routes/api.php'));

        copy(__DIR__ . '/../../stubs/inertia/routes/web.php', base_path('routes/web.php'));

        // Assets...
        copy(__DIR__ . '/../../stubs/webpack.mix.js', base_path('webpack.mix.js'));
        copy(__DIR__ . '/../../stubs/inertia/resources/js/app.js', resource_path('js/app.js'));
        copy(__DIR__ . '/../../stubs/inertia/resources/sass/app.scss', resource_path('sass/app.scss'));

        // Flush node_modules...
        // static::flushNodeModules();

        // Tests...
        //

        $this->line('');
        $this->info('Pinball scaffolded successfully.');
        $this->comment('This process is still experimental and not complete.');
        $this->comment('Please execute "npm install && npm run dev" to build your assets.');
    }

    /**
     * Install the service provider in the application configuration file.
     *
     * @param  string  $after
     * @param  string  $name
     * @return void
     */
    protected function installServiceProviderAfter($after, $name)
    {
        if (!Str::contains($appConfig = file_get_contents(config_path('app.php')), 'App\\Providers\\' . $name . '::class')) {
            file_put_contents(config_path('app.php'), str_replace(
                'App\\Providers\\' . $after . '::class,',
                'App\\Providers\\' . $after . '::class,' . PHP_EOL . '        App\\Providers\\' . $name . '::class,',
                $appConfig
            ));
        }
    }

    /**
     * Install the middleware to a group in the application Http Kernel.
     *
     * @param  string  $after
     * @param  string  $name
     * @param  string  $group
     * @return void
     */
    protected function installMiddlewareAfter($after, $name, $group = 'web')
    {
        $httpKernel = file_get_contents(app_path('Http/Kernel.php'));

        $middlewareGroups = Str::before(Str::after($httpKernel, '$middlewareGroups = ['), '];');
        $middlewareGroup = Str::before(Str::after($middlewareGroups, "'$group' => ["), '],');

        if (!Str::contains($middlewareGroup, $name)) {
            $modifiedMiddlewareGroup = str_replace(
                $after . ',',
                $after . ',' . PHP_EOL . '            ' . $name . ',',
                $middlewareGroup,
            );

            file_put_contents(app_path('Http/Kernel.php'), str_replace(
                $middlewareGroups,
                str_replace($middlewareGroup, $modifiedMiddlewareGroup, $middlewareGroups),
                $httpKernel
            ));
        }
    }

    /**
     * Installs the given Composer Packages into the application.
     *
     * @param  mixed  $packages
     * @return void
     */
    protected function requireComposerPackages($packages)
    {
        $composer = $this->option('composer');

        if ($composer !== 'global') {
            $command = ['php', $composer, 'require'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    /**
     * Update the "package.json" file.
     *
     * @param  callable  $callback
     * @param  bool  $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {
        if (!file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    /**
     * Delete the "node_modules" directory and remove the associated lock files.
     *
     * @return void
     */
    protected static function flushNodeModules()
    {
        tap(new Filesystem, function ($files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('yarn.lock'));
            $files->delete(base_path('package-lock.json'));
        });
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
