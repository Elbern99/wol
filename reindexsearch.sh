#!/bin/bash

baseDir=$1/../;
config=$baseDir.env;
index=$2;

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

if [ -z "$SPHINX_PASSWORD" ]
then
    echo sudo -u sphinxsearch /usr/bin/indexer --config $SPHINX_PATH --rotate $index
else
    echo $SPHINX_PASSWORD | sudo -S -u sphinxsearch /usr/bin/indexer --config $SPHINX_PATH --rotate $index
fi
exit;