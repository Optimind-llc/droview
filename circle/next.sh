GIT_NAME=`git --no-pager log  | head -n 2 |tail -1 | cut -d " " -f 2`

case $GIT_NAME in
    atyenoria )
    echo $GIT_NAME
    curl -X POST https://circleci.com/api/v1/project/HoritaWorks/$1/tree/master?circle-token=${CIRCLE_CI_API_KEY}
        ;;
    shiichi )
    echo $GIT_NAME
    curl -X POST https://circleci.com/api/v1/project/HoritaWorks/$1/tree/master?circle-token=807a7f50585409045679180ee0892a57f09ad346
        ;;
<<<<<<< HEAD
esac
=======
esac
>>>>>>> master-build-ok
