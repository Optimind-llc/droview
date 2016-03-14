import * as types from '../constants/ActionTypes';
import { customFetch } from '../utils/FetchUtils';
import { keyToCamel, keyToSnake } from '../utils/ChangeCaseUtils';
import {
  REQUEST_USER_INFO,
  UPDATE_USER_PROF,
  CHANGE_PASSWORD,
  DEACTIVE_USER
} from '../../config/url';

function addSideAlert(status, messageId, value = null) {
  return {
    type: types.ADD_SIDE_ALERT,
    status,
    messageId,
    value
  };
}

function requestUserInfo() {
  return {
    type: types.REQUEST_USERINFO
  };
}

export function requestUserInfoSuccess(data) {
  return {
    type: types.REQUEST_USERINFO_SUCCESS,
    data: data
  };
}

export function requestUserInfoFail() {
  return {
    type: types.REQUEST_USERINFO_FAIL
  };
}

export function fetchUserInfo() {
  return dispatch => {
    dispatch(requestUserInfo());
    customFetch(REQUEST_USER_INFO, 'POST')
    .then(result => {
      dispatch(requestUserInfoSuccess(keyToCamel(result)));
    })
    .catch(ex => {
      dispatch(requestUserInfoFail());
      dispatch(addSideAlert('danger', 'getUserInfo.fail'));
    })
  }
}

export function UpdateUserProf(request) {
  return dispatch => {
    dispatch(requestUserInfo());
    customFetch(UPDATE_USER_PROF, 'POST', request)
    .then(result => {
      dispatch(requestUserInfoSuccess(keyToCamel(result.userProf)));
      dispatch(addSideAlert('success', 'updateUserProf.success'));
    })
    .catch(ex => {
      dispatch(requestUserInfoFail());
      dispatch(addSideAlert('danger', 'updateUserProf.fail'));
    })
  }
}

function changePassword() {
  return {
    type: types.CHANGE_PASS
  };
}

export function postChangePassword(request) {
  return dispatch => {
    dispatch(changePassword());
    customFetch(CHANGE_PASSWORD, 'POST', request)
    .then(result => {
      dispatch(addSideAlert('success', 'changePassword.success'));
    })
    .catch(ex => {
      dispatch(addSideAlert('danger', 'changePassword.fail'));
    })
  }
}

export function destroy(request) {
  return dispatch => {
    customFetch(`/auth`, 'DELETE')
    .then(result => {
      location.href = 'http://l.com'
    })
    .catch(ex => {
      dispatch(addSideAlert('danger', 'destroy.fail'));
    })
  }
}

function addValidation(validation) {
  return {
    type: types.ADD_VALIDATION,
    validation
  };
}

function addAddress(address) {
  return {
    type: types.ADD_ADDRESS,
    address
  };
}

export function fetchAddress(code) {
  return (dispatch) => {
    customFetch(`/admin/single/getAddress/${code.slice(0,3)}/${code.slice(3,7)}`, 'GET')
    .then(result => {
      dispatch(addAddress(result));
    })
    .catch(ex => {
      dispatch(addValidation({
        postalCode: {
          value: code,
          status: 'error',
          message: 'validation.postalCode.notValid'
        }
      }));
    })
  }
}