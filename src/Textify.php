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

use nicholass003\Textify\Lib\Model\Model;
use nicholass003\Textify\Lib\Model\Text;
use nicholass003\Textify\Lib\Model\Variant;
use pocketmine\world\Position;
use Ramsey\Uuid\Uuid;

final class Textify{

	public static function create(Variant $variant, string $text, Position $position, ?string $actorId = null) : Model{
		return match($variant){
			Variant::TEXT => new Text($actorId !== null ? $actorId : Uuid::uuid4()->toString(), $text, $position)
		};
	}
}
