import React, { PropTypes, Component } from 'react';
// Components
import RaisedButton from 'material-ui/lib/raised-button';
import Colors from 'material-ui/lib/styles/colors';

class DateButton extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { timestamp } = this.props;
    const diff = new Date().getTime() / 1000 - timestamp;

    const dt = new Date(timestamp * 1000);
    const m = dt.getMonth() + 1;
    const d = dt.getDate();
    const w = dt.getDay();
    const weekdays = [ '日', '月', '火', '水', '木', '金', '土' ];

    const getClass = function() {
      let wk = 'weekday';
      if (w === 6) {
        wk = 'saturday';
      }
      else if (w === 0) {
        wk = 'sunday';
      }
      else {
        wk = 'weekday';
      }

      let a = '';
      if ( 0 <= diff && diff < 86400) a = 'active';

      return `date ${wk} ${a}`;
    }

    return (
      <div className={getClass()}>
        <p>{`${m}月${d}日(${weekdays[w]})`}</p>
      </div>
    );
  }
}

DateButton.propTypes = {
  timestamp: PropTypes.number.isRequired,
};

export default DateButton;
