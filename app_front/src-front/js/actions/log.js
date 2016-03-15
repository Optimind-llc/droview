import * as types from '../constants/ActionTypes';
import { CALL_API } from '../middleware/fetchMiddleware';

export function fetchTicketLogs() {
  return {
    [CALL_API]: {
      types: [
        types.REQUEST_TICKET_LOG,
        types.REQUEST_TICKET_LOG_SUCCESS,
        types.REQUEST_TICKET_LOG_FAIL
      ],
      endpoint: '/api/getLog',
      method: 'POST',
      body: null
    }
  };
}

export function fetchReservationLogs() {
  return {
    [CALL_API]: {
      types: [
        types.REQUEST_RESERVATION_LOG,
        types.REQUEST_RESERVATION_LOG_SUCCESS,
        types.REQUEST_RESERVATION_LOG_FAIL
      ],
      endpoint: '/droview/log/reservation/fetch',
      method: 'GET',
      body: null
    }
  };
}
