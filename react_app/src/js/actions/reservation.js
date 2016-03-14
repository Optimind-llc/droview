import * as types from '../constants/ActionTypes';
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

function timetableIsOld(key) {
  return {
    type: types.TIMETABLE_IS_OLD,
    key: key
  };
}

function modalOn() {
  return {
    type: types.MODAL_ON
  };
}

export function modalOff() {
  return {
    type: types.MODAL_OFF
  };
}

function updateUserInfoReservations(num) {
  return {
    type: types.UPDATE_USERINFO_RESERVATION,
    num: num
  };
}

function RequestReservations() {
  return {
    type: types.REQUEST_RESERVATIONS
  };
}

function RequestReservationsSuccess(data) {
  return {
    type: types.REQUEST_RESERVATIONS_SUCCESS,
    data: data
  };
}

function RequestReservationsFail() {
  return {
    type: types.REQUEST_RESERVATIONS_FAIL
  };
}

export function fetchReservations() {
  return dispatch => {
    dispatch(RequestReservations());
    customFetch(REQUEST_RESERVATIONS, 'POST')
    .then(result => {
      dispatch(RequestReservationsSuccess(result));
    })
    .catch(ex => {
      dispatch(RequestReservationsFail());
      dispatch(addSideAlert('danger', 'getReservations.fail'));
    })
  };
}

export function fetchTestToken(request) {
  return dispatch => {
    customFetch(REQUEST_TEST_TOKEN, 'POST', request)
    .then(result => {
      if (result.msg.type === 'error') {
        dispatch(addSideAlert('danger', 'reserve.fail', {reason: result.msg.msg}));
      }
      if (result.msg.type === 'success') {
        dispatch(setTestToken(result.jwt));
        dispatch(modalOn());
      }
    })
    .catch(ex => {
      dispatch(addSideAlert('danger', 'conectionTest.fail'));
    });
  };
}

export function reserve(request, key) {
  return dispatch => {
    customFetch(RESERVE, 'POST', request)
    .then(result => {
      if (result.msg.type === 'error') {
        dispatch(addSideAlert('danger', 'reserve.fail', {reason: result.msg.msg}));
      }
      if (result.msg.type === 'success') {
        dispatch(deleteTestToken());
        dispatch(setConfToken(result.jwt));
        dispatch(updateUserInfoReservations(result.reservations));
        dispatch(timetableIsOld(key));
        dispatch(addSideAlert('success', 'reserve.success'));
      }
    })
    .catch(ex => {
      dispatch(addSideAlert('danger', 'reserve.fail', {reason: 'server'}));
    });
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

export function cancel(request) {
  return dispatch => {
    dispatch(doCancelAction(request.id));
    customFetch(CANCEL, 'POST', request)
    .then(result => {
      dispatch(doneCancelAction(request.id));
      if (result.msg.type === 'error') {
        dispatch(addSideAlert('danger', 'cancel.fail', {reason: result.msg.msg}));
      }
      if (result.msg.type === 'success') {
        dispatch(deleteConfToken(request.id));
        dispatch(RequestReservationsSuccess(result.data));
        dispatch(updateUserInfoReservations(result.reservations));
        dispatch(addSideAlert('success', 'cancel.success'));
      }
    })
    .catch(ex => {
      dispatch(doneCancelAction(request.id));
      dispatch(addSideAlert('danger', 'cancel.fail', {reason: 'server'}));
    });
  };
}
