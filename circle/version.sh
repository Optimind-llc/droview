BRANCH=`git rev-parse --abbrev-ref HEAD`
COMMIT_ID=`git --no-pager log --pretty=oneline --abbrev-commit | head -n 1`
FILE_NAME=app_front_version.json
cat << _EOB_ > $FILE_NAME
{
    "BRANCH": "$BRANCH",
    "COMMIT_ID": "$COMMIT_ID"
}
_EOB_

cp -f $FILE_NAME laravel_app/public