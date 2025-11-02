#!/bin/bash
set -e

cd "$(dirname "$0")"

echo
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

if ! $TOOL compose down -v || ! $TOOL compose up -d; then
    echo
    echo " ✘ ERROR: Service restart failed."
    echo

    echo "Running containers:"
    $TOOL ps -a | head -n1; $TOOL ps | grep ip_cases || true
    echo

    echo "Failed containers:"
    $TOOL ps -a | head -n1; $TOOL ps -a | grep -iE "exited|dead|unhealthy" | grep ip_cases
    exit 1
fi

sleep 0.25

echo
echo " ✔ RESTART COMPLETE"
echo
echo "---"

sleep 0.25

$TOOL ps | head -n1; $TOOL ps | grep ip_cases
