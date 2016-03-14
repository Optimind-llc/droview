import * as types from '../constants/ActionTypes';
import { customFetch } from '../utils/FetchUtils';
import { REQUEST_LOG } from '../../config/url';

function addSideAlert(status, messageId, value = null) {
  return {
    type: types.ADD_SIDE_ALERT,
    status,
    messageId,
    value
  };
}

export function clearLog() {
  return {
    type: types.CLEAR_LOG
  };
}

function setLogPage(page) {
  return {
    type: types.SET_LOG_PAGE,
    page: page
  };
}

function requestLog() {
  return {
    type: types.REQUEST_LOG,
  };
}

function requestLogSuccess(data) {
  return {
    type: types.REQUEST_LOG_SUCCESS,
    data: data
  };
}

function requestLogFail() {
  return {
    type: types.REQUEST_LOG_FAIL,
  };
}

export function fetchLog() {
  return dispatch => {
    dispatch(requestLog());
    customFetch(REQUEST_LOG, 'POST')
    .then(result => {
      dispatch(requestLogSuccess(result));
    })
    .catch(ex => {
      dispatch(requestLogFail());
      dispatch(addSideAlert('danger', 'getLog.fail'));
    })
  }
}
