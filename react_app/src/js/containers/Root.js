import React, { Component, PropTypes } from 'react';
import { Router, Route, Link, Redirect, IndexRoute, browserHistory } from 'react-router';
import { connect } from 'react-redux';
//import { DevTools, DebugPanel, LogMonitor } from 'redux-devtools/lib/react';
//import DiffMonitor from 'redux-devtools-diff-monitor';
import { IntlProvider } from 'react-intl';
//Config
import { _ADMIN_DOMAIN_NAME } from '../../config/env';
import * as i18n from '../../locale';
//Components
import App from './App';
import MainHeader from '../components/MainHeader';
import Reserved from '../components/Reserved/Reserved.js';
import Reserve from '../components/Reserve/Reserve.js';
import Ticket from '../components/Ticket/Ticket.js';
import Log from '../components/Log/Log.js';
import Profile from '../components/Profile/Profile.js';

export default class Root extends Component {
  render() {
    const { locale } = this.props;
    return (
      <div>
      	<MainHeader/>
        <IntlProvider key='intl' locale={locale} messages={i18n[locale]}>
          <Router history={browserHistory}>
            <Route path="/mypage/" component={App}>
              <Route path="reserved" component={Reserved}/>
              <Route path="reserve" component={Reserve}/>
              <Route path="ticket" component={Ticket}/>
              <Route path="log" component={Log}/>
              <Route path="profile" component={Profile}/>
            </Route>
          </Router>
        </IntlProvider>
        {/*<DevTools store={store} monitor={DiffMonitor} shortcut='ctrl+d'/>*/}
      </div>
    )
  }
}

Root.propTypes = {
  locale: PropTypes.string.isRequired
};

function mapStateToProps(state) {
  return {
    locale: state.application.locale
  };
}

export default connect( mapStateToProps )(Root);
