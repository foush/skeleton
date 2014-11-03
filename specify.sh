#!/bin/sh
echo "Specifying project name"
read -p "What is the lower case namespace of your project (eg fzyskeleton): " LCVAL
find ./ -type f -exec sed -i -e "s/fzyskeleton/$LCVAL/g" {} \;
read -p "What is the title case namespace of your project (eg FzySkeleton) : " TCVAL
find ./ -type f -exec sed -i -e "s/fzyskeleton/$TCVAL/gi" {} \;

read -n 1 -p "Set remote database connection variables for development?" setDevDB
if [[ "$setDevDB" == "y" || "$setDevDB" == "Y" ]]; then
    while true; do
         read -p "Development DB name" DEVDB
         read -p "Development DB user" DEVUSER
         read -p "Development DB pass" DEVPASS
         read -p "Development DB host" DEVHOST
         read -n 1 -p "Confirm\nDB: $DEVDB\nUser: $DEVUSER\nPass: $DEVPASS\nHOST: $DEVHOST" isCorrect
         if [[ "$isCorrect" == "y" || "$isCorrect" == "Y" ]]; then
            echo "Configuring development connection..."
            sed -i -e "s/DEVDBNAME/$DEVDB/g" ./src/sync-qa-db.sh
            sed -i -e "s/DEVDBUSER/$DEVUSER/g" ./src/sync-qa-db.sh
            sed -i -e "s/DEVDBPASS/$DEVPASS/g" ./src/sync-qa-db.sh
            sed -i -e "s/DEVDBHOST/$DEVHOST/g" ./src/sync-qa-db.sh
            break
         fi
    done
fi
read -n 1 -p "Set remote database connection variables for staging?" setQaDB
if [[ "$setQaDB" == "y" || "$setQaDB" == "Y" ]]; then
    while true; do
         read -p "Staging DB name" QADB
         read -p "Staging DB user" QAUSER
         read -p "Staging DB pass" QAPASS
         read -p "Staging DB host" QAHOST
         read -n 1 -p "Confirm\nDB: $QADB\nUser: $QAUSER\nPass: $QAPASS\nHOST: $QAHOST" isCorrect
         if [[ "$isCorrect" == "y" || "$isCorrect" == "Y" ]]; then
            echo "Configuring staging connection..."
            sed -i -e "s/QADBNAME/$QADB/g" ./src/sync-qa-db.sh
            sed -i -e "s/QADBUSER/$QAUSER/g" ./src/sync-qa-db.sh
            sed -i -e "s/QADBPASS/$QAPASS/g" ./src/sync-qa-db.sh
            sed -i -e "s/QADBHOST/$QAHOST/g" ./src/sync-qa-db.sh
            break
         fi
    done
fi