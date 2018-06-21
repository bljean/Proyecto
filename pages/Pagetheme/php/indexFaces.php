<!doctype html>
<html >

<head>
 


  <title>rekognition</title>

 

</head>

<body>
  
  <?php
    require '/xampp/htdocs/Proyecto/vendor/autoload.php';
    
    $args = [
          'credentials' => [
           'key'     => 'AKIAIT4LOATLPNNNRGUQ	',
           'secret'  => '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',
          ],
          'region' => 'us-east-1',
          'version' => 'latest'
    
    ] ;

    $client = new Aws\rekognition\rekognitionclient($args);

    $result = $client->indexFaces([
      'CollectionId' => '20131036', // REQUIRED
      'DetectionAttributes' => ['DEFAULT'],
      'Image' => [ // REQUIRED
          'Bytes' => file_get_contents("/xampp/htdocs/Proyecto/pages/Pagetheme/Img/Jean_Luis/Jean_Luis_0005.png"),
      ],
  ]);


    print_r($result)




  
  ?>


</body>

</html>