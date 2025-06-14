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

use nicholass003\Textify\Lib\Trait\ActorTrait;
use nicholass003\Textify\Lib\Trait\NameableTrait;
use nicholass003\Textify\Lib\Trait\PositionTrait;
use pocketmine\block\VanillaBlocks;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\network\mcpe\protocol\SetActorDataPacket;
use pocketmine\network\mcpe\protocol\types\entity\ByteMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\types\entity\FloatMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\IntMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\LongMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\PropertySyncData;
use pocketmine\network\mcpe\protocol\types\entity\StringMetadataProperty;
use pocketmine\player\Player;
use pocketmine\world\Position;

final class Text implements Model, \JsonSerializable{
	use ActorTrait;
	use NameableTrait;
	use PositionTrait;

	public function __construct(
		string $actorId,
		string $text,
		Position $position,
		int $actorRuntimeId = 0
	){
		$this->setText($text);
		$this->setPosition($position);
		$this->setActorRuntimeId($actorRuntimeId);
		$this->setVariant(Variant::TEXT);
		$this->setActorId($actorId);
	}

	public function send(Player $player, Action $action) : void{
		$session = $player->getNetworkSession();
		$packets = match($action){
			Action::ADD => [
				AddActorPacket::create(
					actorUniqueId: $this->actorRuntimeId,
					actorRuntimeId: $this->actorRuntimeId,
					type: EntityIds::FALLING_BLOCK,
					position: $this->position,
					motion: null,
					pitch: 0,
					yaw: 0,
					headYaw: 0,
					bodyYaw: 0,
					attributes: [],
					metadata: [
						EntityMetadataProperties::ALWAYS_SHOW_NAMETAG => new ByteMetadataProperty(1),
						EntityMetadataProperties::BOUNDING_BOX_HEIGHT => new FloatMetadataProperty(0.0),
						EntityMetadataProperties::BOUNDING_BOX_WIDTH => new FloatMetadataProperty(0.0),
						EntityMetadataProperties::FLAGS => LongMetadataProperty::buildFromFlags([
							EntityMetadataFlags::IMMOBILE => true,
							EntityMetadataFlags::FIRE_IMMUNE => true
						]),
						EntityMetadataProperties::NAMETAG => new StringMetadataProperty($this->getTitle() . "\n" . $this->getText()),
						EntityMetadataProperties::SCALE => new FloatMetadataProperty(0.0),
						EntityMetadataProperties::VARIANT => new IntMetadataProperty(TypeConverter::getInstance()->getBlockTranslator()->internalIdToNetworkId(VanillaBlocks::AIR()->getStateId()))
					],
					syncedProperties: new PropertySyncData([], []),
					links: []
				)
			],
			Action::EDIT => [
				SetActorDataPacket::create(
					actorRuntimeId: $this->actorRuntimeId,
					metadata: [
						EntityMetadataProperties::NAMETAG => new StringMetadataProperty($this->getTitle() . "\n" . $this->getText())
					],
					syncedProperties: new PropertySyncData([], []),
					tick: 0
				)
			],
			Action::MOVE => [
				RemoveActorPacket::create(
					actorUniqueId: $this->actorRuntimeId
				),
				AddActorPacket::create(
					actorUniqueId: $this->actorRuntimeId,
					actorRuntimeId: $this->actorRuntimeId,
					type: EntityIds::FALLING_BLOCK,
					position: $this->position,
					motion: null,
					pitch: 0,
					yaw: 0,
					headYaw: 0,
					bodyYaw: 0,
					attributes: [],
					metadata: [
						EntityMetadataProperties::ALWAYS_SHOW_NAMETAG => new ByteMetadataProperty(1),
						EntityMetadataProperties::BOUNDING_BOX_HEIGHT => new FloatMetadataProperty(0.0),
						EntityMetadataProperties::BOUNDING_BOX_WIDTH => new FloatMetadataProperty(0.0),
						EntityMetadataProperties::FLAGS => LongMetadataProperty::buildFromFlags([
							EntityMetadataFlags::IMMOBILE => true,
							EntityMetadataFlags::FIRE_IMMUNE => true
						]),
						EntityMetadataProperties::NAMETAG => new StringMetadataProperty($this->getTitle() . "\n" . $this->getText()),
						EntityMetadataProperties::SCALE => new FloatMetadataProperty(0.0),
						EntityMetadataProperties::VARIANT => new IntMetadataProperty(TypeConverter::getInstance()->getBlockTranslator()->internalIdToNetworkId(VanillaBlocks::AIR()->getStateId()))
					],
					syncedProperties: new PropertySyncData([], []),
					links: []
				)
			],
			Action::REMOVE => [
				RemoveActorPacket::create(
					actorUniqueId: $this->actorRuntimeId
				)
			],
		};

		foreach($packets as $packet){
			$session->sendDataPacket($packet);
		}
	}

	public function jsonSerialize() : mixed{
		return [
			Model::ACTOR_ID => $this->actorId,
			Model::VARIANT => $this->variant,
			Model::TITLE => $this->getTitle(),
			Model::TEXT => $this->getText(),
			Model::SKIN => [],
			Model::POSITION => [
				Model::POSITION_X => $this->position->x,
				Model::POSITION_Y => $this->position->y,
				Model::POSITION_Z => $this->position->z,
				Model::POSITION_WORLD => $this->position->getWorld()->getFolderName()
			]
		];
	}
}
