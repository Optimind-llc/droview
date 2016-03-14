import React, { PropTypes, Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import * as ReservationActions from '../../actions/reservation';
//components
import Header from './Header';
import MainSection from './MainSection';

class Reserved extends Component {
  constructor(props, context) {
    super(props, context);
    props.actions.fetchReservations();
  }

  render() {
    const { reservation, actions: {cancel, getJwtIfNeeded} } = this.props;
    return (
      <div>
        <Header/>
        <MainSection
          reservation = {reservation}
          getJwtIfNeeded={getJwtIfNeeded}
          cancel = {cancel} />
      </div>
    );
  }
}

Reserved.propTypes = {
  reservation: PropTypes.object.isRequired,
  actions: PropTypes.object.isRequired
};

function mapStateToProps(state) {
  const { reservation } = state;
  return {
    reservation
  };
}

function mapDispatchToProps(dispatch) {
  return {
    actions: bindActionCreators(ReservationActions, dispatch)
  };
}

export default connect( mapStateToProps, mapDispatchToProps)(Reserved);
