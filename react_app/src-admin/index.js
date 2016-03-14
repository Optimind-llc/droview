import 'babel-polyfill';
import React from 'react';
import { render } from 'react-dom';
import { Provider } from 'react-redux';
import Root from './js/containers/Root';
import configureStore from './js/store/configureStore';
import { addLocaleData } from 'react-intl';
import en from 'react-intl/lib/locale-data/en';
import de from 'react-intl/lib/locale-data/de';
import it from 'react-intl/lib/locale-data/it';
import ja from 'react-intl/lib/locale-data/ja';

const store = configureStore();

addLocaleData(en);
addLocaleData(de);
addLocaleData(it);
addLocaleData(ja);

render(
  <Provider store={store}>
    <Root/>
  </Provider>,
  document.getElementById('root')
);
