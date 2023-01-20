#!/bin/sh
main_container=$(docker-compose ps -q redis)
seconds=5

until docker inspect --format "{{json .State.Health.Status }}" $main_container | grep '"healthy"' > /dev/null 2>&1 ; do
  >&2 echo "Docker [redis] is not ready - wait 💤 ($seconds)"
  sleep 5
  seconds=$(expr $seconds + 5)
done

echo "\n✅ Done! Docker [redis] is ready. 🏁"