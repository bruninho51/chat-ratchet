<?php

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

require 'vendor/autoload.php';
require 'SimpleChat.php';

IoServer::factory(
  new HttpServer(
      new WsServer(
          new SimpleChat()
      )
  ), 8080
)->run();