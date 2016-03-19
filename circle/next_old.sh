COMMIT_ID=`git --no-pager log --pretty=oneline --abbrev-commit | head -n 1`
echo $COMMIT_ID

if [[ "$COMMIT_ID" =~ "++" ]]; then
curl -X POST https://circleci.com/api/v1/project/HoritaWorks/laravel-prod-image/tree/master?circle-token=$CIRCLE_CI_API_KEY
echo "build next project"
fi



cd laravel_app && sh release.sh $STABLE_LARABEL_PUSH_BRANCH

