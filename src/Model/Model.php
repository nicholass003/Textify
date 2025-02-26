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

namespace nicholass003\Textify\Lib\Model;

use pocketmine\player\Player;
use pocketmine\world\Position;

interface Model{

	public const ACTOR_ID = "actor_id";
	public const TEXT = "text";
	public const VARIANT = "variant";

	public const POSITION = "position";
	public const POSITION_X = "x";
	public const POSITION_Y = "y";
	public const POSITION_Z = "z";
	public const POSITION_WORLD = "world";

	public const SKIN = "skin";
	public const SKIN_ID = "skin_id";
	public const SKIN_DATA = "skin_data";
	public const CAPE_DATA = "cape_data";
	public const GEOMETRY_NAME = "geometry_name";
	public const GEOMETRY_DATA = "geometry_data";

	public function getActorRuntimeId() : int;

	public function setActorRuntimeId(int $actorRuntimeId) : self;

	public function getVariant() : Variant;

	public function setVariant(Variant $variant) : self;

	public function getActorId() : string;

	public function setActorId(string $actorId) : self;

	public function getPosition() : Position;

	public function setPosition(Position $position) : self;

	public function getText() : string;

	public function setText(string $text) : self;

	public function send(Player $player, Action $action) : void;
}
