import React, { Component, PropTypes } from 'react';
import { Router, Route, Redirect, browserHistory } from 'react-router';
import { connect } from 'react-redux';
import { IntlProvider } from 'react-intl';
//import DevTools from './DevTools';
//Config
import { _ADMIN_DOMAIN_NAME } from '../../config/env';
import * as i18n from '../../i18n';
import injectTapEventPlugin from 'react-tap-event-plugin';
injectTapEventPlugin();

//Components
import App from './App';
import Dashboard from '../components/Dashboard/Dashboard';
import AccessManage from '../components/AccessManage/AccessManage';
import Users from '../components/AccessManage/User/Users';
import CreateUser from '../components/AccessManage/User/CreateUser';
import EditUser from '../components/AccessManage/User/EditUser';
import ChangePassword from '../components/AccessManage/User/ChangePassword';
import Roles from '../components/AccessManage/Role/Roles';
import CreateRoles from '../components/AccessManage/Role/CreateRoles';
import EditRoles from '../components/AccessManage/Role/EditRoles';
import Permissions from '../components/AccessManage/Permission/Permissions';
import PinCodeManage from '../components/PinCodeManage/PinCodeManage';
import Pins from '../components/PinCodeManage/Pins';
import GeneratePin from '../components/PinCodeManage/GeneratePin';
import FlightManage from '../components/FlightManage/FlightManage';
import FlightsList from '../components/FlightManage/FlightsList';
import EditTimetable from '../components/FlightManage/EditTimetable';
import EditPlan from '../components/FlightManage/EditPlan';

export default class Root extends Component {
  render() {
    const { locale } = this.props;
    return (
      <div>
        <IntlProvider key="intl" locale={locale} messages={i18n[locale]}>
          <Router history={browserHistory}>
            <Redirect from="/admin/single" to="/admin/single/dashboard"/>
            <Route path={_ADMIN_DOMAIN_NAME} component={App}>
              <Redirect from="" to="dashboard"/>
              <Route path="dashboard" component={Dashboard}/>
              <Redirect from="access" to="access/users"/>
              <Route path="access" component={AccessManage}>
                <Route path="users" component={Users}/>
                <Route path="user/create" component={CreateUser}/>
                <Route path="user/:id/edit" component={EditUser}/>
                <Route path="user/:id/password/change" component={ChangePassword}/>
                <Route path="roles" component={Roles}/>
                <Route path="roles/create" component={CreateRoles}/>
                <Route path="roles/:id/edit" component={EditRoles}/>
                <Route path="permissions" component={Permissions}/>
              </Route>
              <Redirect from="pins" to="pins/list"/>
              <Route path="pins" component={PinCodeManage}>
                <Route path="list" component={Pins}/>
                <Route path="generate" component={GeneratePin}/>
              </Route>
              <Route path="flight" component={FlightManage}>
                <Route path="plans" component={FlightsList}/>
                <Route path="types" component={FlightsList}/>
                <Route path="places" component={FlightsList}/>
                <Route path="timetable/:id" component={EditTimetable}/>
                <Route path="plan/:id/edit" component={EditPlan}/>
              </Route>
            </Route>
          </Router>
        </IntlProvider>
        {/*<DevTools/>*/}
      </div>
    );
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

export default connect(mapStateToProps)(Root);
