#!/usr/bin/env bash
#
# Laravel proof — fresh Laravel 12 app + turso-client-http.
# Builds an image from this checkout via a path repository and asserts
# SP discovery, facade resolution, facade round trip + transactions.
#
# Usage:
#   docker/laravel-proof/proof.sh             # matrix: 8.3 8.4 8.5 (default)
#   docker/laravel-proof/proof.sh 8.3         # single version
#   docker/laravel-proof/proof.sh 8.3 8.5     # subset
#
# Point TURSO_DB_URL at a local sqld (default host.docker.internal:8081 —
# your host sqld on port 8081 reaches the proof container).
set -euo pipefail
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$DIR/../.."

DB_URL="${TURSO_DB_URL:-http://host.docker.internal:8081}"
DB_TOKEN="${TURSO_DB_TOKEN:-eyJ0eXAiOiJKV1QiLCJhbGciOiJFZERTQSJ9.eyJpYXQiOjE3MzY2MzU1MTUsIm5iZiI6MTczNjYzNTUxNSwiZXhwIjoxNzM3MjQwMzE1LCJqdGkiOiJkYjEifQ.5sm4FN4PosAJ5h9wLay6q3ryAxbGRGuETU1A3F_Tr3WXpAEnr98tmAa92qcpZz_YZN0T_h4RqjGlEMgrSwIJAQ}"

if [ "$#" -gt 0 ]; then
    PHP_VERSIONS=("$@")
else
    PHP_VERSIONS=(8.3 8.4 8.5)
fi

FAILED=()

for V in "${PHP_VERSIONS[@]}"; do
    TAG="turso-laravel-proof:${V}"

    echo "==> Building proof image (PHP ${V})"
    docker build --build-arg PHP_VERSION="$V" -f docker/laravel-proof/Dockerfile -t "$TAG" .

    echo "==> Running proof (PHP ${V})"
    if docker run --rm \
        --add-host=host.docker.internal:host-gateway \
        -e DB_URL="$DB_URL" \
        -e DB_TOKEN="$DB_TOKEN" \
        "$TAG"; then
        echo "==> PHP ${V}: PASS"
    else
        echo "==> PHP ${V}: FAIL"
        FAILED+=("$V")
    fi
done

if [ "${#FAILED[@]}" -gt 0 ]; then
    echo "PROOF-MATRIX: FAILED on PHP ${FAILED[*]}" >&2
    exit 1
fi

echo "PROOF-MATRIX: all green (PHP ${PHP_VERSIONS[*]})"
