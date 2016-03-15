import { _LOCALE } from '../../config/env';
import {
  CANGE_LOCALE,
  CONNECTION_TEST_ON,
  CONNECTION_TEST_OFF
} from '../constants/ApplicationActionTypes';

const initialState = {
  locale: _LOCALE,
  conectionTest: false
};

export default function application(state = initialState, action) {
  switch (action.type) {
  case CANGE_LOCALE:
    return Object.assign({}, state, {
      locale: action.locale
    });

  case CONNECTION_TEST_ON:
    return Object.assign({}, state, {
      conectionTest: true
    });

  case CONNECTION_TEST_OFF:
    return Object.assign({}, state, {
      conectionTest: false
    });

  default:
    return state;
  }
}