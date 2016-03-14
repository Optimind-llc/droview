import React, { PropTypes, Component } from 'react';
import { getLocal, delLocal } from '../../utils/WebStrageUtils';
//components
import SelectBox from './SelectBox';
import SelectDate from './SelectDate';
import TimetableBox from './TimetableBox';

class MainSection extends Component {
  componentDidMount() {
    const { fetchDefaultStatus } = this.props.actions;
    fetchDefaultStatus();
  }

  componentWillReceiveProps(nextProps) {
    const { modal } = nextProps;
    const { timetableKey, actions: {modalOff, reserve} } = this.props;

    if (modal) {
      const loadResult = setInterval(function() {
        const token = getLocal('testConnectionResult');
        if (token) {
          modalOff();
          reserve({ token }, timetableKey);
          delLocal('testConnectionResult');
          return clearInterval(loadResult);
        }
      }, [3000, 500]);
    }
  }

  render() {
    const { selector, data, isFetching, didInvalidate, isOld, actions: {fetchTestToken, fetchTimetableIfNeeded} } = this.props;
    return (
      <div>
        <SelectBox
          selector = {selector}
          isFetching = {isFetching}
          fetchTimetableIfNeeded = {fetchTimetableIfNeeded}/>
        <div className="timetable-box">
          <SelectDate
            isFetching = {isFetching}
            fetchTimetableIfNeeded = {fetchTimetableIfNeeded}/>
          <TimetableBox
            isFetching = {isFetching}
            didInvalidate = {didInvalidate}
            isOld = {isOld}
            data = {data}
            fetchTimetableIfNeeded = {fetchTimetableIfNeeded}
            fetchTestToken = {fetchTestToken}/>
        </div>
      </div>
    );
  }
}

MainSection.propTypes = {
  selector: PropTypes.object.isRequired,
  data: PropTypes.object,
  isFetching: PropTypes.bool.isRequired,
  didInvalidate: PropTypes.bool,
  isOld: PropTypes.bool,
  timetableKey: PropTypes.string.isRequired,
  modal: PropTypes.bool.isRequired,
  actions: PropTypes.object.isRequired
};

export default MainSection;
