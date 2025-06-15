<?php

/*
 * Copyright (c) 2024 - present nicholass003
 *   _______        _   _  __
 *  |__   __|      | | (_)/ _|
 *     | | _____  _| |_ _| |_ _   _
 *     | |/ _ \ \/ / __| |  _| | | |
 *     | |  __/>  <| |_| | | | |_| |
 *     |_|\___/_/\_\ __|_|_|  \__, |
 *                             __/ |
 *                            |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  nicholass003
 * @link    https://github.com/nicholass003/
 *
 *
 */

declare(strict_types=1);

namespace nicholass003\Textify\Lib;

use nicholass003\Textify\Lib\Exception\TextifyException;
use nicholass003\Textify\Lib\Model\Model;
use pocketmine\plugin\Plugin;

final class TextifyFactory{

	private static ?TextifyFactory $instance = null;
	private static ?Plugin $registrant = null;

	public static function getInstance() : TextifyFactory{
		return self::$instance ?? throw new TextifyException('');
	}

	public static function isRegistered() : bool{
		return self::$instance !== null;
	}

	public static function register(Plugin $plugin) : void{
		if(self::$instance === null){
			self::$instance = new self();
		}
		self::$registrant = $plugin;
	}

	public static function getRegistrant() : Plugin{
		return self::$registrant;
	}

	/** @var Model[] */
	private array $models = [];

	public function add(Model $model) : void{
		$actorId = $model->getActorId();
		if(!isset($this->models[$actorId])){
			$this->models[$actorId] = $model;
		}
	}

	public function remove(string $actorId) : void{
		unset($this->models[$actorId]);
	}

	public function get(string $actorId) : ?Model{
		return $this->models[$actorId] ?? null;
	}
}
