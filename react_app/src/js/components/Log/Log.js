import React, { PropTypes, Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import * as LogActions from '../../actions/log';
//components
import Header from './Header';
import MainSection from './MainSection';

class Log extends Component {
  render() {
    const { logs, isFetching, didInvalidate, actions } = this.props;
    return (
      <div>
        <Header/>
        <MainSection
          logs={logs}
          isFetching={isFetching}
          didInvalidate={didInvalidate}
          actions={actions}/>
      </div>
    );
  }
}

Log.propTypes = {
  logs: PropTypes.array,
  isFetching: PropTypes.bool.isRequired,
  didInvalidate: PropTypes.bool.isRequired,
  actions: PropTypes.object.isRequired
};

function mapStateToProps(state) {
  const { logs } = state;
  return {
    logs: logs.data,
    isFetching: logs.isFetching,
    didInvalidate: logs.didInvalidate
  };
}

function mapDispatchToProps(dispatch) {
  return {
    actions: bindActionCreators(LogActions, dispatch)
  };
}

export default connect( mapStateToProps, mapDispatchToProps)(Log);
