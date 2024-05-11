<?php

namespace fynessed\Discord;

use pocketmine\Server;

class Webhook {

    protected string $url;

    public function __construct(string $url){
        $this->url = $url;
    }

    public function getURL() : string {
        return $this->url;
    }

    public function isValid() : bool {
        return filter_var($this->url, FILTER_VALIDATE_URL) !== false;
    }

    public function send(Message $message) : void {
        Server::getInstance()->getAsyncPool()->submitTask(new WebhookSend($this, $message));
    }
}