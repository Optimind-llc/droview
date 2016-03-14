import React, { PropTypes, Component } from 'react';
import { Table, Pagination } from 'react-bootstrap';
import { sortLogs } from '../../utils/SortUtils';

class TicketLog extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      activePage: 1,
      items: Math.ceil(props.logs.length / 3),
      orderBy: 'created_at',
      asc: true,
      method: ['webpay', 'pin', 'paypal', 'led', 'vege', 'fish'],
      logs: sortLogs(props.logs, 1, 'created_at', true) || null
    };
  }

  componentDidMount() {
    const { fetchLog } = this.props.actions;
    fetchLog(1);
  }

  componentWillReceiveProps(nextProps) {
    const { logs } = nextProps;
    const { orderBy, asc } = this.state;

    this.state = {
      activePage: 1,
      items: Math.ceil(logs.length / 3),
      orderBy: orderBy,
      asc: asc,
      method: ['webpay', 'pin', 'paypal', 'led', 'vege', 'fish'],
      logs: sortLogs(logs, 1, orderBy, asc) || null
    };
  }

  handlePage(e, selectedEvent) {
    const page = selectedEvent.eventKey;
    const { orderBy, asc, method } = this.state;
    const logs = this.props.logs.filter(l => method.indexOf(l.method) > -1)

    this.setState({
      activePage: page,
      logs: sortLogs(logs, page, orderBy, asc),
      items: Math.ceil(logs.length / 3)
    });
  }

  handleFilter(e) {
    const { checked, value } = e.target;
    const { activePage, orderBy, asc } = this.state;
    let method;

    if(checked) {
      method = this.state.method.slice();
      method.push(value);
    } else {
      method = this.state.method.filter(m => m != value);
    }

    const logs = this.props.logs.filter(l => method.indexOf(l.method) > -1)
    
    this.setState({
      method: method,
      logs: sortLogs(logs, activePage, orderBy, asc),
      items: Math.ceil(logs.length / 3)
    });
  }

  renderLog() {
    const { logs } = this.state;
    return logs.map((l, i) =>
      <tr key={i}>
        {l.amount > 0 && <th>{l.amount}枚購入</th>}
        {l.amount < 0 && <th>{l.amount}枚消費</th>}
        <td>{l.method}</td>
        <td>{l.created_at}</td>
      </tr>
    );      
  }

  render() {
    const { isFetching } = this.props;
    
    return (
      <div>
        <div className="filter" onChange={this.handleFilter.bind(this)}>
          <div className="filter-buy">
            <label className="checkbox-inline">
              <input type="checkbox" name="method" value="webpay" defaultChecked/>クレジットカード
            </label>
            <label className="checkbox-inline">
              <input type="checkbox" name="method" value="paypal" defaultChecked/>PayPal
            </label>
            <label className="checkbox-inline">
              <input type="checkbox" name="method" value="pin" defaultChecked/>PINコード
            </label>
          </div>
          <div className="filter-consumption">
            <label className="checkbox-inline">
              <input type="checkbox" name="method" value="led" defaultChecked/>LEDチカチカ
            </label>
            <label className="checkbox-inline">
              <input type="checkbox" name="method" value="vege" defaultChecked/>植物育成
            </label>
            <label className="checkbox-inline">
              <input type="checkbox" name="method" value="fish" defaultChecked/>魚鑑賞
            </label>
          </div>
        </div>
        <Table className="table table-striped table-hover table-bordered">
          <thead>
            <tr>
              <th>追加枚数</th>
              <th>手段</th>
              <th>使用日</th>
            </tr>
          </thead>
          <tbody>
          {!isFetching && this.renderLog()}
          </tbody>
        </Table>
        <div className="pagination-wrap">
          <Pagination
            first
            last
            ellipsis
            items={this.state.items}
            maxButtons={5}
            activePage={this.state.activePage}
            onSelect={this.handlePage.bind(this)} />
        </div>
      </div>
    );
  }
}

TicketLog.propTypes = {
  logs: PropTypes.array,
  isFetching: PropTypes.bool.isRequired,
  didInvalidate: PropTypes.bool.isRequired,
  actions: PropTypes.object.isRequired
};

export default TicketLog;
