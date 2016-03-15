import * as actionTypes from '../constants/ActionTypes';
import * as disposableActionTypes from '../constants/DisposableActionTypes';
const types = Object.assign(actionTypes, disposableActionTypes);

export function deleteSideAlerts(keys) {
  return {
    type: types.DELETE_SIDE_ALERT,
    keys
  };
}

export function clearDisposable() {
  return {
    type: types.CLEAR_DISPOSABLE,
  };
}
