import 'babel-polyfill';
import React from 'react';
import { render } from 'react-dom';
import createBrowserHistory from 'history/lib/createBrowserHistory';
import { useRouterHistory } from 'react-router';
import { addLocaleData } from 'react-intl';
import Root from './js/containers/Root';
import configureStore from './js/store/configureStore';
import en from 'react-intl/locale-data/en';
import ja from 'react-intl/locale-data/ja';

const browserHistory = useRouterHistory(createBrowserHistory)({
  basename: '/droview'
})

const store = configureStore({}, browserHistory);

addLocaleData(en);
addLocaleData(ja);

render(
  <Root history={browserHistory} store={store}/>,
  document.getElementById('root')
);
