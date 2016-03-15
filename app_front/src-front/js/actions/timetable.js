import * as types from '../constants/TimetableActionTypes';
import { CALL_API } from '../middleware/fetchMiddleware';
import { connectionTestOn } from './application';
import { clearDisposable } from './initialize';
import { addSideAlert } from './alert';

export function fetchTimetables(request) {
  return {
    [CALL_API]: {
      types: [
        types.REQUEST_TIMETABLE,
        types.REQUEST_TIMETABLE_SUCCESS,
        types.REQUEST_TIMETABLE_FAIL
      ],
      endpoint: '/droview/timetables',
      method: 'POST',
      body: request
    },
    payload: {
      key: request.planId
    }
  };
}

export function confirmReservation(id) {
  return {
    [CALL_API]: {
      types: [
        types.CONFIRM_RESERVATION,
        types.CONFIRM_RESERVATION_SUCCESS,
        types.CONFIRM_RESERVATION_FAIL
      ],
      endpoint: '/droview/confirmReservation',
      method: 'POST',
      body: { id }
    },
    meta: {
      actionsOnSuccess: [
        connectionTest
      ]
    }
  };
}

//be called on confirmReservation success
function connectionTest(response) {
  return {
    [CALL_API]: {
      types: [
        types.CONNECTION_TEST,
        types.CONNECTION_TEST_SUCCESS,
        types.CONNECTION_TEST_FAIL
      ],
      endpoint: '/connectionTest',
      method: 'POST',
      body: response
    }
  }
}

export function reserve(request) {
  return {
    [CALL_API]: {
      types: [
        types.RESERVE,
        types.RESERVE_SUCCESS,
        types.RESERVE_FAIL
      ],
      endpoint: '/droview/reserve',
      method: 'POST',
      body: request
    },
    meta: {
      actionsOnSuccess: [
        (response) => {
          return {
            type: types.REQUEST_TIMETABLE_SUCCESS,
            payload: { ...response},
            meta: { timestamp: new Date().getTime()}
          };
        },
        (response) => {
          return {
            type: types.UPDATE_USERINFO_RESERVATION,
            num: response.reservations
          };
        },
        clearDisposable(),
        addSideAlert('success', 'reserve.success')
      ],
      actionsOnFail: [clearDisposable()],
    }
  }
}
