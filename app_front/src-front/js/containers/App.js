import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
//Actions
import * as ApplicationActions from '../actions/application';
import * as InitializeActions from '../actions/initialize';
//Components
import { AppBar } from 'material-ui';
import Navigation from '../components/Navigation/Navigation';
import Alert from '../components/Alert';

class App extends Component {
  render() {
    const { locale, alerts, routing, children, actions: {deleteSideAlerts} } = this.props;

    return (
      <div>
        <header id="fh5co-header" role="banner">
          <nav className="navbar navbar-default" role="navigation">
            <div className="container-fluid">
              <div className="navbar-header"> 
                {/* Mobile Toggle Menu Button */}
                <a href="#" className="js-fh5co-nav-toggle fh5co-nav-toggle" data-toggle="collapse" data-target="#fh5co-navbar" aria-expanded="false" aria-controls="navbar"><i /></a>
                <a className="navbar-brand" href="/droview">Droview</a>
              </div>
              <div id="fh5co-navbar" className="navbar-collapse collapse">
                <ul className="nav navbar-nav navbar-right">
                  <li><a href="/logout"><span>ログアウト <span className="border" /></span></a></li>
                </ul>
              </div>
            </div>
          </nav>
        </header>
        <div className="container">
          <Navigation/>
          <div className="content col-md-10">
            <div style={{marginLeft: 30}}>
            {children}
            </div>
          </div>
          <Alert
            alerts={alerts}
            deleteSideAlerts={deleteSideAlerts}
          />
        </div>
      </div>
    );
  }
}

App.propTypes = {
  locale: PropTypes.string.isRequired,
  alerts: PropTypes.array,
  routing: PropTypes.object.isRequired,
  actions: PropTypes.object.isRequired
};

function mapStateToProps(state) {
  return {
    locale: state.application.locale,
    alerts: state.alert.side,
    routing: state.routing
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign(
    ApplicationActions,
    InitializeActions
  );
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect( mapStateToProps, mapDispatchToProps)(App);

