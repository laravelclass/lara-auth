<?php


namespace LaravelClass\LaraAuth\Commands;

use Illuminate\Console\Command;

class LaraAuthUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraAuth:up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'serve laraAuth package';

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
        $this->call('vendor:publish',[
            '--provider' => 'LaravelClass\LaraAuth\Providers\LaraAuthProvider'
        ]);
        return 0;
    }
}
