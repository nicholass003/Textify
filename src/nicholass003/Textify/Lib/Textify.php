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

use nicholass003\Textify\Lib\Exception\TextifyInvalidDataException;
use nicholass003\Textify\Lib\Model\Model;
use nicholass003\Textify\Lib\Model\NonPlayerCharacter;
use nicholass003\Textify\Lib\Model\Text;
use nicholass003\Textify\Lib\Model\Variant;
use pocketmine\entity\Skin;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\world\Position;
use Ramsey\Uuid\Uuid;
use function count;
use function is_array;
use function json_decode;

final class Textify{

	public const TAG_SKIN = "skin";
	public const TAG_COMPOUND = "compound";

	/**
	 * @param Variant     $variant
	 * @param string      $text
	 * @param Position    $position
	 * @param string|null $actorId
	 * @param array       $extraData
	 *
	 * @return Model
	 */
	public static function create(Variant $variant, string $text, Position $position, ?string $actorId = null, array $extraData = []) : Model{
		$id = $actorId !== null ? $actorId : Uuid::uuid4()->toString();
		return match($variant){
			Variant::NPC => new NonPlayerCharacter($id, $text, $position, $extraData[self::TAG_SKIN], $extraData[self::TAG_COMPOUND] ?? null),
			Variant::TEXT => new Text($id, $text, $position)
		};
	}

	/**
	 * @param string $json
	 *
	 * @return Model
	 */
	public static function fromString(string $json) : Model{
		$data = json_decode($json, true);
		if(is_array($data) && isset($data[Model::ACTOR_ID]) && isset($data[Model::VARIANT]) && isset($data[Model::TEXT]) && isset($data[Model::SKIN]) && isset($data[Model::POSITION])){
			$skinData = $data[Model::SKIN];
			$pos = $data[Model::POSITION];
			return self::create(
				Variant::fromString($data[Model::VARIANT]),
				$data[Model::TEXT],
				Position::fromObject(new Vector3($pos[Model::POSITION_X], $pos[Model::POSITION_Y], $pos[Model::POSITION_Z]), Server::getInstance()->getWorldManager()->getWorldByName($pos[Model::POSITION_WORLD])),
				$data[Model::ACTOR_ID],
				count($skinData) > 0 ? [
					self::TAG_SKIN => new Skin(
						$skinData[Model::SKIN_ID],
						$skinData[Model::SKIN_DATA],
						$skinData[Model::CAPE_DATA],
						$skinData[Model::GEOMETRY_NAME],
						$skinData[Model::GEOMETRY_DATA]
					)
				] : []
			);
		}else{
			throw new TextifyInvalidDataException("Malformed JSON data: Unable to parse model information");
		}
	}
}
