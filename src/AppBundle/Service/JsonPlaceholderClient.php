<?php
namespace AppBundle\Service;
use GuzzleHttp\Client;
class JsonPlaceholderClient
{
    const BASE_URI = 'https://jsonplaceholder.typicode.com';
    
    private $guzzle;
    
    public function __construct()
    {
        $this->guzzle = new Client([
            'base_uri' => self::BASE_URI
        ]);
    }
    
    public function getPosts()
    {
        $response = $this->guzzle->request('GET', 'posts');
        
        // return $this->parseResponse($response);
        return json_decode($response->getBody()->getContents());
    }
    
    public function getPost($id)
    {
        $response = $this->guzzle->request('GET', 'posts/' . $id);
        
        return json_decode($response->getBody()->getContents());
    }
}