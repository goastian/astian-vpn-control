docker pull elyerr/vpn-control:latest && \
docker compose down && \
docker compose up -d --build && \
docker image prune -f
