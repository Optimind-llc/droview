import { createStore, combineReducers, applyMiddleware, compose } from 'redux';
import { syncHistory } from 'react-router-redux';
import thunk from 'redux-thunk';
import promise from 'redux-promise';
import createLogger from 'redux-logger';
import persistState from 'redux-localstorage';
import Middlewares from '../middleware';
import rootReducer from '../reducers';
import DevTools from '../containers/DevTools';

export default function configureStore(initialState = {}, history) {
  const logger = createLogger({
    level: 'info',
    duration: true
  });

  const reduxRouterMiddleware = syncHistory(history);

  //persistStateはdevToolsより上に記述
  const createStoreWithMiddleware = compose(
    applyMiddleware(...Middlewares, thunk, promise, logger, reduxRouterMiddleware),
    persistState(['application']),
    //window.devToolsExtension ? window.devToolsExtension() : DevTools.instrument()
  )(createStore);

  const store = createStoreWithMiddleware(rootReducer, initialState);
  // Required for replaying actions from devtools to work
  reduxRouterMiddleware.listenForReplays(store)

  if (module.hot) {
    module.hot.accept('../reducers', () => {
      const nextReducer = require('../reducers');
      store.replaceReducer(nextReducer);
    });
  }

  return store;
}
