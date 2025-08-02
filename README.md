# VPN Control (Administrator) 
VPN Manager is a managed VPN server that centralizes all VPN configurations, providing an efficient and scalable way to handle multiple servers and instances.
Features 🚀

- Centralized management: Control all VPN configurations from a single interface.
- WireGuard support: Currently compatible with WireGuard for fast and secure connections.
- Multi-server: Manage multiple VPN servers simultaneously.
- Multi-instance: Run multiple VPN instances within the same system.

## Upcoming Improvements ✨

- Support for additional VPN protocols.
- Integration with admin panels.
- Advanced deployment automation.

## License

This project is licensed under the GNU Affero General Public License v3.0. See the [LICENSE](./LICENSE) file for details.


# 🚀 Deploy Setup

This project uses Docker and Laravel for OAuth2 authentication. Follow the steps below to deploy the production environment and create the first user.

## 🔑 Environment Configuration

Before deployment, make sure to copy the environment file and configure the necessary variables:

# 📄 Environment Configuration (`.env`)

This file contains environment-specific settings for your application. Below is a breakdown of the configuration variables:

---

## 🌐 Application Settings

- `APP_ENV=env`: The current environment the application is running in (e.g., `production`, `local`, `staging`).
- `APP_KEY=`: Application encryption key (must be set for security).
- `APP_DEBUG=true`: Enables debug mode (set to `false` in production).
- `APP_TIMEZONE=UTC`: Default timezone used by the application.
- `APP_URL="https://vpn.elyerr.xyz"`: The base URL of your application.

---

## 🔐 OAuth2 Passport Server Configuration

- `PASSPORT_SERVER=https://auth.elyerr.xyz`: Base URL of the OAuth2 authentication server.
- `PASSPORT_SERVER_ID="9e77717c-d78f-4fcc-853d-036135405471"`: OAuth2 client ID used to authenticate this application with the Passport server.
- `PASSPORT_PROMPT_MODE=none`: Defines the prompt behavior (`internal`,`none`, `consent`, or `login`).
- `PASSPORT_DOMAIN_SERVER=".elyerr.xyz"`: Cookie domain scope for passport authentication.
- `PASSPORT_TOKEN_NAME="passport_server"`: Name of the cookie used to store the access token.
- `PASSPORT_SECURE_COOKIE=true`: Ensures the cookie is only sent over HTTPS.
- `PASSPORT_HTTP_ONLY_COOKIE=true`: Restricts cookie access to HTTP(S) requests only (not available to JavaScript).
- `PASSPORT_PARTITIONED_COOKIE=true`: Enables support for partitioned cookies for cross-site scenarios.

---

## ⚙️ PHP Server Workers

- `PHP_CLI_SERVER_WORKERS=4`: Number of workers for the built-in PHP CLI server.

---

## 📋 Logging Configuration

- `LOG_CHANNEL=daily`: Logging channel (`daily`, `single`, etc.).
- `LOG_STACK=single`: Logging stack used for writing logs.
- `LOG_DEPRECATIONS_CHANNEL=null`: Channel used for logging deprecation notices.
- `LOG_LEVEL=debug`: Minimum log level (e.g., `debug`, `info`, `error`).

---

## 🗄️ Database Configuration

- `DB_CONNECTION=pgsql`: Database driver (`pgsql` for PostgreSQL).
- `DB_HOST=127.0.0.1`: Host address of the database server.
- `DB_PORT=5432`: Port on which the database listens.
- `DB_DATABASE=vpn`: Name of the database.
- `DB_USERNAME=admin`: Database username.
- `DB_PASSWORD=admin`: Database password.

```bash
cp .env.example .env
```

Then, edit the .env file with your specific settings.

## Deploy to Production

Run the following command to deploy the application in a production environment:

```bash
./deploy-prod.sh
```

## Proxy settings Nginx server

```bash
server {

    listen 80;
    server_name vpn.server.org;

    # Logging
    access_log /var/log/nginx/accounts_access.log main;
    error_log /var/log/nginx/accounts_error.log warn;

    location / {
        proxy_pass http://127.0.0.1:8002;
        proxy_http_version 1.1;

        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;

        proxy_set_header X-Forwarded-Host $http_x_forwarded_host;
        proxy_set_header X-Forwarded-Port $http_x_forwarded_port;
        proxy_set_header X-Forwarded-AWS-ELB $http_x_forwarded_aws_elb;

        proxy_read_timeout 720s;
        proxy_connect_timeout 720s;
        proxy_send_timeout 720s;

        proxy_buffering on;
        proxy_buffer_size 128k;
        proxy_buffers 4 256k;
        proxy_busy_buffers_size 256k;
        proxy_temp_file_write_size 256k;

        proxy_redirect off;
    }
}
```

## VPN Core (Core of system)

You can use the nex image [Docker Image](https://hub.docker.com/r/elyerr/vpn-core)

