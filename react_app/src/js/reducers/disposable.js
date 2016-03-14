import {
  ADD_ADDRESS,
  ADD_VALIDATION,
  CLEAR_DISPOSABLE
} from '../constants/ActionTypes';

const initialState = {
  address: {},
  validation: {}
};

export default function disposable(state = initialState, action) {
  switch (action.type) {
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
    return initialState;

  default:
    return state;
  }
}
