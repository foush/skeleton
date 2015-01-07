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
read -n 1 -p "Set up gulp [y/n]? " doGulp
if [[ "$doGulp" == "y" || "$doGulp" == "Y" ]]; then
    ./gulp.sh
fi

# delete original git history
rm -rf .git/
rm ./specify.sh
rm ./startme.sh
rm ./gulp.sh
# start new git repository
git init
# add pre-commit hook
mv pre-commit-template.sh .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit
git add --all
git commit --no-verify -m"Initial commit"
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
