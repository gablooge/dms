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

class PdfToTextJob implements ShouldQueue
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
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        try{
            $dokumen = Dokumen::find($this->params['id']);
            $process = new Process([env('BIN_OCRMYPDF', '/usr/bin/ocrmypdf'), public_path('medias/'.$dokumen->file), public_path('medias/'.$dokumen->file)]);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new Exception('Failed convert pdf tobe searchable text.');
            }
            $dokumen->solr = false;
            $dokumen->isi = Pdf::getText(public_path('medias/'.$dokumen->file));
            $dokumen->save();
            // other information
            if(app('App\Http\Controllers\SolariumController')->update($dokumen) == 0){
                $dokumen->solr = true;
                $dokumen->save();
            }
        }catch(\Exception $e){
            // ngapain nih kalau gagal di proses queue?
        }
    }
}
