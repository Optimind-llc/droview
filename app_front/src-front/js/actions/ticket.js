import * as types from '../constants/ActionTypes';
import { customFetch } from '../utils/FetchUtils';
import { WEBPAY, PIN } from '../../config/url';

function addSideAlert(status, messageId, value = null) {
  return {
    type: types.ADD_SIDE_ALERT,
    status,
    messageId,
    value
  };
}

function updateUserInfoTickets(num) {
  return {
    type: types.UPDATE_USERINFO_TICKETS,
    num: num
  };
}

function requestTicket() {
  return {
    type: types.REQUEST_TICKET
  };
}

export function requestTicketSuccess() {
  return {
    type: types.REQUEST_TICKET_SUCCESS
  };
}

export function requestTicketFail() {
  return {
    type: types.REQUEST_TICKET_FAIL
  };
}

export function fetchWebpay(request) {
  return dispatch => {
    dispatch(requestTicket());
    console.log('aaa')
    customFetch(WEBPAY, 'POST', request)
    .then(result => {
      if (result.msg.type === 'error') {
        dispatch(addSideAlert('danger', 'addTicket.fail'));
      }
      else if (result.msg.type === 'success') {
        dispatch(requestTicketSuccess());
        dispatch(updateUserInfoTickets(result.tickets));
        dispatch(addSideAlert('success', 'addTicket.success'));
      }
    })
    .catch(ex => {
      dispatch(requestTicketFail());
      dispatch(addSideAlert('danger', 'addTicket.fail'));
    })
  }
}

export function fetchPin(pin) {
  return dispatch => {
    dispatch(requestTicket());
    customFetch(PIN, 'POST', pin)
    .then(result => {
      dispatch(requestTicketSuccess());
      dispatch(updateUserInfoTickets(result.tickets));
      dispatch(addSideAlert('success', 'addTicket.success'));
    })
    .catch(ex => {
      dispatch(requestTicketFail());
      dispatch(addSideAlert('danger', 'addTicket.fail'));
    })
  }
}
