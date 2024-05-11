<?php

namespace fynessed;

use fynessed\Commands\Report;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class ReportSystem extends PluginBase {

    use SingletonTrait;

    public function onLoad(): void {
        self::$instance = $this;
    }

    public function onEnable(): void {
        $this->saveDefaultConfig();

        self::registerCommands();
    }

    public function registerCommands(): void {
        $this->getServer()->getCommandMap()->register('reportSystem', new Report());
    }
}