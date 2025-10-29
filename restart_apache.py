#!/usr/bin/env python3
import subprocess
import shutil
import sys

DOCKER_CMD = shutil.which("docker") or shutil.which("podman")
CONTAINERS = ["php"]

if not DOCKER_CMD:
    print("Docker (or Podman) not found in PATH.")
    sys.exit(1)

try:
    print("\nRestarting php service...\n")
    subprocess.run(
        [DOCKER_CMD, "compose", "up", "-d", "--build", "--force-recreate"] + CONTAINERS,
        check=True,
    )
    print("\n✓ php service restart complete!")
except subprocess.CalledProcessError as e:
    print("Failed to restart php service:", e)
    sys.exit(1)
