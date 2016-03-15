import * as types from '../constants/ActionTypes';

export function addSideAlert(status, messageId, value = null) {
  return {
    type: types.ADD_SIDE_ALERT,
    status,
    messageId,
    value
  };
}