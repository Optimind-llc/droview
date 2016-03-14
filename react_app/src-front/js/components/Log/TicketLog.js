import React, { PropTypes, Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import * as LogActions from '../../actions/log';
//components
import { Link } from 'react-router';
import { BootstrapTable, TableHeaderColumn } from 'react-bootstrap-table';
import Header from './Header';

class TicketLog extends Component {
  constructor(props, context) {
    super(props, context);
    props.actions.fetchTicketLogs();
  }

  render() {
    const { ticketLogs } = this.props;
    return (
      <div>
        <div id="log-menu">
          <ul>
            <li><Link to="/log/ticket">チケットログ</Link></li>
            <li><Link to="/log/reservation">予約ログ</Link></li>
          </ul> 
        </div>
        {ticketLogs.data && !ticketLogs.isFetching && !ticketLogs.didInvalidate &&
          <BootstrapTable data={ticketLogs.data} search={true} pagination={true}>
            <TableHeaderColumn
              dataField="method"
              dataSort
              isKey
            >
              利用タイプ
            </TableHeaderColumn>
            <TableHeaderColumn
              dataField="amount"
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

TicketLog.propTypes = {
  ticketLogs: PropTypes.object.isRequired,
  actions: PropTypes.object.isRequired
};

function mapStateToProps(state) {
  console.log(state.logs)
  return {
    ticketLogs: state.logs.ticket
  };
}

function mapDispatchToProps(dispatch) {
  return {
    actions: bindActionCreators(LogActions, dispatch)
  };
}

export default connect( mapStateToProps, mapDispatchToProps)(TicketLog);
