#!/bin/bash

git diff --name-only --diff-filter=ADMR @~..@ | grep -q database/migrations
if [ $? -eq 1 ]; then
    echo "No DB changes";
    exit 0;
fi

sudo apt update -y
sudo apt install default-jre python-pydot python-pydot-ng graphviz -y

REPO_NAME=schemacrawler/SchemaCrawler
WORK_DIR=/tmp
ZIP_FILE_NAME=schemacrawler.zip
ZIP_FILE_PATH=$WORK_DIR/$ZIP_FILE_NAME
LATEST_RELEASE_URL=https://github.com/$REPO_NAME/releases/latest
LATEST_VERSION=$(echo $(curl -L -s -H 'Accept: application/json' $LATEST_RELEASE_URL) | sed -e 's/.*"tag_name":"\([^"]*\)".*/\1/')

UPLOAD_ENDPOINT="https://production-review-tool.herokuapp.com/api/uploadDiagram"

wget -O $ZIP_FILE_PATH https://github.com/$(wget $LATEST_RELEASE_URL -O - | egrep '/.*/.*/.*zip' -o)

unzip -q -o $ZIP_FILE_PATH -d $WORK_DIR

if [ $DB_DRIVER == 'postgres' ]; then SC_SERVER=postgresql; else SC_SERVER=$DB_DRIVER; fi

$WORK_DIR/schemacrawler-$(echo $LATEST_VERSION | cut -d v -f 2)-distribution/_schemacrawler/schemacrawler.sh -server=$SC_SERVER -host=$DB_HOST -user=$DB_USER -password=$DB_PASS -database=$DB_NAME -infolevel=maximum -routines= -command=schema -outputfile=/tmp/db-schema.png

curl -X POST $UPLOAD_ENDPOINT -H "accept: application/json" -H "x-access-token: $GITHUB_ACCESS_TOKEN" -H "x-key: $GITHUB_USER_ID" -H "Content-Type: multipart/form-data" -F "comment=DB schema" -F "repository_full_name=uCreateit/$CIRCLE_PROJECT_REPONAME" -F "databaseFileUpload=@/tmp/db-schema.png;type=image/png"