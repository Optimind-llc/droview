import React, { PropTypes, Component } from 'react';

class SelectDate extends Component {
  handleClicked(e) {
    const { isFetching, fetchTimetableIfNeeded } = this.props;
    const { className } = e.target;

    if (!isFetching) {
      if (className === 'btn-buck') fetchTimetableIfNeeded(null, null, -1);
      if (className === 'btn-next') fetchTimetableIfNeeded(null, null, 1);
    }
  }

  render() {
    return (
      <div className="page-nation clearfix" onClick={this.handleClicked.bind(this)}>
        <div className="btn-buck"></div>
        <div className="btn-next"></div>
      </div>
    );
  }
}

SelectDate.propTypes = {
  isFetching: PropTypes.bool.isRequired,
  fetchTimetableIfNeeded: PropTypes.func.isRequired
};

export default SelectDate;
