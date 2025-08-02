docker pull astian/vpn-control:dev && \
docker compose -f docker-compose-dev.yml down && \
docker compose -f docker-compose-dev.yml up -d --build && \
docker image prune -f
