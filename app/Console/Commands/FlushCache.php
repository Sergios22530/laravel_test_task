<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Class JobModuleMake
 *
 * @property Filesystem $files
 * @package App\Console\Commands
 */
class FlushCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:flush-all {--optimize : Make cache optimize}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear application cache';

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
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('view:clear');
        $this->call('event:clear');
        $this->call('route:clear');
        $this->call('queue:clear');
        $this->call('schedule:clear-cache');

        $this->call('optimize:clear');

        $this->info('Cache cleared successfully.');

        if ($this->input->getOption('optimize')) {
//            $this->call('optimize');
            $this->call('config:cache');
            $this->warn('Optimize all cache.');
        }


        return true;
    }
}
