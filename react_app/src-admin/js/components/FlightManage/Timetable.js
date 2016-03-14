import React, { PropTypes, Component } from 'react';
import { Button } from 'react-bootstrap';
import Icon from 'react-fa';
//components
import DateButton from './TimetableButton/DateButton';
import ReservationButton from './TimetableButton/ReservationButton';

import RaisedButton from 'material-ui/lib/raised-button';
import Colors from 'material-ui/lib/styles/colors';

class Timetable extends Component {
  render() {
    const { plan, timetable, fetchingNodes, openFlight, closeFlight } = this.props;
    const S = {  
      columns: {
        marginLeft: 5,
        marginRight: 5,
        flexBasis: 80,
        flexGrow: 1,
      }
    };

    return (
      <div>
        <div className="date-wrap">
          {timetable.map(day =>
            <div style={S.columns} key={day.timestamp}>
              <DateButton timestamp={Number(day.timestamp)}/>
            </div>
          )}
        </div>
        <div className="timetable-wrap">
          {timetable.map(day =>
            <div className="timetable-columns" style={S.columns} key={day.timestamp}>
                {day.timetable.map(time =>
                  <ReservationButton
                    key={time.period}
                    plan={plan}
                    timestamp={Number(day.timestamp)}
                    time={time}
                    fetchingNodes={
                      typeof fetchingNodes[day.timestamp] !== 'undefined' ?
                      fetchingNodes[day.timestamp] : []
                    }
                    openFlight={openFlight}
                    closeFlight={closeFlight}
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
  plan: PropTypes.string.isRequired,
  timetable: PropTypes.array.isRequired,
  fetchingNodes: PropTypes.object.isRequired,
  openFlight: PropTypes.func.isRequired,
  closeFlight: PropTypes.func.isRequired,
};

export default Timetable;
