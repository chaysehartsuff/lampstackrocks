#!/bin/bash

# Check if docker-compose command is available
if command -v docker-compose &> /dev/null; then
    DOCKER_CMD="docker-compose"
# Check if docker compose command is available
elif command -v docker-compose &> /dev/null; then
    DOCKER_CMD="docker compose"
else
    echo "Neither docker-compose nor docker compose command found. Please install Docker Compose and try again."
    exit 1
fi

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
$DOCKER_CMD up -d --build

echo "Waiting for MySQL to become available..."
for ((i=1;i<=MAX_ATTEMPTS;i++)); do
    if $DOCKER_CMD exec -T db mysqladmin ping -h db -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --silent &> /dev/null; then
        echo "MySQL is up and running!"
        break
    else
        echo "Still waiting...Attempt $i/$MAX_ATTEMPTS..."
        sleep $SLEEP_DURATION
    fi

    if [ $i -eq $MAX_ATTEMPTS ]; then
        echo "MySQL failed to become available after $MAX_ATTEMPTS attempts. Exiting."
        exit 1
    fi
done

echo "Inserting schema..."
$DOCKER_CMD exec -T db mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" < "$SCRIPT_DIR/../src/sql/init.sql" 2> /dev/null

echo "Seeding database..."
$DOCKER_CMD exec -T db mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" < "$SCRIPT_DIR/../src/sql/seed.sql" 2> /dev/null

echo "Live at http://localhost:8080"
