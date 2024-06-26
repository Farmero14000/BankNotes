<?php

declare(strict_types=1);

namespace Farmero\banknotes\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use Farmero\banknotes\BankNotes;

use Farmero\moneysystem\MoneySystem;

class CreateNoteCommand extends Command {

    public function __construct() {
        parent::__construct("banknote", "Create a bank note");
        $this->setPermission("banknotes.cmd");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game");
            return false;
        }

        if (count($args) !== 1 || !is_numeric($args[0])) {
            $sender->sendMessage("Usage: /banknote <amount>");
            return false;
        }

        $amount = intval($args[0]);

        if (MoneySystem::getInstance()->getMoneyManager()->getMoney($sender) < $amount) {
            $sender->sendMessage("You do not have enough money");
            return false;
        }

        MoneySystem::getInstance()->getMoneyManager()->removeMoney($sender, $amount);
        BankNotes::getInstance()->getNoteManager()->createNoteForPlayer($sender, $amount);

        $sender->sendMessage("Bank note created for $" . $amount);
        return true;
    }
}
