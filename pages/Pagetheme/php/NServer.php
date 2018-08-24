<?php
  //require __DIR__ . '/vendor/autoload.php';
  require '/xampp/htdocs/Proyecto/vendor/autoload.php';
  $options = array(
    'cluster' => 'mt1',
    'encrypted' => true
  );
  $pusher = new Pusher\Pusher(
    '8b7b30cb5814aead90c6',
    '487f91e47b4bbf226e84',
    '583885',
    $options
  );

  $data['message'] = 'hello world';
  $pusher->trigger('my-channel', 'my-event', $data);
?>