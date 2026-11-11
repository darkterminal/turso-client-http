#!/usr/bin/env bash
#
# Laravel proof — fresh Laravel 12 app + turso-client-http.
# Builds an image from this checkout via a path repository and asserts
# SP discovery, facade resolution, facade round trip + transactions.
#
# Point TURSO_DB_URL at a local sqld (default host.docker.internal:8081 —
# your host sqld on port 8081 reaches the proof container).
set -euo pipefail
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$DIR/../.."

DB_URL="${TURSO_DB_URL:-http://host.docker.internal:8081}"
DB_TOKEN="${TURSO_DB_TOKEN:-eyJ0eXAiOiJKV1QiLCJhbGciOiJFZERTQSJ9.eyJpYXQiOjE3MzY2MzU1MTUsIm5iZiI6MTczNjYzNTUxNSwiZXhwIjoxNzM3MjQwMzE1LCJqdGkiOiJkYjEifQ.5sm4FN4PosAJ5h9wLay6q3ryAxbGRGuETU1A3F_Tr3WXpAEnr98tmAa92qcpZz_YZN0T_h4RqjGlEMgrSwIJAQ}"

echo "==> Building proof image (Laravel 12 + turso-client-http)"
docker build -f docker/laravel-proof/Dockerfile -t turso-laravel-proof .

echo "==> Running proof (DB_URL=$DB_URL)"
docker run --rm \
    --add-host=host.docker.internal:host-gateway \
    -e DB_URL="$DB_URL" \
    -e DB_TOKEN="$DB_TOKEN" \
    turso-laravel-proof
