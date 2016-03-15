import React, { PropTypes, Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import * as UserinfoActions from '../../actions/userinfo';

class NavHeader extends Component {
  componentDidMount() {
    this.props.actions.fetchUserInfo();
  }

  render() {
    const { reservations, remainingTickets } = this.props.user.status;
    return (
      <div className="side-bar-head">
        <p className="name">{ this.props.user.name }</p>
        <table className="table">
          <tbody>
            <tr>
              <td>予約中</td>
              <td><span id="reservations">{ reservations }</span></td>
              <td>件</td>
            </tr>
            <tr>
              <td>チケット</td>
              <td><span id="remainingTickets">{ remainingTickets }</span></td>
              <td>枚</td>
            </tr>
          </tbody>
        </table>
      </div>
    );
  }
}

NavHeader.propTypes = {
  user: PropTypes.object.isRequired,
  actions: PropTypes.object.isRequired,
};

function mapStateToProps(state) {
  return {
    user: state.user
  };
}

function mapDispatchToProps(dispatch) {
  return {
    actions: bindActionCreators(UserinfoActions, dispatch)
  };
}

export default connect( mapStateToProps, mapDispatchToProps)(NavHeader);
