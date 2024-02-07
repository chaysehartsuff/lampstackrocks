#!/bin/bash

SCRIPT_DIR=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" &> /dev/null && pwd)
ENV_FILE="$SCRIPT_DIR/../.env"

MAX_ATTEMPTS=30
SLEEP_DURATION=5

if [ -f "$ENV_FILE" ]; then
  echo ".env file found at $ENV_FILE"
  export $(grep -v '^#' "$ENV_FILE" | xargs)
else
  echo ".env file not found at $ENV_FILE."
  exit 1
fi

echo "Building containers..."
docker compose up -d --build

HOST='db'
PORT='3306'

echo "Waiting for MySQL to become available..."
for ((i=1;i<=MAX_ATTEMPTS;i++)); do
    if mysqladmin ping -h 127.0.0.1 -P $PORT --protocol=TCP -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --silent 2>/dev/null; then
        echo "It's alive!"
        break
    else
        echo "still waiting...Attempt $i/$MAX_ATTEMPTS..."
        sleep $SLEEP_DURATION
    fi

    if [ $i -eq $MAX_ATTEMPTS ]; then
        echo "MySQL failed to become available after $MAX_ATTEMPTS attempts. Exiting."
        exit 1
    fi
done

echo "Inserting schema..."
docker-compose exec -T db mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" < "$SCRIPT_DIR/../src/sql/init.sql" 2> /dev/null

echo "Seeding database..."
docker-compose exec -T db mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" < "$SCRIPT_DIR/../src/sql/seed.sql" 2> /dev/null

echo "Live at http://localhost:8080"

