<?php

namespace WorldEditor;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
class Main extends PluginBase implements Listener
{
	public $WEmode="off";
	public $position1=array();
	public $position2=array();	
	public function onEnable()
	{
		$this->getLogger()->info(TextFormat::BLUE."plugin loading...");
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		$this->getLogger()->info(TextFormat::GREEN."plugin loaded successed!");
	}
	
	public function onCommand(CommandSender $sender,Command $cmd,$label,array $args)
	{
		global $WEmode;
		switch($cmd->getName())
		{
			case "wemode":
			if(isset($args[0]))
			{
				if($args[0]=="on")
				{
					$WEmode="on";
					$this->getServer()->broadcastMessage(TextFormat::GREEN."World Edit Mode has been on!");
				}
				elseif($args[0]=="off")
				{
					$WEmode="off";
					$this->getServer()->broadcastMessage(TextFormat::RED."World Edit Mode has been off!");
				}
			}
		}
	}
	public function playerBlockTouch(PlayerInteractEvent $event)
	{
		global $WEmode;
		if($WEmode=="on")
		{	
			$blockid=$event->getBlock()->getID();
			$block=$event->getBlock();
			$tmp="on";
			if($tmp=="on")
			{
				$this->position1=array( "x"=>$block->x,
										"y"=>$block->y,
										"z"=>$block->z,
										"id"=>$blockid);
				$tmp="off";
			}
			else
			{
				$this->position2=array( "x"=>$block->x,
										"y"=>$block->y,
										"z"=>$block->z,
										"id"=>$block->$blockid);
				$tmp="on";
				
			}
			$player=$event->getPlayer();
			$text="Block ID:".$blockid;
			$player->sendMessage(TextFormat::BLUE.$block->x.",".$block->y.",".$block->z.",".$blockid);
			$player->sendMessage(TextFormat::GREEN.$text);
		}
	}
}

?>
