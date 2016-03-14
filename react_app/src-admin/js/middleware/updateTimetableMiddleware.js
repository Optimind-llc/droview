import * as types from '../constants/ActionTypes';

const targetTypes = [
  types.OPEN_FLIGHT_SUCCESS,
  types.CLOSE_FLIGHT_SUCCESS,
];

export default store => next => action => {
  const { type, payload, error, meta } = action;

  if (targetTypes.indexOf(type) === -1) {
      return next(action);
  }

  const { plan, timestamp, period, ...rest } = payload;

  next({
    type,
    payload: { plan, timestamp, period },
    meta
  });
  next({
    type: types.REQUEST_TIMETABLE_SUCCESS,
    payload: { key: plan, ...rest },
    meta,
  });
};
