<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class SimpleChat implements MessageComponentInterface {
    
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        echo "Classe instanciada";
    }

    // Adiciona o cliente quando ele se conectar
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "Cliente conectado ({$conn->resourceId})" . PHP_EOL;
    }

    // Remove o cliente quando ele se desconectar
    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Cliente {$conn->resourceId} desconectou" . PHP_EOL;
    }

    // Quando houver erro na conexão
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
        echo "Ocorreu um erro: {$e->getMessage()}" . PHP_EOL;
    }

    // Quando cliente enviar dados ao socket
    function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg);
        $data->date = date('d/m/Y H:i:s');

        foreach ($this->clients as $client) {
            $client->send(json_encode($data));
        }

        echo "Cliente {$from->resourceId} enviou uma mensagem" . PHP_EOL;
    }
}