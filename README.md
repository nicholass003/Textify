# Textify

🔤 **Textify** is a **PocketMine-MP library (virion)** that provides a stable, structured, and developer-friendly system for **Floating Texts** and **NPCs**.

It is built for developers who want clean abstractions, predictable behavior, and production-ready performance—without rewriting visual entity logic from scratch.

---

## ✨ Key Features

* 📌 Static and dynamic Floating Text
* 🧍 NPC management with full lifecycle control
* 🧩 Modular and extensible architecture
* ⚡ Performance-aware and server-friendly
* 📦 Designed to be used as a **virion**
* 🛠️ Ideal for leaderboards, holograms, area info, and visual UI elements

---

## 🎯 Project Goals

Textify exists to:

* Eliminate repetitive boilerplate when working with Floating Texts and NPCs
* Provide a **clear, consistent API** for visual entities
* Serve as a reusable foundation for PocketMine-MP plugins

If your plugin needs in-world visual representation, **Textify is the foundation—not a shortcut**.

---

## 📦 Project Status

✅ **Stable**

Textify has been tested in real server environments.
Breaking changes are avoided and handled through proper versioning.

---

## 🧠 Design Philosophy

* **Explicit over implicit** — no hidden behavior
* **Library-first mindset** — flexible, not opinionated
* **Performance-aware** — no unnecessary tick overhead
* **Developer-oriented API** — readable, predictable, maintainable

---

## 📥 Installation

Textify can be used **both locally and through Poggit**, following standard PocketMine-MP practices.

### Option 1: Using Poggit (Recommended)

Add Textify as a virion dependency in your `poggit.yml`:

```yaml
libs:
  - src: Nicholass003\Textify\Lib\Textify
    version: ^0.0.1
```

Build your plugin on Poggit. Poggit will automatically embed Textify into your plugin `.phar`.

This is the **recommended approach** for public plugins.

---

### Option 2: Using Pharynx (Local Build)

1. Add Textify to your project dependencies
2. Compile your plugin using [**Pharynx**](https://github.com/SOF3/pharynx)
3. Use Textify namespaces directly in your plugin

This approach is suitable for **local development** and advanced workflows.

---

## 🧩 Basic Usage

Examples and API references will be provided in separate documentation.

Textify allows you to:

* Create and manage Floating Texts
* Spawn and control NPCs
* Update visual content dynamically

> Textify provides the tools. **You define the behavior.**

---

## 🔗 Compatibility

* Latest **PocketMine-MP**
* Minecraft Bedrock Edition (as supported by PMMP)

---

## 📖 Inspiration

Some parts of this project were inspired by [**Texter**](https://github.com/fuyutsuki/Texter). Special thanks to its contributors for their work in the PocketMine-MP community.

---

## 🔌 Plugins Using Textify

The following plugins are known to use Textify in real-world production environments:

- **TopStats** – Statistics and leaderboard plugin that uses Textify for floating texts and NPC displays  
  https://github.com/nicholass003/TopStats

---

## 🤝 Contributing

Contributions are welcome, but:

* Code must be **clean, consistent, and intentional**
* PRs without a clear purpose may be rejected
* Architectural discussion is preferred over quick fixes

---
