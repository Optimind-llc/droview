import React, { Component, PropTypes } from 'react';
import { Router, Route, Link, Redirect, IndexRoute } from 'react-router';
import { Provider, connect } from 'react-redux';
import { IntlProvider } from 'react-intl';
import * as i18n from '../../locale';
//Components
//import DevTools from './DevTools';
import App from './App';
import MainHeader from '../components/MainHeader';
import Reserved from '../components/Reserved/Reserved.js';
import Reserve from '../components/Reserve/Reserve.js';
import EditTimetable from '../components/Reserve/EditTimetable.js';
import Ticket from '../components/Ticket/Ticket.js';
import Log from '../components/Log/Log.js';
import TicketLog from '../components/Log/TicketLog.js';
import ReservationLog from '../components/Log/ReservationLog.js';
import Profile from '../components/Profile/Profile.js';

export default class Root extends Component {
  render() {
    const { history, store, locale } = this.props;
    return (
      <Provider store={store}>
        <div>
          <IntlProvider key='intl' locale={locale} messages={i18n[locale]}>
            <Router history={history}>
              <Route path="/" component={App}>
                <Route path="reserved" component={Reserved}/>
                <Route path="reserve" component={Reserve}/>
                <Route path="timetable/:id" component={EditTimetable}/>
                <Route path="ticket" component={Ticket}/>

                <Redirect from="log" to="log/ticket"/>
                <Route path="log" component={Log}>
                  <Route name="Ticket Log" path="ticket" component={TicketLog}/>
                  <Route name="Reservation Log" path="reservation" component={ReservationLog}/>
                </Route>
                <Route path="profile" component={Profile}/>
              </Route>
            </Router>
          </IntlProvider>
          {/*<DevTools/>*/}
        </div>
      </Provider>
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
