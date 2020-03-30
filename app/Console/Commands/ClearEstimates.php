<?php

namespace App\Console\Commands;

use App\Models\Estimate;
use Illuminate\Console\Command;

class ClearEstimates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:estimates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes all estimates and add an example estimate (works only on preview mode)';

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
     * @return mixed
     */
    public function handle()
    {
        // Don't remove this line to prevent running out of preview mode
        if(!config('app.preview')) return;

        Estimate::all()->each->forceDelete();

        $this->call('db:seed', [
            '--class' => 'PreviewEstimatesSeeder',
            '--force' => true,
        ]);
    }
}
