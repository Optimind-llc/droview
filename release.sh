
#!/bin/sh

set -e

git config --global user.email $GIT_USER_EMAIL
git config --global user.name $GIT_USER_NAME

CI_RELEASE_VERSION=`date +"v%Y/%m/%d:%H:%M:%S"`
git checkout -b $1
git add -A
git commit -m "[auto] release branch (${CI_RELEASE_VERSION})"
git push -f origin $1