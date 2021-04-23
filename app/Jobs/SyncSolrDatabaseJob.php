<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Spatie\PdfToText\Pdf;
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
            try{
                $process = new Process([env('BIN_OCRMYPDF', '/usr/bin/ocrmypdf'), public_path('medias/'.$dokumen->file), public_path('medias/'.$dokumen->file)]);
                $process->run();
                if (!$process->isSuccessful()) {
                    //throw new \Exception('Failed convert pdf to be searchable text.');
                }
                $dokumen->isi = Pdf::getText(public_path('medias/'.$dokumen->file));
                $dokumen->save();
                if(app('App\Http\Controllers\SolariumController')->update($dokumen) == 0){
                    $dokumen->solr = true;
                }
                $dokumen->save();
            }catch(\Exception $e){
                // ngapain nih kalau gagal di proses queue?
            }
        }
    }
}
