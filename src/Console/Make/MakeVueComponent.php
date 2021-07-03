<?php

namespace Vio\Pinball\Console\Make;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Vio\Pinball\Helpers\Stub;

class MakeVueComponent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinball:make:vue
                            {name : Fully qualified name of the component to generate}
                            {--force : Overwrite existing component if it exists}
                            {--base=Components : Base directory relative to resources/js}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a Vue3 component.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $base = $this->option('base') ?? 'Components';
        $parts = pathinfo($name);
        $dir = $parts['dirname'];
        $filename = $parts['filename'];
        $component = Str::studly($filename);

        $path = ['js'];

        if($base){
            $path[] = trim($base, '/');
        }

        if($dir !== '.'){
            $path[] = trim($dir, '/');
        }

        $path[] = "{$component}.vue";

        $target = implode('/', $path);
        $targetPath = resource_path($target);

        $filesystem = new Filesystem();
        $targetDir = $filesystem->dirname($targetPath);
        $filesystem->ensureDirectoryExists($targetDir);

        if (!$this->option('force') && $filesystem->exists($targetPath)) {
            $this->error("A file already exists at {$targetPath}");
            return 0;
        }

        $pathToStub = PINBALL_STUB_DIR.'/templates/resources/js/Components/EmptyComponent.vue.stub';
        $stub = file_get_contents($pathToStub);
        $stub = Stub::interpolate($stub, ['name' => $component]);
        file_put_contents($targetPath, $stub);

        $this->line("Generated Vue component at resources/{$target}");
        return 0;
    }
}
