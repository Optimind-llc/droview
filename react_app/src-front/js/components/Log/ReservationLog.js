import React, { PropTypes, Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import * as LogActions from '../../actions/log';
//components
import { Link } from 'react-router';
import { BootstrapTable, TableHeaderColumn } from 'react-bootstrap-table';

class ReservationLog extends Component {
  constructor(props, context) {
    super(props, context);
    props.actions.fetchReservationLogs();
  }

  render() {
    const { reservationLogs } = this.props;
    return (
      <div>
        <div id="log-menu">
          <ul>
            <li><Link to="/log/ticket">チケットログ</Link></li>
            <li><Link to="/log/reservation">予約ログ</Link></li>
          </ul> 
        </div>
        {typeof reservationLogs.data !== 'undefined' && !reservationLogs.isFetching && !reservationLogs.didInvalidate &&
          <BootstrapTable data={reservationLogs.data} search={true} pagination={true}>
            <TableHeaderColumn
              dataField="flightId"
              dataSort
              isKey
            >
              利用タイプ
            </TableHeaderColumn>
            <TableHeaderColumn
              dataField="action"
              dataSort
            >
              枚数
            </TableHeaderColumn>
            <TableHeaderColumn
              dataField="createdAt"
              dataSort
            >
              利用日
              </TableHeaderColumn>
          </BootstrapTable>
        }
      </div>
    );
  }
}

ReservationLog.propTypes = {
  reservationLogs: PropTypes.object.isRequired,
  actions: PropTypes.object.isRequired
};

function mapStateToProps(state) {
  return {
    reservationLogs: state.logs.reservation
  };
}

function mapDispatchToProps(dispatch) {
  return {
    actions: bindActionCreators(LogActions, dispatch)
  };
}

export default connect( mapStateToProps, mapDispatchToProps)(ReservationLog);
