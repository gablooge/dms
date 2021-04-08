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
    // for tinker 
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
}