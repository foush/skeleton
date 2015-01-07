#!/bin/sh
DIR=$(git rev-parse --show-toplevel)
cd "$DIR"
./pre-commit.sh