import {
  REQUEST_RESERVATIONS,
  REQUEST_RESERVATIONS_SUCCESS,
  REQUEST_RESERVATIONS_FAIL,
  CANCEL,
  CANCEL_SUCCESS,
  CANCEL_FAIL
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
      reservations: action.payload.reservations
    });

  case REQUEST_RESERVATIONS_FAIL:
    return Object.assign({}, state, {
      isFetching: false,
      didInvalidate: true
    });

  case CANCEL:
    return Object.assign({}, state, {
      isCanceling: state.isCanceling.concat([action.payload.id])
    });

  case CANCEL_SUCCESS:
    return Object.assign({}, state, {
      isCanceling: state.isCanceling.filter(id => id !== action.payload.id)
    });

  case CANCEL_FAIL:
    return Object.assign({}, state, {
      isCanceling: state.isCanceling.filter(id => id !== action.payload.id)
    });

  default:
    return state;
  }
}
