import { createStore, combineReducers, applyMiddleware, compose } from 'redux';
import { browserHistory } from 'react-router';
import { syncHistory } from 'react-router-redux';
import thunk from 'redux-thunk';
import promise from 'redux-promise';
import persistState from 'redux-localstorage';
import rootReducer from '../reducers';

// Sync dispatched route actions to the history
const reduxRouterMiddleware = syncHistory(browserHistory)

const createStoreWithMiddleware = compose(
  applyMiddleware(thunk, promise),
  persistState(['jwtToken'])
)(createStore);

export default function configureStore(initialState) {
  const store = createStoreWithMiddleware(rootReducer, initialState, thunk, promise);
  return store;
}
