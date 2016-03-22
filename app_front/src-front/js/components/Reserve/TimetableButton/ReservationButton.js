import React, { PropTypes, Component } from 'react';
import { connect } from 'react-redux';
import moment from 'moment';

class ReservationButton extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { userId, time, confirmReservationResult, confirmReservation } = this.props;
    const flightAt = moment(time.flightAt, "YYYY-MM-DD HH:mm:ss");
    let className = 'rsv';

    if (flightAt.diff(moment()) <= 0) {
      className = `${className} past`;
    }

    if (typeof time.id === 'undefined') {
      className = className + ' closed';
    } else {

      if (time.deletedAt === null) {
        className = className + ' opened';
      } else {
        className = className + ' closed';
      }

      if (time.users.length >= time.numberOfDrones) {
        console.log(time.users.map(user => user.id).indexOf(userId))
        className = className + ' crowded';
      }

      if (
        userId &&
        time.users.length > 0 &&
        time.users.map(user => user.id).indexOf(userId) >= 0
      ) {
        className = className + ' myReservation';
      };

    }

    return (
      <button
        className={className}
        onClick={() => {
          confirmReservation(time.id)
        }}
      >
        <p>{flightAt.toLocaleTimeString().slice(0, -3)}</p>
      </button>
    );
  }
}

ReservationButton.propTypes = {
  time: PropTypes.object.isRequired,
  confirmReservation: PropTypes.func.isRequired,
  confirmReservationResult: PropTypes.object,
};

function mapStateToProps(state) {
  return {
    userId: state.user.id || null,
  };
}

export default connect(mapStateToProps)(ReservationButton);
