import { combineReducers } from 'redux'
import {reducer as formReducer} from 'redux-form';
import { routeReducer } from 'react-router-redux'
//my reducers
import application from './application';
import alert from './alert';
import disposable from './disposable';
import user from './user';
import plans from './plans';
import timetables from './timetables';
import fetchingNodes from './fetchingNodes';
import reservation from './reservation';
import logs from './logs';

const rootReducer = combineReducers(Object.assign({
  application, alert, disposable, user,
  plans, timetables, fetchingNodes, reservation, logs
}, {
  form: formReducer,
  routing: routeReducer
}));

export default rootReducer


