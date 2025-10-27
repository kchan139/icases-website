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
    print("\nResetting php service...\n")
    subprocess.run(
        [DOCKER_CMD, "compose", "up", "-d", "--build", "--force-recreate"] + CONTAINERS,
        check=True,
    )
    print("\n✓ php service reset complete!")
except subprocess.CalledProcessError as e:
    print("Failed to reset php service:", e)
    sys.exit(1)
