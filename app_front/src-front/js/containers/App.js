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
      <div className="container">
        <AppBar title="H works" style={{marginBottom: 40}}/>
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

