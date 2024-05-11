<?php

namespace fynessed\Discord;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\thread\NonThreadSafeValue;

class WebhookSend extends AsyncTask {

    /** @var Webhook  */
    protected $webhook;
    /** @var Message */
    protected $message;

    public function __construct(Webhook $webhook, Message $message){
        $this->webhook = new NonThreadSafeValue($webhook);
        $this->message = new NonThreadSafeValue($message);
    }

    public function onRun() : void {
        $ch = curl_init($this->webhook->deserialize()->getURL());
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->message->deserialize()));
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        $this->setResult(curl_exec($ch));
        curl_close($ch);
    }

    public function onCompletion() : void {
        $response = $this->getResult();
        if($response !== ""){
            Server::getInstance()->getLogger()->error("[DiscordWebhookAPI] Got error: " . $response);
        }
    }
}