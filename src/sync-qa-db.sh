#!/bin/bash

# get current working directory
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"


# prep environment variables
read -p "From which environment? [qa/dev]: " ENV
if [ -z "$ENV" ];
    then ENV="dev"; echo "$ENV"; # default
fi


if [ "$ENV" = "dev" ]; then
    DBNAME="DEVDBNAME"
    DBHOST="DEVDBHOST"
    DBUSER="DEVDBUSER"
    PW="DEVDBPASS"
elif [ "$ENV" = "qa" ]
then
    DBNAME="QADBNAME"
    DBHOST="QADBHOST"
    DBUSER="QADBUSER"
    PW="QADBPASS"
else
    echo "Unknown environment. ABORT"
    exit 0
fi


# dump schema.sql and schema-data.sql (if desired)
read -p "Dump all data along with schema? [y/n]: " GETDATA
if [ -z "$GETDATA" ];
    then GETDATA="n"; echo "$GETDATA"; # default
fi

echo "Pulling $DBNAME schema to local $DIR/src/sql/schema.sql..."
vagrant ssh -c"mysqldump -d -u$DBUSER -p\"$PW\" -h$DBHOST $DBNAME > \"/vagrant/sql/schema.sql\""
if [ "$GETDATA" = "y" ]; then
    echo "Pulling $DBNAME data to local $DIR/src/sql/schema-data.sql..."
    vagrant ssh -c"mysqldump -u$DBUSER -p\"$PW\" -h$DBHOST $DBNAME > \"/vagrant/sql/schema-data.sql\""
fi


# overwrite local database (if desired)
read -p "Overwrite your local db w/ the $ENV server's data? [y/n]: " APPLY
if [ -z "$APPLY" ];
    then APPLY="n"; echo "$APPLY"; # default
fi

if [ "$APPLY" = "y" ]; then
    echo "Applying schema to your local vagrant database..."
    if [ "$GETDATA" = "y" ]; then
        sh -c "\"$DIR/apply-schema.sh\" -d"
    else
        sh -c "\"$DIR/apply-schema.sh\""
    fi
fi
