import React, { PropTypes, Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
var moment = require('moment');
//Actions
import { routeActions } from 'react-router-redux';
import * as IniyializeActions from '../../actions/initialize';
import * as PlanActions from '../../actions/plan';
import * as TimetableActions from '../../actions/timetable';
//Components
import { RaisedButton, Paper, LinearProgress } from 'material-ui';
import Colors from 'material-ui/lib/styles/colors';
import FontIcon from 'material-ui/lib/font-icon';
import Timetable from './Timetable';
import ReservationModal from './ReservationModal';

class EditTimetable extends Component {
  constructor(props) {
    super(props);
    const { routeParams, actions } = props;
    const timestamp = Math.floor(new Date().getTime() / 1000);

    actions.fetchPlan(routeParams.id);
    actions.fetchTimetables({
      planId: routeParams.id,
      timestamp,
      range: [0, 6]
    });

    this.state = {
      planId: routeParams.id,
      timestamp,
      take: 7,
      range: [0, 6]
    };
  }

  render() {
    const {
      planId, plan, timetables, isFetching, didInvalidate, fetchingNodes,
      confirmReservation, testResults, reservation, actions
    } = this.props;
    const { timestamp, range, take } = this.state;

    const displayData = Object.keys(timetables)
    .filter(key =>
      timestamp + (86400 * (range[0] - 1)) <= key && key < timestamp + (86400 * range[1])
    )
    .reduce((array, key)=> {
      array.push({
        timestamp: key,
        timetable: timetables[key]
      });
      return array;
    }, []);

    const reachUpperLimit = range[1] > 28;
    const reachLowerLimit = range[0] < -28;

    return (
      <div style={{margin: '20px 35px 0 0'}}>
        {isFetching &&
          <LinearProgress
            style={{position: 'absolute', top:0, left: 0 }}
            color={Colors.indigo500}
            mode="indeterminate"
          />
        }
        {plan &&
          <div
            className="timetable-header"
            style={{backgroundImage: `url(/admin/single/flight/places/${plan.place.id}/picture)`}}
          >
            <h3>
              {plan.type.name}
              <span className="place">{plan.place.name}</span>
              {displayData.length === take &&
              <span className="date">
                {moment.unix(displayData[0].timestamp).format('YYYY年MM月DD日')}〜
                {moment.unix(displayData[6].timestamp).format('YYYY年MM月DD日')}
              </span>
              }
            </h3>
          </div>
        }
        <div className="timetable-body">
          <div className="info">
            <div className="rsv opened reserved example"><p>予約あり</p></div>           
            <div className="rsv opened example"><p>開講中</p></div>           
            <div className="rsv opened past example"><p>終了</p></div>           
            <div className="rsv reserved example"><p>未開講</p></div>
            <div style={{clear: 'both'}}></div>       
          </div>
          <button
            className={`btn-date back ${reachLowerLimit ? 'disabled' : ''}`}
            onMouseDown={() => { if(!reachLowerLimit) {
              actions.fetchTimetables({ planId, timestamp, range: range.map(n => n - take) });
              this.setState({ range: range.map(n => n - take) });
            }}}
          />
          {displayData.length === take &&
            <Timetable
              timetable={displayData}
              confirmReservationResult={confirmReservation}
              confirmReservation={actions.confirmReservation}
            />
          }
          <button
            className={`btn-date next ${reachUpperLimit ? 'disabled' : ''}`}
            onMouseDown={() => { if(!reachUpperLimit) {
              actions.fetchTimetables({ planId, timestamp, range: range.map(n => n + take) });
              this.setState({ range: range.map(n => n + take) });
            }}}
          />
        </div>
        <ReservationModal
          confirmReservation={confirmReservation}
          testResults={testResults}
          reservation={reservation}
          reserve={actions.reserve}
          clearDisposable={actions.clearDisposable}
        />
      </div>
    );
  }
}

EditTimetable.propTypes = {
  planId: PropTypes.number.isRequired,
  plan: PropTypes.object,
  timetables: PropTypes.object,
  isFetching: PropTypes.bool.isRequired,
  didInvalidate: PropTypes.bool.isRequired,
  fetchingNodes: PropTypes.object.isRequired,
  confirmReservation: PropTypes.object,
  testResults: PropTypes.object,
  reservation: PropTypes.object,
  actions: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  const { plans, timetables, fetchingNodes, disposable: {
    confirmReservation, testResults, reservation
  }} = state;
  const { id } = ownProps.params;

  return {
    planId: Number(id),
    plan: plans.plans.filter(p => p.id == id)[0],
    timetables: timetables[id] ? timetables[id].timetables : {},
    isFetching: timetables[id] ? timetables[id].isFetching : false,
    didInvalidate: timetables[id] ? timetables[id].didInvalidate : false,
    fetchingNodes: fetchingNodes[id] ? fetchingNodes[id] : {},
    confirmReservation: confirmReservation,
    testResults: testResults,
    reservation: reservation,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign(routeActions, IniyializeActions, PlanActions, TimetableActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(EditTimetable);
