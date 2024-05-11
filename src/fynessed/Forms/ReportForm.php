<?php

namespace fynessed\Forms;

use EasyUI\element\Dropdown;
use EasyUI\element\Option;
use EasyUI\element\Input;
use EasyUI\utils\FormResponse;
use EasyUI\variant\CustomForm;
use fynessed\Discord\Embed;
use fynessed\Discord\Message;
use fynessed\Discord\Webhook;
use fynessed\ReportSystem;
use pocketmine\player\Player;
use pocketmine\Server;

class ReportForm extends CustomForm {
    public function __construct() {
        parent::__construct('Report a player!');
    }

    public function onCreation(): void {
        $playerDropdown = new Dropdown("Report a player:");
        $playerInput = new Input('Reason for report:');
        foreach(Server::getInstance()->getOnlinePlayers() as $player) {
            $playerDropdown->addOption(new Option($username = $player->getName(), $username));
        }

        $this->addElement('playerTarget', $playerDropdown);
        $this->addElement('playerInput', $playerInput);
    }

    public function onSubmit(Player $player, FormResponse $response): void {
        $target = $response->getDropdownSubmittedOptionId('playerTarget');
        $input = $response->getInputSubmittedText('playerInput');
        $webhook = new Webhook(ReportSystem::getInstance()->getConfig()->getNested('webhook'));
        $msg = new Message();
        $embed = new Embed();
        $embed->setTitle(ReportSystem::getInstance()->getConfig()->getNested('discordTitle'));
        $embed->setDescription(str_replace(["{player}", "{sender}", "{reason}"], [$target, $player->getName(), $input], ReportSystem::getInstance()->getConfig()->getNested("reportMessage")));
        $embed->setColor(ReportSystem::getInstance()->getConfig()->getNested('embedColour'));
        $embed->setFooter(date('m/d/Y @ h:i:s a', time()));
        $msg->addEmbed($embed);
        $webhook->send($msg);
        $player->sendMessage('§7[§l§3ReportSystem§r§7] §bReport sent successfully!');
    }
}