import {
  REQUEST_RESERVATIONS,
  REQUEST_RESERVATIONS_SUCCESS,
  REQUEST_RESERVATIONS_FAIL,
  DO_CANCEL_ACTION,
  DONE_CANCEL_ACTION
} from '../constants/ActionTypes';

const initialState = {
  reservations: null,
  isFetching: false,
  didInvalidate: false,
  isCanceling: []
};

export default function reservation(state = initialState, action) {
  switch (action.type) {
  case REQUEST_RESERVATIONS:
    return Object.assign({}, state, {
      isFetching: true,
      didInvalidate: false
    });

  case REQUEST_RESERVATIONS_SUCCESS:
    return Object.assign({}, state, {
      isFetching: false,
      didInvalidate: false,
      reservations: action.data.reduce((reservations, current) => {
        reservations[current.pivot.id] = current;
        return reservations;
      }, {})
    });

  case REQUEST_RESERVATIONS_FAIL:
    return Object.assign({}, state, {
      isFetching: false,
      didInvalidate: true
    });

  case DO_CANCEL_ACTION:
    return Object.assign({}, state, {
      isCanceling: state.isCanceling.concat([action.id])
    });

  case DONE_CANCEL_ACTION:
    return Object.assign({}, state, {
      isCanceling: state.isCanceling.filter(id => id !== action.id)
    });

  default:
    return state;
  }
}
