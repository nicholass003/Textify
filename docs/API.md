# Textify API Documentation

**Version:** 0.0.1
**Status:** Stable
**Target:** Plugin developers using PocketMine-MP

---

## 1. Overview

Textify is a **library-first virion** that provides a structured API for creating and managing **Floating Text** and **NPC (Non-Player Character)** models inside the world.

The API is intentionally **explicit and low-magic**: nothing spawns automatically unless you ask for it, and lifecycle control stays in the developer’s hands.

---

## 2. Bootstrapping Textify

Before using any Textify feature, **the factory must be registered**.

### 2.1 Registering the Factory

Call this **once**, typically in `onEnable()`:

```php
use Nicholass003\Textify\Lib\TextifyFactory;

if(!TextifyFactory::isRegistered()){
	TextifyFactory::register($this);
}
```

What this does:

* Registers the NPC entity to `EntityFactory`
* Registers internal event listeners
* Loads stored models from `textify_models.json` (if enabled)

> [!WARNING]
> **Factory Registration Is Mandatory**
> Calling any Textify API **before** `TextifyFactory::register()` is a **logic error** and may result in undefined behavior or crashes.

---

## 3. Core Entry Point: `Textify`

### 3.1 Creating a Model

```php
use Nicholass003\Textify\Lib\Textify;
use Nicholass003\Textify\Lib\Model\Variant;
use pocketmine\world\Position;

$model = Textify::create(
    Variant::TEXT,
    $position,
    title: "Welcome",
    text: "Hello World"
);
```

#### Parameters

| Parameter   | Type           | Description                  |
| ----------- | -------------- | ---------------------------- |
| `Variant`   | `Variant` enum | TEXT, NPC, or PLAYER         |
| `Position`  | `Position`     | World position of the model  |
| `title`     | `string`       | Displayed title (first line) |
| `text`      | `string`       | Main displayed text          |
| `actorId`   | `?string`      | Optional custom ID           |
| `extraData` | `array`        | Skin / NBT data (NPC only)   |

If a model with the same `actorId` already exists, the existing instance is returned.

---

### 3.2 Creating an NPC Model

NPCs require **Skin** and optional **NBT data**.

```php
use pocketmine\entity\Human;
use Nicholass003\Textify\Lib\Textify;
use Nicholass003\Textify\Lib\Model\Variant;

$model = Textify::create(
    Variant::NPC,
    $position,
    title: "Shop",
    text: "Right-click me",
    extraData: [
        Textify::TAG_SKIN => $skin
    ]
);
```

If `TAG_SKIN` is missing for NPC variants, behavior is undefined and may throw.

---

## 4. Factory: `TextifyFactory`

The factory acts as the **model registry and lifecycle manager**.

### 4.1 Retrieving Models

```php
$factory = TextifyFactory::getInstance();

$model = $factory->get($actorId);
$allModels = $factory->getAll();
```

### 4.2 Removing Models

```php
$factory->remove($actorId);
```

This only removes the model from memory. Use `destroy()` for full cleanup.

---

### 4.3 Saving Models to Storage

```php
TextifyFactory::getInstance()->save();
```

This writes to:

```
plugin_data/textify_models.json
```

The file is guarded with a marker to prevent accidental overwrite.

---

## 5. Model Types

All models implement the `Model` interface and share common behavior.

---

## 5.1 Text (FloatingText)

The `Text` model represents a **Floating Text** rendered using a packet-level entity (falling block with no scale).

It does **not** extend a PocketMine entity class and is fully controlled via packets.

> [!NOTE]
> **Packet-Level Entity**
> `Text` models do not exist server-side as real entities.
> They are created, updated, and removed **entirely via network packets**.

### 5.1.1 Characteristics

* No gravity
* Invisible hitbox
* Always-visible nametag
* Lightweight (packet-only)

This makes it suitable for holograms, labels, and static/dynamic information displays.

---

### 5.1.2 Creating a Floating Text

```php
use Nicholass003\Textify\Lib\Textify;
use Nicholass003\Textify\Lib\Model\Variant;

$text = Textify::create(
    Variant::TEXT,
    $position,
    title: "Server",
    text: "Welcome to the world"
);
```

---

### 5.1.3 Sending and Updating

```php
use Nicholass003\Textify\Lib\Model\Action;

$text->send($player, Action::ADD);
$text->setText("Updated text");
$text->update(Action::EDIT);
```

### Supported Actions

> [!IMPORTANT]
> **Action Semantics Matter**
> Not all actions are equal in cost. Misusing them may affect client performance.

* `ADD` – Spawn text entity
* `EDIT` – Update title/text
* `MOVE` – Recreate entity at new position (**remove + add**, heavier)
* `REMOVE` – Despawn entity

---

### 5.1.4 Destroying a Text Model

```php
$text->destroy();
```

This removes the model from the factory and despawns it from all viewers.

---

## 5.2 NonPlayerCharacter (NPC)

Extends `Human` and implements `Model`.

### 5.1.3 Destroying an NPC

```php
$npc->destroy();
```

This will:

* Remove the model from the factory
* Despawn it from all viewers

---

## 6. Serialization

Models are JSON-serializable and can be restored using:

```php
$model = Textify::fromString($json);
```

> [!WARNING]
> **Do Not Trust External Data**
> Invalid or malformed JSON will throw `TextifyInvalidDataException`.
> Only deserialize data produced by Textify itself.

---

## 7. Storage Format

Textify stores models in a guarded JSON file:

* Marker key: `storage_textify_marker`
* Marker value: `textify-model-storage`

Any file missing this marker is **rejected**.

---

## 8. Error Handling

Common exceptions:

* `TextifyException` – Internal or lifecycle errors
* `TextifyInvalidDataException` – Invalid JSON or missing required fields

These indicate **logic errors**, not runtime conditions to silently ignore.

---

## 9. Versioning Policy

* Patch releases: bug fixes
* Minor releases: additive API changes
* Breaking changes are avoided unless unavoidable

---

## 10. Intended Usage

Textify is meant to be:

* A **foundation**, not a monolithic system
* Used by plugins that need visual world entities
* Extended through composition, not inheritance

> [!TIP]
> **Think in Lifecycles, Not Objects**
> Textify models are not fire-and-forget objects.
> Always think in terms of **create → send → update → destroy**.

If you find yourself hacking internals, you are likely using the API incorrectly.
