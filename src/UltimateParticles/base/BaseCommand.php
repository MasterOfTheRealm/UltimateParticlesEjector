<?php

/*
 * This file is a part of UltimateParticles.
 * Copyright (C) 2016 hoyinm14mc
 *
 * UltimateParticles is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * UltimateParticles is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with UltimateParticles. If not, see <http://www.gnu.org/licenses/>.
 */
namespace UltimateParticles\base;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandExecutor;
use UltimateParticles\UltimateParticles;

abstract class BaseCommand extends PluginBase implements CommandExecutor{

	/**
	 *
	 * @var $plugin
	 */
	protected $plugin;

	public function __construct(UltimateParticles $plugin){
		$this->plugin = $plugin;
	}

	public final function getPlugin(){
		return $this->plugin;
	}

	public final function getConfig(){
		return $this->plugin->getConfig();
	}

}
?>