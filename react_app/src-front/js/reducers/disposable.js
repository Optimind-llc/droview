import { CLEAR_DISPOSABLE } from '../constants/DisposableActionTypes';
import {
  ADD_ADDRESS,
  ADD_VALIDATION,
} from '../constants/ActionTypes';

import {
  CONFIRM_RESERVATION,
  CONFIRM_RESERVATION_SUCCESS,
  CONFIRM_RESERVATION_FAIL,

  CONNECTION_TEST,
  CONNECTION_TEST_SUCCESS,
  CONNECTION_TEST_FAIL,

  RESERVE,
  RESERVE_SUCCESS,
  RESERVE_FAIL,
} from '../constants/TimetableActionTypes';

function change(type, payload) {
  switch (type) {
    case 'ACTION':
      return {
        isFetching: true,
        didInvalidate: false,
        data: {}
      };

    case 'ACTION_SUCCESS':
      return {
        isFetching: false,
        didInvalidate: false,
        data: payload
      };

    case 'ACTION_FAIL':
      return {
        isFetching: false,
        didInvalidate: true,
        data: {}
      };

    default:
      return {};
  }
}

export default function disposable(state = {}, action) {
  const { type, payload } = action;
  switch (type) {
    case CONFIRM_RESERVATION:
    case CONFIRM_RESERVATION_SUCCESS:
    case CONFIRM_RESERVATION_FAIL:
      return Object.assign({}, state, {
        confirmReservation: change(
          type.replace( /CONFIRM_RESERVATION/g , "ACTION" ),
          payload
        )
      });

    case CONNECTION_TEST:
    case CONNECTION_TEST_SUCCESS:
    case CONNECTION_TEST_FAIL:
      return Object.assign({}, state, {
        testResults: change(
          type.replace( /CONNECTION_TEST/g , "ACTION" ),
          payload
        )
      });

    case RESERVE:
    case RESERVE_SUCCESS:
    case RESERVE_FAIL:
      return Object.assign({}, state, {
        reservation: change(
          type.replace( /RESERVE/g , "ACTION" ),
          payload
        )
      });

    case ADD_ADDRESS:
      const { stateName, city, street } = action.address;
      return Object.assign({}, state, { 
        address: { state: stateName, city, street }
      });

    case ADD_VALIDATION:
      return Object.assign({}, state, {
        validation: action.validation
      });

    case CLEAR_DISPOSABLE:
      return {};

    default:
      return state;
  }
}
