<?php

namespace Vio\Pinball\Console\Make;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Vio\Pinball\Helpers\Stub;

class MakeInertiaPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinball:make:inertiaPage 
                            {name : Fully qualified name of the page to generate}
                            {--force : Overwrite existing page if it exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates an empty InertiaJS page component (Vue3).';

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

        $args = [
            'name' => $name,
            '--base' => 'Pages',
            '--force' => $this->option('force'),
        ];

        $this->call(MakeVueComponent::class, $args);

        return 0;
    }
}
