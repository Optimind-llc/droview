import * as types from '../constants/ActionTypes';
import { customFetch } from '../utils/FetchUtils';
import { REQUEST_TIMETABLE, REQUEST_DEFAULT_STATUS } from '../../config/url';

function addSideAlert(status, messageId, value) {
  return {
    type: types.ADD_SIDE_ALERT,
    status,
    messageId,
    value
  };
}

function setPlans(plans) {
  return {
    type: types.SET_PLANS,
    plans: plans
  };
}

function setTypeStatus(status) {
  return {
    type: types.SET_TYPE_STATUS,
    status: status
  };
}

function setPlaceStatus(status) {
  return {
    type: types.SET_PLACE_STATUS,
    status: status
  };
}

function changeTypeChecked(id) {
  return {
    type: types.CHANGE_TYPE_CHECKED,
    id: id
  };
}

function changeActivePlace(ids) {
  return {
    type: types.CHANGE_PLACE_ACTIVE,
    ids: ids
  };
}

function changePlaceChecked(id) {
  return {
    type: types.CHANGE_PLACE_CHECKED,
    id: id
  };
}

function changeWeek(n) {
  return {
    type: types.CHANGE_WEEK,
    week: n
  };
}

function requestTimetable(key) {
  return {
    type: types.REQUEST_TIMETABLE,
    key: key
  };
}

function requestTimetableSuccess(key, data) {
  return {
    type: types.REQUEST_TIMETABLE_SUCCESS,
    key: key,
    data: data,
    //receivedAt: Date.now(),
  };
}

function requestTimetableFail(key) {
  return {
    type: types.REQUEST_TIMETABLE_FAIL,
    key: key
  };
}

export function fetchTimetable(key, request) {
  return dispatch => {
    dispatch(requestTimetable(key));
    customFetch(REQUEST_TIMETABLE, 'POST', request)
    .then(result => {
      dispatch(requestTimetableSuccess(key, result));
    })
    .catch(ex => {
      dispatch(requestTimetableFail(key));
    })
  };
}

function shouldFetchTimetable(timetable) {
  if (!timetable) {
    return true;
  }
  if (timetable.isFetching) {
    return false;
  }
  return timetable.isOld;
  //return timetable.didInvalidate;
}

function getCheckedId(status) {
  for (const i of status) if (i.checked) return i.id;
}

function getCheckedPlaceId(plans, places, typeId) {
  const ids = plans[typeId];
  const checkedId = getCheckedId(places);

  if ( ids.indexOf(checkedId) >= 0 ) return checkedId;
  return Math.min.apply(null, ids);
}

export function fetchTimetableIfNeeded(t, p, w = 0) {
  return (dispatch, getState) => {
    const { timetables: {plans}, selector: {flightTypes, places, week}} = getState();
    const request = {
      flightType: t || getCheckedId(flightTypes),
      place: p || getCheckedPlaceId(plans, places, t || getCheckedId(flightTypes)),
      week: week + w
    };
    const key = request.flightType + '_' + request.place + '_' + request.week;
    const timetable = getState().timetables[key];

    dispatch(changeTypeChecked(request.flightType));
    dispatch(changeActivePlace(plans[request.flightType]));
    dispatch(changePlaceChecked(request.place));
    dispatch(changeWeek(request.week));

    if (shouldFetchTimetable(timetable)) {
      return dispatch(fetchTimetable(key, request));
    }
  };
}

function convertPlans(plans) {
  return plans.reduce((converted, plan) => {
    let { type_id, place_id } = plan;
    if (!converted[type_id]) {
      converted[type_id] = [Number(place_id)];
    } else {
      converted[type_id].push(Number(place_id));
    }
    return converted;
  }, {});
}

export function fetchDefaultStatus() {
  return dispatch => {
    customFetch(REQUEST_DEFAULT_STATUS, 'POST')
    .then(result => {
      const { types, places, plans } = result.selector;
      const { key, data } = result.timetable;
      const minTypeId = Math.min.apply({}, plans.map(p => Number(p.type_id)));
      const minPlaceId = Math.min.apply({}, plans.map(p => Number(p.type_id) === minTypeId ? p.place_id : 100000));

      //timetableに登録
      dispatch(setPlans(convertPlans(plans)));
      dispatch(requestTimetableSuccess(key, data));
      //selectorに登録
      dispatch(setTypeStatus(types));
      dispatch(setPlaceStatus(places));
      dispatch(changeTypeChecked(minTypeId));
      dispatch(changeActivePlace(convertPlans(plans)[minTypeId]));
      dispatch(changePlaceChecked(minPlaceId));
    })
    .catch(ex => {
      dispatch(requestTimetableFail('1_1_0'));
      dispatch(addSideAlert('danger', 'server.' + ex.status));
    })
  };
}
