<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Solarium\Client;
use Solarium\Core\Client\Adapter\Curl;
use Symfony\Component\EventDispatcher\EventDispatcher; 

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
    // $params = ['start' => '0', 'length' => '20'];
    // $request = Illuminate\Http\Request::create('/select', 'get', $params);
    // $controller = app()->make('App\Http\Controllers\SolariumController');
    // app()->call([$controller, 'select'], ['request' => $request]);
    public function select(Request $request)
    {
        $start = $request->query('start', "0");
        $length = $request->query('length', "10");
        $q = '*:*';
        if($request->has('search') && !empty($request->search['value'])){
            $q = "isi:{$request->search['value']} OR tags:{$request->search['value']} OR perihal:{$request->search['value']}";
        }
        $select = array(
            'query'         => $q,
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
            $doc->$key = $value;
        }
        $update->addDocuments(array($doc));
        $update->addCommit();
        $result = $client->update($update);
        return $result->getStatus();
    }
    // for tinker 
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
}