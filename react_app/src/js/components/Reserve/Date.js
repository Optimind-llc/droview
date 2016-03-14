import React, { PropTypes, Component } from 'react';

class Date extends Component {
  render() {
    const { className, dateNodes } = this.props;
    return (
      <li className={ className }><p>{ dateNodes }</p></li>
    );
  }
}

Date.propTypes = {
  className: PropTypes.string.isRequired,
  dateNodes: PropTypes.node.isRequired,
};

export default Date;
