<?php

declare(strict_types=1);

namespace Farmero\banknotes;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemTypeIds;

use Farmero\banknotes\command\CreateNoteCommand;

use Farmero\banknotes\utils\NoteManager;

use Farmero\moneysystem\MoneySystem;

class BankNotes extends PluginBase implements Listener {

    public static $instance;
    private $noteManager;

    public function onLoad(): void {
        self::$instance = $this;
    }

    public function onEnable(): void {
        $this->noteManager = new NoteManager();
        $this->registerCommands();
        $this->registerEvents();
    }

    private function registerCommands() {
        $this->getServer()->getCommandMap()->register("BankNotes", new CreateNoteCommand());
    }

    private function registerEvents() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public static function getInstance(): self {
        return self::$instance;
    }

    public function getNoteManager(): NoteManager {
        return $this->noteManager;
    }

    public function onPlayerInteract(PlayerInteractEvent $event): void {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if ($item->getTypeId() === ItemTypeIds::PAPER && $item->getNamedTag()->getTag("BankNoteAmount")) {
            $amount = $item->getNamedTag()->getInt("BankNoteAmount");
            MoneySystem::getInstance()->getMoneyManager()->addMoney($player, $amount);
            $item->setCount($item->getCount() - 1);
            $player->getInventory()->setItemInHand($item);
            $player->sendMessage("You have redeemed a bank note worth $amount");
            $event->cancel();
        }
    }
}
