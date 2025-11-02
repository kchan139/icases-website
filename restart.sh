#!/bin/bash
set -e

cd "$(dirname "$0")"

echo "--- RESTARTING SERVICES ---"
echo

# for podman & docker compatibility
if command -v podman &> /dev/null; then
    TOOL=podman
elif command -v docker &> /dev/null; then
    TOOL=docker
else
    echo "Error: podman or docker is not installed." >&2
    exit 1
fi

$TOOL compose down -v
$TOOL compose up -d

sleep 0.25

echo
echo " ✔ RESTART COMPLETE"
echo
echo "---"

sleep 0.25

# list running containers for this project
$TOOL ps | head -n 1; $TOOL ps | grep ip_cases;
