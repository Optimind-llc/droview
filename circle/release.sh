#!/bin/sh

set -e

git config --global user.email $GIT_USER_EMAIL
git config --global user.name $GIT_USER_NAME

CI_RELEASE_VERSION=`date +"v%Y/%m/%d:%H:%M:%S"`



optipng `find public -name "*.png" -type f -follow -print` || echo "ok"
jpegoptim `find public -name "*.jpg" -type f -follow -print` || echo "ok"



git add -A
git commit -m "[auto] release branch (${CI_RELEASE_VERSION})"
git checkout -b $1
git push -f origin $1
