import React, { PropTypes, Component } from 'react';
// Components
import RaisedButton from 'material-ui/lib/raised-button';
import Colors from 'material-ui/lib/styles/colors';

class ReservationButton extends Component {
  constructor(props) {
    super(props);
  }

  handleClick() {
    const { plan, timestamp, fetchingNodes, time, openFlight, closeFlight } = this.props;
    const { id, users, period } = time;

    if (fetchingNodes.indexOf(time.period) >= 0) {
      return false;
    };

    if (typeof id === 'undefined') {
      openFlight(plan, timestamp, period);
    }
    if (typeof id !== 'undefined' && users.length === 0) {
      closeFlight(plan, timestamp, period, id);
    }
  }

  render() {
    const { time, fetchingNodes } = this.props;

    const getStatus = function() {
      let status, isFetching, value;
      if (typeof time.id === 'undefined') {
        status = 'rsv-close';
        value = '未開講';
      }
      else {
        if (time.users.length > 0) {
          status = 'rsv-reserved';
          value = '予約あり';
        }
        else {
          status = 'rsv-open';
          value = '開講中';     
        }
      }

      if (fetchingNodes.indexOf(time.period) >= 0) {
        isFetching = "isFetching"
      };

      return {
        class: `rsv ${status} ${isFetching}`,
        value: value
      }
    }

    return (
      <button
        className={getStatus().class}
        onClick={this.handleClick.bind(this)}
        /*onMouseOver={this.handleClick.bind(this)}*/>
        <p>{getStatus().value}</p>
      </button>
    );
  }
}

ReservationButton.propTypes = {
  plan: PropTypes.string.isRequired,
  timestamp: PropTypes.number.isRequired,
  time: PropTypes.object.isRequired,
  fetchingNodes: PropTypes.array.isRequired,
  openFlight: PropTypes.func.isRequired,
  closeFlight: PropTypes.func.isRequired,
};

export default ReservationButton;
