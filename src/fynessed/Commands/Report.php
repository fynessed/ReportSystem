<?php

namespace fynessed\Commands;

use fynessed\Forms\ReportForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class Report extends Command {

    public function __construct() {
        parent::__construct('report', 'Report a player online.');
        $this->setPermission('pocketmine.group.user');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if ($sender instanceof Player) {
            $sender->sendForm(new ReportForm());
        }
    }
}