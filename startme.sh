#!/bin/sh

# first run specifier sed replacer on entire project
./specify.sh
# vagrant up
vagrant up
# run db sync script setup
read -n 1 -p "Set up database from remote configuration [y/n]? " doDb
if [[ "$doDb" == "y" || "$doDb" == "Y" ]]; then
    ./src/sync-qa-db.sh
fi

# delete original git history
rm -rf .git/
# start new git repository
git init
git add --all
git commit -m"Initial commit"
# prompt for a remote
while true; do
    read -n 1 -p "Set up remote [y/n]? " doRemote
    if [[ "$doRemote" == "y" || "$doRemote" == "Y" ]]; then
        read -p "Specify remote URL: " REMOTE
        read -n 1 -p "$REMOTE is this correct [y/n]? " confirmRemote
        if [[ "$doRemote" == "y" || "$doRemote" == "Y" ]]; then
            git remote add origin "$REMOTE"
            git push origin master
            git flow init
            break;
        fi
    else
       break
    fi
done
