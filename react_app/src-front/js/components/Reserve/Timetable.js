import React, { PropTypes, Component } from 'react';
//Components
import DateButton from './TimetableButton/DateButton';
import ReservationButton from './TimetableButton/ReservationButton';

class Timetable extends Component {
  render() {
    const { timetable, confirmReservationResult, confirmReservation } = this.props;
    return (
      <div style={{marginLeft: 40, marginRight: 40}}>
        <div className="date-wrap">
          {timetable.map(day =>
            <DateButton timestamp={Number(day.timestamp)} key={day.timestamp}/>
          )}
        </div>
        <div className="timetable-wrap">
          {timetable.map(day =>
            <div className="timetable-columns" key={day.timestamp}>
              {day.timetable.map(time =>
                <ReservationButton
                  key={time.period}
                  time={time}
                  confirmReservationResult={confirmReservationResult}
                  confirmReservation={confirmReservation}
                />
              )}
            </div>
          )}
        </div>
      </div>
    );
  }
}

Timetable.propTypes = {
  timetable: PropTypes.array.isRequired,
  confirmReservation: PropTypes.func.isRequired,
  confirmReservationResult: PropTypes.object,
};

export default Timetable;
