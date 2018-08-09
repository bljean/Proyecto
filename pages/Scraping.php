<?php 
require '/xampp/htdocs/Proyecto/vendor/autoload.php';
use Goutte\Client;
//connection
$url = "http://169.254.65.123/";

//-------------------------------------------------------------
//get the page: 
$client = new Client();
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

//-------------------------------------------------------------
//get the swipe info:
$compare = $crawler->filter('tr.N')->first()->html();

while(1){
    
    $message = $crawler->filter('tr.N')->first()->html();
    if($compare != $message){

    }
    refresh();
}

function refresh(){
    $form = $crawler->selectButton('Refresh')->form();
    $crawler = $client->submit($form);
}

?>