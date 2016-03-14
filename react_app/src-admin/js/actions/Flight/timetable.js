import * as types from '../../constants/ActionTypes';
import { CALL_API } from '../../middleware/fetchMiddleware';


export function fetchTimetables(request) {
  return {
    [CALL_API]: {
      types: [
        types.REQUEST_TIMETABLE,
        types.REQUEST_TIMETABLE_SUCCESS,
        types.REQUEST_TIMETABLE_FAIL
      ],
      endpoint: 'flight/timetables',
      method: 'POST',
      body: request
    },
    payload: {
      key: request.plan
    }
  }
}

export function openFlight(plan, timestamp, period) {
  return {
    [CALL_API]: {
      types: [
        types.OPEN_FLIGHT,
        types.OPEN_FLIGHT_SUCCESS,
        types.OPEN_FLIGHT_FAIL
      ],
      endpoint: 'flight/flight',
      method: 'POST',
      body: { plan, timestamp, period }
    },
    payload: { plan, timestamp, period }
  }
}

export function closeFlight(plan, timestamp, period, id) {
  return {
    [CALL_API]: {
      types: [
        types.CLOSE_FLIGHT,
        types.CLOSE_FLIGHT_SUCCESS,
        types.CLOSE_FLIGHT_FAIL
      ],
      endpoint: 'flight/flight',
      method: 'DELETE',
      body: { plan, timestamp, id }
    },
    payload: { plan, timestamp, period }
  }
}

