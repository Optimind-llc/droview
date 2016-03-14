import { normalize, Schema, arrayOf } from 'normalizr';
import {
  REQUEST_TIMETABLE,
  REQUEST_TIMETABLE_SUCCESS,
  REQUEST_TIMETABLE_FAIL,
} from '../../constants/ActionTypes';

function fill(max, timetable) {
  const exists = timetable.map(t => t.period);
  let fill = []
  for (let p = 1; p <= max; p++) {
    if (exists.indexOf(p) === -1) {
      fill.push({ period: p })
    };
  }

  return timetable.concat(fill).sort((a, b) => {
    if( a.period < b.period ) return -1;
    if( a.period > b.period ) return 1;
  })
}

const initialState = {
  isFetching: false,
  didInvalidate: false,
  timetables: {}
};

function change(state = initialState, type, payload) {
  switch (type) {
  case REQUEST_TIMETABLE:
    return Object.assign({}, state, {
      isFetching: true,
      didInvalidate: false
    });

  case REQUEST_TIMETABLE_SUCCESS:
    return Object.assign({}, state, {
      isFetching: false,
      didInvalidate: false,
      timetables: Object.assign({}, state.timetables,
        payload.timetables.reduce((reshaped, timetable) => {
          const key = timetable[0];
          reshaped[key] = fill(payload.periods, timetable[1]);
          return reshaped;
        }, {})
      )
    });

  case REQUEST_TIMETABLE_FAIL:
    return Object.assign({}, state, {
      isFetching: false,
      didInvalidate: true
    });

  default:
    return state;
  }
}

export default function timetables(state = {}, action) {
  const { type, payload } = action;
  switch (type) {
  case REQUEST_TIMETABLE:
  case REQUEST_TIMETABLE_SUCCESS:
  case REQUEST_TIMETABLE_FAIL:
    return Object.assign({}, state, {
      [payload.key]: change(state[payload.key], type, payload)
    });

  default:
    return state;
  }
}

