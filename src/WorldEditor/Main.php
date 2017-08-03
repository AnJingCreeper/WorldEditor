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
		$player=$event->getPlayer();
		$itemId=$event->getItem()->getID();
		if($WEmode=="on"&&$player->isOp()&&$itemId==271)
		{	
			
			$blockid=$event->getBlock()->getID();
			$block=$event->getBlock();
			static $tmp="on";
			if($tmp=="on")
			{
				$this->position1=array( "x"=>$block->x,
										"y"=>$block->y,
										"z"=>$block->z,
										"id"=>$blockid,
										"player"=>$player);
				$tmp="off";
				$player->sendMessage(TextFormat::WHITE."Set FIRST point:".$this->position1['x'].",".$this->position1['y'].",".$this->position1['z']);

			}
			else
			{
				$this->position2=array( "x"=>$block->x,
										"y"=>$block->y,
										"z"=>$block->z,
										"id"=>$blockid,
										"player"=>$player);
				$tmp="on";
				$player->sendMessage(TextFormat::WHITE."Set SECOND point:".$this->position2['x'].",".$this->position2['y'].",".$this->position2['z']);
				
			}
			$player->sendMessage(TextFormat::BLUE.$block->x.",".$block->y.",".$block->z.",".$blockid);
		}
	}
	public function fillBlock(BlockPlaceEvent $event)
	{
		global $WEmode;
		$player=$event->getPlayer();
		$block=$event->getBlock();
		if(($this->position1['player']==$this->position2['player'])&&$WEmode=="on"&&($this->position1['player']==$player))
			{
				$event->setCancelled(true);
				$command="fill ".$this->position1['x']." ".$this->position1['y']." ".$this->position1['z']." ".$this->position2['x']." ".$this->position2['y']." ".$this->position2['z']." ".$block->getID();
				//$player->sendMessage(TextFormat::WHITE.$command);
				$this->getServer()->dispatchCommand($player,$command);
				
			}
	}
	
}

?>
