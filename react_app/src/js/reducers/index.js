import { combineReducers } from 'redux'
import {reducer as formReducer} from 'redux-form';
import { routeReducer } from 'react-router-redux'
//my reducers
import application from './application';
import alert from './alert';
import disposable from './disposable';
import user from './user';
import timetables from './timetables';
import selector from './selector';
import modal from './modal';
import reservation from './reservation';
import logs from './logs';
import jwtToken from './jwtToken';

//reducers/index.jsから全てのreducerを取得しformReducer,routeReducerとcombine
const rootReducer = combineReducers(Object.assign({
	application, alert, disposable, user, timetables, selector, modal, reservation, logs, jwtToken
  }, {
    form: formReducer,
    routing: routeReducer
  }
));

export default rootReducer


