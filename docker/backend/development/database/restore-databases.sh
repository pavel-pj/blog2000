#!/bin/bash
set -e

echo "Restoring backup to multiple databases..."

# Восстанавливаем основную базу
echo "Restoring to main database: $POSTGRES_DB"
psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" -f /tmp/db1/db_backup.sql

# Восстанавливаем тестовую базу
if [ -n "$POSTGRES_MULTIPLE_DATABASES" ]; then
    for db in $(echo $POSTGRES_MULTIPLE_DATABASES | tr ',' ' '); do
        if [ "$db" != "$POSTGRES_DB" ]; then
            echo "Restoring to test database: $db"
            psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$db" -f /tmp/db1/db_backup.sql
        fi
    done
fi

echo "Backup restored to all databases successfully!"