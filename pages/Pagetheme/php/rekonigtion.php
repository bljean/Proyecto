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

    $result = $client->searchFacesByImage([
      'CollectionId' => '20131036',
      'FaceMatchThreshold' => 85,
      'Image' => [
        'Bytes' => file_get_contents("/xampp/htdocs/Proyecto/pages/Pagetheme/Img/Angenis_Garcia/Angenis_Garcia_0001.jpg"),
      ],
      'MaxFaces' => 3,
  ]);


    print_r($result)




  
  ?>


</body>

</html>