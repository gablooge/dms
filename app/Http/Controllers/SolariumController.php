<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Solarium\Client;
use Solarium\Core\Client\Adapter\Curl;
use Symfony\Component\EventDispatcher\EventDispatcher; 
use Symfony\Component\Process\Process;
use Spatie\PdfToText\Pdf;
use App\Models\Dokumen;

class SolariumController extends Controller
{
    //
    protected $client;
    public function __construct(\Solarium\Client $client)
    {
        $this->client = $client;
    }

    public function ping()
    {
        // create a ping query
        $ping = $this->client->createPing();
        // execute the ping query
        try {
            $this->client->ping($ping);
            return response()->json('OK');
        } catch (\Solarium\Exception $e) {
            return response()->json('ERROR', 500);
        }
    }
    // $controller = app()->make('App\Http\Controllers\SolariumController');
    // app()->call([$controller, 'total']);
    public function total()
    {
        $select = array(
            'query'         => '*:*',
            'start'         => "0",
            'rows'          => "0",
            'sort'          => array('tahun' => 'desc')
        );
        $adapter = new Curl();
        $dispatcher = new EventDispatcher();
        $client = new Client($adapter, $dispatcher, config('solarium'));

        $query = $client->createSelect($select);
        $resultset = $client->select($query);
        // echo 'NumFound: '.$resultset->getNumFound();
        return $resultset->getNumFound();
    } 

    // $params = ['start' => '0', 'length' => '20'];
    // $request = Illuminate\Http\Request::create('/select', 'get', $params);
    // $controller = app()->make('App\Http\Controllers\SolariumController');
    // app()->call([$controller, 'select'], ['request' => $request]);
    public function select(Request $request)
    {
        $start = $request->query('start', "0");
        $length = $request->query('length', "10");
        $q = array();
        if($request->has('search') && !empty($request->search['value'])){
            array_push($q, "(nomor:{$request->search['value']} OR isi:{$request->search['value']} OR tags:{$request->search['value']} OR perihal:{$request->search['value']})");
        }
        if ($request->has('tags') && !empty($request->tags)) {
            $tags = array_map(function ($tag) {
                return "tags:".$tag;
            }, $request->tags);
            array_push($q, "(".join(" ".$request->match_tag_list." ", $tags).")");
        }
        $query = '*:*';
        if (!empty($q)) {
            $query = join(" AND ", $q);
        }
        $select = array(
            'query'         => $query,
            'start'         => $start,
            'rows'          => $length,
            'sort'          => array('tahun' => 'desc')
        );
        $adapter = new Curl();
        $dispatcher = new EventDispatcher();
        $client = new Client($adapter, $dispatcher, config('solarium'));

        $query = $client->createSelect($select);
        $resultset = $client->select($query);
        // echo 'NumFound: '.$resultset->getNumFound();
        return $resultset;
    }

    // for tinker 
    // $dokumen = App\Models\Dokumen::find(1);
    // $controller = app()->make('App\Http\Controllers\SolariumController');
    // app()->call([$controller, 'add'], ['dokumen' => $dokumen]);
    public function add($dokumen)
    {
        $adapter = new Curl();
        $dispatcher = new EventDispatcher();
        $client = new Client($adapter, $dispatcher, config('solarium'));

        $update = $client->createUpdate();
        $doc = $update->createDocument();
        foreach( $dokumen->toArray() as $key => $value )
        {
            if($key == "tahun"){
                $doc->$key = strval($value);
            }else if($key != "solr"){
                $doc->$key = $value;
            }
        }
        $update->addDocuments(array($doc));
        $update->addCommit();
        $result = $client->update($update);
        return $result->getStatus();
    }
    // for tinker 
    // $dokumen = App\Models\Dokumen::find(3);
    // $controller = app()->make('App\Http\Controllers\SolariumController');
    // app()->call([$controller, 'update'], ['dokumen' => $dokumen]);
    public function update($dokumen)
    {
        $this->delete($dokumen->id);
        return $this->add($dokumen);
        // $adapter = new Curl();
        // $dispatcher = new EventDispatcher();
        // $client = new Client($adapter, $dispatcher, config('solarium'));

        // $update = $client->createUpdate();
        // $doc = $update->createDocument();
        // foreach( $dokumen->toArray() as $key => $value )
        // {
        //     if($key == 'id')
        //     {
        //         $doc->setKey('id', $value);
        //     }
        //     elseif ($value == null or empty($value))
        //     {
                
        //         $doc->setField($key, '');
        //         $doc->removeField($key);
        //         $doc->setFieldModifier($key, $doc::MODIFIER_REMOVE);
        //     }else{
        //         $doc->setField($key, $value);
        //         $doc->setFieldModifier($key, $doc::MODIFIER_SET);
        //     }
        // }
        // $update->addDocuments(array($doc));
        // $update->addCommit();
        // $result = $client->update($update);
        // return $result->getStatus();
    }
    // $dokumen = App\Models\Dokumen::find(1);
    // $controller = app()->make('App\Http\Controllers\SolariumController');
    // app()->call([$controller, 'delete'], ['id' => $dokumen->id]);
    public function delete($id)
    {
        $adapter = new Curl();
        $dispatcher = new EventDispatcher();
        $client = new Client($adapter, $dispatcher, config('solarium'));

        $update = $client->createUpdate();
        $update->addDeleteById($id);
        $update->addCommit();
        $result = $client->update($update);
        return $result->getStatus();
    }
    // app()->call([$controller, 'sync'], ['all' => true]);
    public function sync($all = false)
    {
        // all is true
        $dokumen_list = [];
        
        if($all){
            $dokumen_list = Dokumen::all();
        }else{
            $dokumen_list = Dokumen::where('solr', false)->get();
        }
        foreach ($dokumen_list as $dokumen) {
            $dokumen->solr = false;
            try{
                $process = new Process([env('BIN_OCRMYPDF', '/usr/bin/ocrmypdf'), public_path('medias/'.$dokumen->file), public_path('medias/'.$dokumen->file)]);
                $process->run();
                // echo $dokumen->id." : ";
                if (!$process->isSuccessful()) {
                    // throw new \Exception('Failed convert pdf to be searchable text.');
                    // echo "gagal\n";
                }else{
                    // echo "sukses\n";
                }
                $dokumen->isi = Pdf::getText(public_path('medias/'.$dokumen->file));
                $dokumen->save();
                if(app('App\Http\Controllers\SolariumController')->update($dokumen) == 0){
                    $dokumen->solr = true;
                }
                $dokumen->save();
            }catch(\Exception $e){
                // ngapain nih kalau gagal di proses queue?
                // echo $e->getMessage();
            }
        }
        
    }
}