import React, { PropTypes, Component } from 'react';
import { TransitionMotion, Motion, spring, presets } from 'react-motion';
//components
import Panel from './Panel';

class MainSection extends Component {
  getDefaultValue() {
    const { reservations } = this.props.reservation;
    return Object.keys(reservations).reduce((result, key) => {
      result.push({
        key,
        data: reservations[key],
        style: { opacity: 1 }
      })
      return result;
    }, []);
  }

  getEndValue() {
    const { reservations } = this.props.reservation;
    return Object.keys(reservations).reduce((result, key) => {
      result.push({
        key,
        data: reservations[key],
        style: { opacity: spring(1, presets.gentle) }
      })
      return result;
    }, []);
  }

  willEnter(key) {
    return { opacity: 1 };
  }

  willLeave(key, styleThatJustLeft) {
    return { opacity: spring(0) };
  }

  render() {
    const { reservation: {reservations, isCanceling}, cancel, getJwtIfNeeded } = this.props;

    return (
      <div>
      {reservations &&
      <TransitionMotion
        defaultStyles={this.getDefaultValue.bind(this)()}
        styles={this.getEndValue.bind(this)()}
        willLeave={this.willLeave.bind(this)}
        willEnter={this.willEnter.bind(this)}>
        {reservations =>
          <div>
          {reservations.map(({ key, data, style }) => {
            return (
              <Panel
                key={key}
                status={data}
                style={style}
                isCanceling={isCanceling}
                cancel={cancel}
                getJwtIfNeeded={getJwtIfNeeded}/>
            );
          })}
          </div>
        }
      </TransitionMotion>}
      </div>
    );
  }
}

MainSection.propTypes = {
  reservation: PropTypes.object,
  getJwtIfNeeded: PropTypes.func.isRequired,
  cancel: PropTypes.func.isRequired
};

export default MainSection;
