<?php 
//require '../vendor/autoload.php';
require '/xampp/htdocs/Proyecto/vendor/autoload.php';

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
//connection

newcrawler();
function newcrawler(){
    try{
        $url = "http://169.254.65.123/";
        //get the page: 
        $client = new Client();
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 60,
        ));
        $client->setClient($guzzleClient);
        $crawler = $client->request('GET', $url);
        //-------------------------------------------------------------
        //login and sumbit:
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = 'abc';
        $form['pwd'] = '654321';
        $crawler = $client->submit($form);
        //-------------------------------------------------------------
        //get the swipe page:
        $form = $crawler->selectButton('Swipe')->form();
        $crawler = $client->submit($form);
        return $crawler;
    }catch(\GuzzleHttp\Exception\RequestException $E)
    {    
        echo"Desconectado\n";
        usleep(1000000);
        return newcrawler();
    }
}
 
