<?php

/*
 * This file is the main class of UltimateParticles.
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
namespace UltimateParticles;

use UltimateParticles\task\SpiralTask;
use UltimateParticles\commands\UltimateParticlesCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\item\ItemBlock;
use pocketmine\entity\Entity;

class UltimateParticles extends PluginBase
{

    /**
     *
     * @var static $this |null
     */
    private static $instance = null;

    /**
     *
     * @var string
     */
    private $provider = "YAML";

    /**
     *
     * @return UltimateParticles
     */
    public static function getInstance(): UltimateParticles
    {
        return self::$instance;
    }

    public function onEnable()
    {
        $this->getLogger()->info("Loading resources..");
        if (!is_dir($this->getDataFolder())) {
            mkdir($this->getDataFolder());
        }
        $this->data = new Config($this->getDataFolder() . "ejector.yml", Config::YAML, array());
        self::$instance = $this;
        $this->particles = new Particles ($this);
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new SpiralTask ($this), 3);
        $this->getCommand("ultimateparticles")->setExecutor(new UltimateParticlesCommand($this));
        $this->getLogger()->info($this->colorMessage("&aLoaded Successfully!"));
    }

    public function colorMessage(string $message): string
    {
        return str_replace("&", "§", $message);
    }

    // For external use
    /*
     * @deprecated
     */
    public function getData(string $file = "data")
    {
    return $this->data;
        return false;
    }

    /**
     *
     * @return Particles
     */
    public function getParticles(): Particles
    {
        $particles = new Particles ($this);
        return $particles;
    }

    /**
     * @api
     *
     * @param string $name Ejector's name to be an identification of it
     * @param Position $pos Ejector position
     * @param array $particles All kinds of particles to be shown (in array form)
     * @param float $amplifier Frequency of particles' appearance
     * @param string $type "normal" / "spiral"
     *
     * @return boolean
     */
    public function setEjector(string $name, Position $pos, array $particles, float $amplifier = 1, string $type = "normal"): bool
    {
        $ed = $this->data->getAll();
        if (array_key_exists($name, $ed) !== false) {
            unset($ed[$name]);
        }
        $ed[$name]["pos"]["x"] = $pos->x;
        $ed[$name]["pos"]["y"] = $pos->y + 1;
        $ed[$name]["pos"]["z"] = $pos->z;
        $ed[$name]["pos"]["world"] = $pos->getLevel()->getName();
        foreach ($particles as $particle) {
            $ed[$name]["particle"][] = $particle;
        }
        $ed[$name]["amplifier"] = $amplifier;
        //TODO: $ed[$name]["type"] = $type;
        $this->data->setAll($ed);
        $this->data->save();
        return true;
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return boolean
     */
    public function isEjectorExists(string $name): bool
    {
        $ed = $this->data->getAll();
        return (bool)array_key_exists($name, $ed);
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return boolean
     */
    public function removeEjector(string $name): bool
    {
        $ed = $this->data->getAll();
        if (array_key_exists($name, $ed)) {
            unset($ed[$name]);
            $this->data->setAll($ed);
            $this->data->save();
            return true;
        }
        return false;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAllEjectors(): string
    {
        $ed = $this->data->getAll();
        return implode(", ", array_keys($ed));
    }
}

?>