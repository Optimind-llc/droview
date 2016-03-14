import {
  MODAL_ON,
  MODAL_OFF,
} from '../constants/ActionTypes';

const initialState = false;

export default function message(state = initialState, action) {
  switch (action.type) {
  case MODAL_ON:
    return true;

  case MODAL_OFF:
    return false;

  default:
    return state;
  }
}
