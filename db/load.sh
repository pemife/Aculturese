#!/bin/sh

BASE_DIR=$(dirname "$(readlink -f "$0")")
if [ "$1" != "test" ]; then
    psql -h localhost -U aculturese -d aculturese < $BASE_DIR/aculturese.sql
fi
psql -h localhost -U aculturese -d aculturese_test < $BASE_DIR/aculturese.sql
