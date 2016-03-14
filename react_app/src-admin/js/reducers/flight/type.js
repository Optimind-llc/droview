import {
  ADD_TYPE,
  DELETE_TYPE
} from '../../constants/ActionTypes';

const initialState = {};

export default function application(state = initialState, action) {
  switch (action.type) {
    case ADD_TYPE:
      return Object.assign({}, state, {
        locale: action.locale
      });

    case DELETE_TYPE:
      return Object.assign({}, state, {
        locale: action.locale
      });

    default:
      return state;
  }
}
