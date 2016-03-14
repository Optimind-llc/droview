import { callApi } from '../utils/FetchUtils';

export const CALL_API = Symbol('Call API');

export default store => next => action => {
  const callAPI = action[CALL_API]
  if (typeof callAPI === 'undefined') {
    return next(action)
  }

  let { endpoint } = callAPI;
  const { types, method, body } = callAPI;

  if (typeof endpoint === 'function') {
    endpoint = endpoint(store.getState())
  }

  console.log(types);

  if (typeof endpoint !== 'string') {
    throw new Error('Specify a string endpoint URL.')
  }
  if (!Array.isArray(types) || types.length !== 3) {
    throw new Error('Expected an array of three action types.')
  }
  if (!types.every(type => typeof type === 'string')) {
    throw new Error('Expected action types to be strings.')
  }

  function actionWith(data) {
    console.log(data)
    return {
      type: data.type,
      payload: Object.assign({}, action.payload, data.payload),
      error: data.error,
      meta: Object.assign({}, action.meta, data.meta)
    };
  }

  const [ requestType, successType, failureType ] = types;
  next(actionWith({ type: requestType }))

  return callApi(endpoint, method, body).then(
    response => next(actionWith({
      type: successType,
      payload: response,
      error: false
    })),
    error => next(actionWith({
      type: failureType,
      payload: {
        status: 'danger',
        messageId: `server.${error.status}`
      },
      error: true
    }))
  )
}
