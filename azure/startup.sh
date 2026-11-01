#!/usr/bin/env bash
set -euo pipefail

# App Service PHP Linux memakai Nginx, sehingga aturan .htaccess tidak dibaca.
cp /home/site/wwwroot/azure/default.conf /etc/nginx/sites-available/default
nginx -t
service nginx reload
