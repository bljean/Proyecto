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

    $result = $client->compareFaces ([
      'SimilarityThreshold' => 70,
      'SourceImage'=>[
        'Bytes' => file_get_contents("ang2.PNG"),
      ],
       'TargetImage' => [
         'Bytes' => file_get_contents("jea.PNG"),
       ],
    ]);


    print_r($result)




  
  ?>


</body>

</html>