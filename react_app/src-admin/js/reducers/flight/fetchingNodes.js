import {
  OPEN_FLIGHT,
  OPEN_FLIGHT_SUCCESS,
  OPEN_FLIGHT_FAIL,
  CLOSE_FLIGHT,
  CLOSE_FLIGHT_SUCCESS,
  CLOSE_FLIGHT_FAIL
} from '../../constants/ActionTypes';

function change(state = {}, type, payload) {
  const { timestamp, period } = payload;
  switch (type) {
    case OPEN_FLIGHT:
    case CLOSE_FLIGHT:
      return Object.assign({}, state, {
        [timestamp]: typeof state[timestamp] === 'undefined' ? [ period ] : state[timestamp].concat(period)
      });

    case OPEN_FLIGHT_SUCCESS:
    case OPEN_FLIGHT_FAIL:
    case CLOSE_FLIGHT_SUCCESS:
    case CLOSE_FLIGHT_FAIL:
      return Object.assign({}, state, {
        [timestamp]: state[timestamp].filter(n => n !== period),
      });

    default:
      return state;
  }
}

export default function fetchingNodes(state = {}, action) {
  const { type, payload } = action;
  switch (type) {
  case OPEN_FLIGHT:
  case OPEN_FLIGHT_SUCCESS:
  case OPEN_FLIGHT_FAIL:
  case CLOSE_FLIGHT:
  case CLOSE_FLIGHT_SUCCESS:
  case CLOSE_FLIGHT_FAIL:
    return Object.assign({}, state, {
      [payload.plan]: change(state[payload.plan], type, payload)
    });

  default:
    return state;
  }
}