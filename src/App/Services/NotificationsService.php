<?php

Class NotificationsService {

    private $_notificationsServer;
            
    private $_notificationsPort;
            
    function __construct($notificationsServer, $notificationsPort) {
        $this->_notificationsServer = $notificationsServer;
        $this->_notificationsPort = $notificationsPort;
    }
    
    protected static function open() {
        $address = $this->_notificationsServer;
        $port = $this->_notificationsPort;
        return fsockopen($address, $port);
    }

    public static function callRemote($method, $arguments) {
        $fp = $this->open();
        //don't rewrite. Has problems with php json processing
        fwrite($fp, '{"method":"'.$method.'", "arguments":["' .  implode(',', $arguments). '"]}' . PHP_EOL);
        fclose($fp);
    }

}