#!/bin/bash

baseDir=$1/../;
config=$baseDir.env;
index=$2;
echo "start" >> $log;
if [ -z "$index" ]; then
    index='--all';
fi

if [ ! -f "$config" ]; then
    echo "File config $config not found!";
    exit;
fi

. "$config";

if [ ! -f "$SPHINX_PATH" ]; then
    echo "File sphinx config $SPHINX_PATH not found!";
    exit;
fi

exec sudo -u sphinxsearch /usr/bin/indexer --config "$SPHINX_PATH" --rotate "$index";
exit;