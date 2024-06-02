<?php

declare(strict_types=1);

namespace Farmero\banknotes\utils;

use pocketmine\player\Player;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;

class NoteManager {

    public function createNoteForPlayer(Player $player, int $amount): void {
        $item = VanillaItems::PAPER();
        $item->setCustomName("Bank Note");
        $item->setLore(["Amount: $amount"]);
        $nbt = $item->getNamedTag();
        $nbt->setInt("BankNoteAmount", $amount);
        $item->setNamedTag($nbt);

        $player->getInventory()->addItem($item);
    }
}