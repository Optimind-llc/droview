import {
  REQUEST_TIMETABLE,
  REQUEST_TIMETABLE_SUCCESS,
  REQUEST_TIMETABLE_FAIL,
  TIMETABLE_IS_OLD,
  SET_PLANS
} from '../constants/ActionTypes';

function change(state = {
  isFetching: false,
  didInvalidate: false,
  lastUpdated: '',
  isOld: false,
  data: {}
}, action) {
  switch (action.type) {
  case REQUEST_TIMETABLE:
    return Object.assign({}, state, {
      isFetching: true,
      isOld: false,
      didInvalidate: false
    });

  case REQUEST_TIMETABLE_SUCCESS:
    return Object.assign({}, state, {
      isFetching: false,
      didInvalidate: false,
      data: action.data,
      lastUpdated: action.receivedAt
    });

  case REQUEST_TIMETABLE_FAIL:
    return Object.assign({}, state, {
      isFetching: false,
      didInvalidate: true
    });

  case TIMETABLE_IS_OLD:
    return Object.assign({}, state, {
      isOld: true
    });

  default:
    return state;
  }
}

export default function timetables(state = {}, action) {
  switch (action.type) {
  case REQUEST_TIMETABLE:
  case REQUEST_TIMETABLE_SUCCESS:
  case REQUEST_TIMETABLE_FAIL:
  case TIMETABLE_IS_OLD:
    return Object.assign({}, state, {
      [action.key]: change(state[action.key], action)
    });

  case SET_PLANS:
    return Object.assign({}, state, {plans: action.plans});
  default:
    return state;
  }
}
