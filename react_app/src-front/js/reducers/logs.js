import {
  REQUEST_TICKET_LOG,
  REQUEST_TICKET_LOG_SUCCESS,
  REQUEST_TICKET_LOG_FAIL,
  REQUEST_RESERVATION_LOG,
  REQUEST_RESERVATION_LOG_SUCCESS,
  REQUEST_RESERVATION_LOG_FAIL,
} from '../constants/ActionTypes';

function change(type, logs) {
  switch (type) {
    case 'REQUEST_LOG':
      return {
        isFetching: true,
        didInvalidate: false,
        data: []
      };

    case 'REQUEST_LOG_SUCCESS':
      return {
        isFetching: false,
        didInvalidate: false,
        data: logs
      };

    case 'REQUEST_LOG_FAIL':
      return {
        isFetching: false,
        didInvalidate: true,
        data: []
      };

    default:
      return {
        isFetching: false,
        didInvalidate: false,
        data: []
      };
  }
}

const initialState = {
  ticket: {},
  reservation: {}
};

export default function logs(state = initialState, action) {
  const { type, payload } = action;
  switch (type) {
    case REQUEST_TICKET_LOG:
    case REQUEST_TICKET_LOG_SUCCESS:
    case REQUEST_TICKET_LOG_FAIL:
      return Object.assign({}, state, {
        ticket: change(
          type.replace( /_TICKET/g , ''),
          payload.ticketLogs
        )
      });

    case REQUEST_RESERVATION_LOG:
    case REQUEST_RESERVATION_LOG_SUCCESS:
    case REQUEST_RESERVATION_LOG_FAIL:
      return Object.assign({}, state, {
        reservation: change(
          type.replace( /_RESERVATION/g , ''),
          payload.reservationLogs
        )
      });

    default:
      return state;
  }
}



