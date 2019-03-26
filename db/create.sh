#!/bin/sh

if [ "$1" = "travis" ]; then
    psql -U postgres -c "CREATE DATABASE aculturese_test;"
    psql -U postgres -c "CREATE USER aculturese PASSWORD 'aculturese' SUPERUSER;"
else
    sudo -u postgres dropdb --if-exists aculturese
    sudo -u postgres dropdb --if-exists aculturese_test
    sudo -u postgres dropuser --if-exists aculturese
    sudo -u postgres psql -c "CREATE USER aculturese PASSWORD 'aculturese' SUPERUSER;"
    sudo -u postgres createdb -O aculturese aculturese
    sudo -u postgres psql -d aculturese -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    sudo -u postgres createdb -O aculturese aculturese_test
    sudo -u postgres psql -d aculturese_test -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    LINE="localhost:5432:*:aculturese:aculturese"
    FILE=~/.pgpass
    if [ ! -f $FILE ]; then
        touch $FILE
        chmod 600 $FILE
    fi
    if ! grep -qsF "$LINE" $FILE; then
        echo "$LINE" >> $FILE
    fi
fi
