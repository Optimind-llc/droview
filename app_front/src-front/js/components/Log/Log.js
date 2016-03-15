import React, { PropTypes, Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
//components
import Header from './Header';

class Log extends Component {
  render() {
    return (
      <div>
        <Header/>
        {this.props.children}
      </div>
    );
  }
}

Log.propTypes = {
  children: PropTypes.array.isRequired,
};

function mapStateToProps(state) {
  return {
    logs: state.logs
  };
}

export default connect( mapStateToProps)(Log);
