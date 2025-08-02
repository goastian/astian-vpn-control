# Changelog

## 🚧 v2.0.0

-   🚀 Migrated Laravel framework from **v11.x** to **v12.x**
-   🔄 Upgraded Passport Connect from **v1.0.16** to **v2.0.1**
-   🛠️ Fixed configuration to support deployment in **HTTP environments**
-   🐳 Optimized the **Docker image** for better performance and reduced size
-   🐞 **Debugbar Integration**: Enabled Laravel Debugbar for improved debugging in development environments
-   🦮 **VPN Core Compatibility**:

    -   Verified compatibility with [vpn-core v1.0.1](https://hub.docker.com/r/elyerr/vpn-core)

-   🗂️ Added **prefix support for database tables**
-   ⚙️ Updated **Docker Compose** files for more robust and flexible deployment

---

## [v1.0.0]

### 👤 User Features

-   📊 **Dashboard**: Intuitive panel to view user activity and status.
-   🖥️ **Devices**: Complete list of connected browser extensions.
-   🔐 **WireGuard Generator**: Simple device management using the WireGuard protocol.

---

### 🛠️ Admin Panel

-   🗄️ **Server Management**: Easily add, view, and edit servers.
-   🌐 **VPN Proxy API Support**: Added compatibility for Firefox browsers to facilitate extension development.
-   🧩 **WireGuard Management**: Efficient organization of WireGuard interfaces.
-   ⚙️ **Settings**:

    -   🛠️ **General**: Main application configuration.
    -   🔑 **Session**: App session management.
    -   💳 **Plans**: Plan configuration options.
    -   🗃️ **Redis**: Redis connection settings _(inactive)_.
    -   📦 **Queue**: Queue service configuration _(inactive)_.

-   🧬 **VPN Core Compatibility**:
    -   Compatible with [vpn-core v1.0.1](https://hub.docker.com/r/elyerr/vpn-core)
