import * as types from '../constants/ActionTypes';
import { CALL_API } from '../middleware/fetchMiddleware';
import { customFetch } from '../utils/FetchUtils';
import { REQUEST_TEST_TOKEN, RESERVE, REQUEST_RESERVATIONS, GETJWT, CANCEL } from '../../config/url';

function addSideAlert(status, messageId, value = null) {
  return {
    type: types.ADD_SIDE_ALERT,
    status,
    messageId,
    value
  };
}

function setTestToken(value) {
  return {
    type: types.SET_TEST_TOKEN,
    value: value
  };
}

function deleteTestToken() {
  return {
    type: types.DELETE_TEST_TOKEN
  };
}

function setConfToken(token) {
  return {
    type: types.SET_CONF_TOKEN,
    token: token
  };
}

function deleteConfToken(key) {
  return {
    type: types.DELETE_CONF_TOKEN,
    key: key
  };
}

function updateUserInfoReservations(num) {
  return {
    type: types.UPDATE_USERINFO_RESERVATION,
    num: num
  };
}

export function getJwtIfNeeded(id) {
  return (dispatch, getState) => {
    if (!getState().jwtToken[id]) {
      return dispatch(getJwt());
    }
  };
}

function getJwt() {
  return dispatch => {
    customFetch(GETJWT, 'POST')
    .then(result => dispatch(setConfToken(result)))
    .catch(ex => {
      dispatch(addSideAlert('danger', 'getReservationToken.fail'));
    });
  };
}

function doCancelAction(id) {
  return {
    type: types.DO_CANCEL_ACTION,
    id
  };
}

function doneCancelAction(id) {
  return {
    type: types.DONE_CANCEL_ACTION,
    id
  };
}

export function cancel(id, pivotId) {
  return {
    [CALL_API]: {
      types: [
        types.CANCEL,
        types.CANCEL_SUCCESS,
        types.CANCEL_FAIL
      ],
      endpoint: '/api/cancel',
      method: 'POST',
      body: {id}
    },
    payload: {
      id: pivotId
    },
    meta: {
      actionsOnSuccess: [
        (response) => {
          return {
            type: types.REQUEST_RESERVATIONS_SUCCESS,
            payload: response,
          };
        },
        (response) => {
          return {
            type: types.UPDATE_USERINFO_RESERVATION,
            num: response.reservations.length
          };
        },
        addSideAlert('success', 'cancel.success')
      ]
    }
  };
}

export function fetchReservations() {
  return {
    [CALL_API]: {
      types: [
        types.REQUEST_RESERVATIONS,
        types.REQUEST_RESERVATIONS_SUCCESS,
        types.REQUEST_RESERVATIONS_FAIL
      ],
      endpoint: '/api/rsvList',
      method: 'POST',
      body: null
    }
  };
}
