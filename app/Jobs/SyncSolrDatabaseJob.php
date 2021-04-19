<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Dokumen;

class SyncSolrDatabaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        //
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // all is true
        $dokumen_list = [];
        if($this->params['all']){
            $dokumen_list = Dokumen::all();
        }else{
            $dokumen_list = Dokumen::where('solr', false)->get();
        }
        foreach ($dokumen_list as $dokumen) {
            $dokumen->solr = false;
            if(app('App\Http\Controllers\SolariumController')->update($dokumen) == 0){
                $dokumen->solr = true;
            }
            $dokumen->save();
        }
    }
}
