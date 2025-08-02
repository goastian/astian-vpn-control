#!/bin/bash

IMAGE="astian/vpn-control:latest"
COMPOSE_FILE="docker-compose-prod.yml"
ENV_FILE=".env"

# Required environment variables
required_keys=(
    DB_CONNECTION
    DB_HOST
    DB_PORT
    DB_DATABASE
    DB_USERNAME
    DB_PASSWORD
)

# Step 1: Ensure docker-compose-prod.yml exists
if [ ! -f "$COMPOSE_FILE" ]; then
    if [ -f "docker-compose.yml" ]; then
        cp docker-compose.yml "$COMPOSE_FILE"
        echo "[INFO] Created $COMPOSE_FILE from docker-compose.yml"
    else
        echo "[ERROR] Neither $COMPOSE_FILE nor docker-compose.yml found. Aborting."
        exit 1
    fi
fi

# Step 2: Ensure .env file exists
if [ ! -f "$ENV_FILE" ]; then
    echo "[ERROR] .env file not found. Please create it first."
    exit 1
fi

# Step 3: Validate that required environment variables exist and have values
for key in "${required_keys[@]}"; do
    if ! grep -q "^$key=" "$ENV_FILE"; then
        echo "[ERROR] Missing required environment variable: $key"
        exit 1
    fi

    value=$(grep "^$key=" "$ENV_FILE" | cut -d '=' -f2-)
    if [[ -z "$value" ]]; then
        echo "[ERROR] Environment variable $key has no value. Please fill it in $ENV_FILE"
        exit 1
    fi
done

echo "[INFO] Environment variables validated successfully."

# Replace image line under "app:"
awk -v image="$IMAGE" '
/^[[:space:]]*app:/ {
    in_app = 1
    print
    next
}
in_app && /^[[:space:]]*image:/ {
    print "        image: " image
    next
}
/^[[:space:]]*[a-zA-Z0-9_-]+:/ && !/^[[:space:]]*app:/ {
    in_app = 0
}
{ print }
' "$COMPOSE_FILE" > "$COMPOSE_FILE.tmp" && mv "$COMPOSE_FILE.tmp" "$COMPOSE_FILE"

echo "[INFO] Updated app service image to: $IMAGE"

echo "[INFO] Stopping containers..."
docker compose -f "$COMPOSE_FILE" down

echo "[INFO] Starting containers..."
docker compose -f "$COMPOSE_FILE" up -d --build

echo "[INFO] Cleaning up unused images..."
docker image prune -f