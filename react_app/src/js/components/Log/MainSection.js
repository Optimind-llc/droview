import React, { PropTypes, Component } from 'react';
import { Tabs, Tab } from 'react-bootstrap';
//components
import TicketLog from './TicketLog';

class MainSection extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      key: 1
    };
  }

  handleSelect(key) {
    this.setState({key});
  }

  render() {
    const { logs, isFetching, didInvalidate, actions } = this.props;
    const { key } = this.state;

    return (
      <Tabs activeKey={key} onSelect={this.handleSelect.bind(this)}>
        <Tab eventKey={1} title="チケットログ">
          <TicketLog 
            logs={logs}
            isFetching={isFetching}
            didInvalidate={didInvalidate}
            actions={actions}/>
        </Tab>
        <Tab eventKey={2} title="coming soon"></Tab>
        <Tab eventKey={3} title="coming soon"></Tab>
      </Tabs>
    );
  }
}

MainSection.propTypes = {
  logs: PropTypes.array,
  isFetching: PropTypes.bool.isRequired,
  didInvalidate: PropTypes.bool.isRequired,
  actions: PropTypes.object.isRequired
};

export default MainSection;
