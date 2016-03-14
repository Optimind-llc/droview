import React, { PropTypes, Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { RaisedButton, Paper, LinearProgress, Colors } from 'material-ui';
import FontIcon from 'material-ui/lib/font-icon';
//Actions
import { routeActions } from 'react-router-redux';
import * as TimetableActions from '../../actions/Flight/timetable';
//Components
import Timetable from './Timetable';
import Loading from '../Common/Loading';

class EditTimetable extends Component {
  constructor(props) {
    super(props);
    const { routeParams, actions } = props;
    const date = new Date();
    const toDay = new Date(`
      ${date.getFullYear()}/
      ${date.getMonth()+1}/
      ${date.getDate()}`
    ).getTime() / 1000;

    actions.fetchTimetables({
      plan: routeParams.id,
      timestamp: toDay,
      range: [0, 6]
    });

    this.state = {
      plan: routeParams.id,
      timestamp: toDay,
      take: 7,
      range: [0, 6]
    };
  }

  changeDate(num) {
    const { routeParams, actions } = this.props;
    const { plan, timestamp, take, range } = this.state;
    const newRange = [range[0] + (take * num), range[1] + (take * num)];

    actions.fetchTimetables({ plan, timestamp, range: newRange });
    this.setState({ range: newRange });
  }

  render() {
    const { timetables, isFetching, didInvalidate, fetchingNodes, actions } = this.props;
    const { plan, timestamp, range, take } = this.state;
    
    const getTimestamps = function() {
      let timestamps = [];
      for (let i = range[0]; i <= range[1]; i++) {
        timestamps.push(timestamp + (86400 * i))
      }
      return timestamps;
    }

    const timestamps = getTimestamps();
    const displayData = Object.keys(timetables)
    .filter(key =>
      timestamps.indexOf(Number(key)) >= 0
    )
    .reduce((array, key)=> {
      return array.concat([{
        timestamp: key,
        timetable: timetables[key]
      }])
    }, []);

    return (
      <Paper className="content-wrap" zDepth={1}>
        {isFetching && <LinearProgress color={'red'} mode="indeterminate"/>}
        <button className="btn-date back" onMouseDown={this.changeDate.bind(this, -1)}/>
        {
          displayData.length === take &&
          <Timetable
            plan={plan}
            timetable={displayData}
            fetchingNodes={fetchingNodes}
            openFlight={actions.openFlight}
            closeFlight={actions.closeFlight}
          />
        }
        {displayData.length < take && <Loading/>}
        <button className="btn-date next" onMouseDown={this.changeDate.bind(this, 1)}/>
      </Paper>
    );
  }
}

EditTimetable.propTypes = {
  timetables: PropTypes.object.isRequired,
  isFetching: PropTypes.bool.isRequired,
  didInvalidate: PropTypes.bool.isRequired,
  fetchingNodes: PropTypes.object.isRequired,
  actions: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  const { timetables, fetchingNodes } = state;
  const { id } = ownProps.params;
  return {
    timetables: timetables[id] ? timetables[id].timetables : {},
    isFetching: timetables[id] ? timetables[id].isFetching : false,
    didInvalidate: timetables[id] ? timetables[id].didInvalidate : false,
    fetchingNodes: fetchingNodes[id] ? fetchingNodes[id] : {}
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign(routeActions, TimetableActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(EditTimetable);
