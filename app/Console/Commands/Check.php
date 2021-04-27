<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Check extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check requirements';

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
        // check ocrmypdf
        if (`which ocrmypdf`) {
            $this->info('OCRMYPDF found!');
            $cocrmypf_sys = trim(`which ocrmypdf`);
            $ocrmypdf_your = env('BIN_OCRMYPDF', '/usr/bin/ocrmypdf');
            if( $cur != $ocrmypdf_your){
                $this->error("OCRMYPDF found in $cocrmypf_sys, but you .env not set properly, your BIN_OCRMYPDF is $ocrmypdf_your.");
            }
        }else{
            $this->error('OCRMYPDF NOT FOUND! Please Install OCRMYPDF.');
        }
        $this->info('The command was successful!');
        return 0;
    }
}
