import React, { PropTypes, Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import * as TimetableActions from '../../actions/timetable';
import * as ReservationActions from '../../actions/reservation';
//components
import Header from './Header';
import MainSection from './MainSection';
import ConnectionTest from './ConnectionTest';

class Reserve extends Component {
  render() {
    const { selector, data, timetableKey, isFetching, didInvalidate, isOld, actions, modal } = this.props;
    return (
      <div>
        <Header/>
        <MainSection
          selector = {selector}
          data = {data}
          timetableKey = {timetableKey}
          isFetching = {isFetching}
          didInvalidate = {didInvalidate}
          isOld = {isOld}
          modal = {modal}
          actions = {actions} />
        {modal && <ConnectionTest/>}
      </div>
    );
  }
}

Reserve.propTypes = {
  selector: PropTypes.object.isRequired,
  data: PropTypes.object,
  timetableKey: PropTypes.string.isRequired,
  isFetching: PropTypes.bool.isRequired,
  didInvalidate: PropTypes.bool,
  isOld: PropTypes.bool,
  modal: PropTypes.bool.isRequired,
  actions: PropTypes.object.isRequired
};

function getCheckedId(status) {
  for (const i of status) if (i.checked) return i.id;
}

function mapStateToProps(state) {
  const { timetables, selector, modal } = state;
  const { flightTypes, places, week } = selector;
  const f = getCheckedId(flightTypes);
  const p = getCheckedId(places);
  const timetableKey = f + '_' + p + '_' + week;
  const {isFetching, didInvalidate, isOld, lastUpdated, data} = timetables[timetableKey] || {isFetching: true};

  return {
    selector,
    data,
    timetableKey,
    isFetching,
    didInvalidate,
    isOld,
    lastUpdated,
    modal
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign(TimetableActions, ReservationActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Reserve);
