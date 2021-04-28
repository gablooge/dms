<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Solarium\Client;
use Solarium\Core\Client\Adapter\Curl;
use Symfony\Component\EventDispatcher\EventDispatcher; 
use Illuminate\Support\Facades\DB;

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
        // Check PDF-TO-TEXT
        if (`which pdftotext`) {
            $this->info('PDF-TO-TEXT found!');
        }else{
            $this->error('PDF-TO-TEXT NOT FOUND! Please Install PDF-TO-TEXT.');
        }
        // check ocrmypdf
        if (`which ocrmypdf`) {
            $this->info('OCRMYPDF found!');
            $cocrmypf_sys = trim(`which ocrmypdf`);
            $ocrmypdf_your = env('BIN_OCRMYPDF', '/usr/bin/ocrmypdf');
            if( $cocrmypf_sys != $ocrmypdf_your){
                $this->error("OCRMYPDF found in $cocrmypf_sys, but you .env not set properly, your BIN_OCRMYPDF is $ocrmypdf_your.");
            }
        }else{
            $this->error('OCRMYPDF NOT FOUND! Please Install OCRMYPDF.');
        }
        $this->info('The command was successful!');
        // Check tesseract
        if (`which tesseract`) {
            $this->info('tesseract found!');
            $this->info(`tesseract -v`);
        }else{
            $this->error('tesseract NOT FOUND! Please Install tesseract>=4.0.0.');
        }
        // Check jbig2
        if (`which jbig2`) {
            $this->info('jbig2 found!');
        }else{
            $this->error('jbig2 NOT FOUND! Please Install jbig2.');
        }
        // Ping SOLR
        $adapter = new Curl();
        $dispatcher = new EventDispatcher();
        $client = new Client($adapter, $dispatcher, config('solarium'));
        
        $this->info('PING CHECKING SOLR CONNECTION...');
        try {
            $ping = $client->createPing();
            $client->ping($ping);
            $this->info('PING SOLR SUCCESS.');
        } catch (\Exception $e) {
            $this->error('PING SOLR FAILED, MAKE SURE THE SOLR IS CONNECTED TO DMS SYSTEM.');
            $this->error($e->getMessage());
        }
        // Test database connection
        try {
            $db = DB::connection()->getPdo();
            $this->info("Connected to the database by ".$db->getAttribute(\PDO::ATTR_DRIVER_NAME)." driver.");
        } catch (\Exception $e) {
            $this->error('Database connection failed. ');
            $this->error($e->getMessage());
        }
        return 0;
    }
}
