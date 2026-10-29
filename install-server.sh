#!/bin/bash
# Usage on a fresh Ubuntu/Debian server:
#   ./install-server.sh <git repo url> [domain] [app dir]
# Installs Docker + host nginx; nginx proxies port 80 to the app container.
set -e

REPO_URL="${1:?Usage: ./install-server.sh <git repo url>}"
DOMAIN="${2:-_}"
APP_DIR="${3:-$HOME/silsilah}"

if ! command -v docker >/dev/null 2>&1; then
    curl -fsSL https://get.docker.com | sudo sh
    sudo usermod -aG docker "$USER"
fi

if ! command -v git >/dev/null 2>&1; then
    sudo apt-get update && sudo apt-get install -y git
fi

if [ -d "$APP_DIR/.git" ]; then
    git -C "$APP_DIR" pull
else
    git clone "$REPO_URL" "$APP_DIR"
fi

cd "$APP_DIR"
[ -f .env ] || cp .env.example .env

SERVER_IP=$(curl -fsS https://api.ipify.org || echo localhost)
HOST="$DOMAIN"; [ "$DOMAIN" = "_" ] && HOST="$SERVER_IP"
sed -i "s|^APP_URL=.*|APP_URL=http://$HOST|" .env

sudo docker compose up -d --build

# Host nginx -> docker app (127.0.0.1:8000)
sudo apt-get update && sudo apt-get install -y nginx
sudo tee /etc/nginx/sites-available/silsilah >/dev/null <<NGINX
server {
    listen 80;
    server_name $DOMAIN;
    client_max_body_size 64M;

    location / {
        proxy_pass http://127.0.0.1:8000;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
        proxy_read_timeout 120s;
    }
}
NGINX
sudo ln -sf /etc/nginx/sites-available/silsilah /etc/nginx/sites-enabled/silsilah
sudo rm -f /etc/nginx/sites-enabled/default
if command -v ufw >/dev/null 2>&1 && sudo ufw status | grep -q "Status: active"; then
    sudo ufw allow OpenSSH
    sudo ufw allow 'Nginx Full'
fi
sudo nginx -t && sudo systemctl enable --now nginx && sudo systemctl reload nginx

echo
echo "App:        http://$HOST"
echo "phpMyAdmin: ssh -L 8080:127.0.0.1:8080 user@$SERVER_IP, then http://localhost:8080"
echo "HTTPS:      sudo apt install -y certbot python3-certbot-nginx && sudo certbot --nginx -d $DOMAIN"
echo "First start takes a few minutes (composer + npm install). Watch: sudo docker compose logs -f app"
