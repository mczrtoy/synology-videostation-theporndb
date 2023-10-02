#!/bin/bash

BASEDIR=$(dirname "$0")
echo php "$BASEDIR/loader.php" "$@" >/tmp/io.github.mczrtoy.ThePornDB.log
php "$BASEDIR/loader.php" "$@" 2>/tmp/io.github.mczrtoy.ThePornDB.err.log 1>/tmp/io.github.mczrtoy.ThePornDB.out.log
cat /tmp/io.github.mczrtoy.ThePornDB.out.log