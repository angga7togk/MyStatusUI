<?php
declare(strict_types=1);

namespace Angga7Togk\MyStatusUI;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;

use onebone\economyapi\EconomyAPI;

use Angga7Togk\MyStatusUI\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener{
    
    public function onEnable() : void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        
        @mkdir($this->getDataFolder());
       $this->saveDefaultConfig();
       $this->getResource("config.yml");
       
    }

    public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) : bool {
        
        if($cmd->getName() == "mystatus"){
            $this->MyStatusUI($sender);
        }
        
        return true;
    }
    
    public function MyStatusUI($player){
        $form = new SimpleForm(function(Player $player, int $data = null){
                if($data === null){
	                $this->getConfig()->get("Status")["Button"]["Message"];
                        return true;
		}
	        if($data === 0){
		        $this->getConfig()->get("Status")["Button"]["Message"];
                        return true;
		}
	});
            $content = str_replace (["{money}", "{player}", "{rank}", "{online}", "{max_online}", "{ping}", "{tps}", "{x}", "{y}", "{z}", "{item_name}", "{item_meta}", "{item_id}"], [$this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($player), $player->getName(), $this->getServer()->getPluginManager()->getPlugin("PurePerms")->getUserDataMgr()->getGroup($player)->getName(), count($this->getServer()->getOnlinePlayers()), $this->getServer()->getMaxPlayers(), $player->getNetworkSession()->getPing(), $this->getServer()->getTicksPerSecond(), intval($player->getPosition()->getX()), intval($player->getPosition()->getY()), intval($player->getPosition()->getZ()), $player->getInventory()->getItemInHand()->getName(), $player->getInventory()->getItemInHand()->getMeta(), $player->getInventory()->getItemInHand()->getId()], $this->getConfig()->get("Status")["SimpleForm"]["Content"]);
       	    $form->setTitle($this->getConfig()->get("Status")["SimpleForm"]["Title"]);
            $form->setContent($content);
            $form->addButton($this->getConfig()->get("Status")["Button"]["Name"], 0, $this->getConfig()->get("Status")["Button"]["Image"]);
            $form->sendToPlayer($player);
            return $form;
    }
}
