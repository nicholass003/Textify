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

namespace Nicholass003\Textify\Lib\Trait;

use pocketmine\player\Player;
use pocketmine\world\Position;

trait PositionTrait{

	private Position $modelPosition;

	public function getModelPosition() : Position{
		return $this->modelPosition;
	}

	public function setModelPosition(Position $modelPosition) : self{
		$this->modelPosition = $modelPosition;
		return $this;
	}

	/**
	 * Returns a list of players who are currently able to view or are near this position.
	 *
	 * This typically includes all players whose render distance covers the current position,
	 * as determined by the world’s visibility system.
	 *
	 * @return Player[]
	 */
	public function getViewers() : array{
		$this->tryLoadChunk();
		return $this->modelPosition->getWorld()->getViewersForPosition($this->modelPosition);
	}

	protected function tryLoadChunk() : void{
		$world = $this->modelPosition->getWorld();
		if($world === null){
			return;
		}
		$chunkX = $this->modelPosition->getFloorX() >> 4;
		$chunkZ = $this->modelPosition->getFloorZ() >> 4;
		if(!$world->isChunkLoaded($chunkX, $chunkZ)){
			$world->loadChunk($chunkX, $chunkZ);
		}
	}
}
