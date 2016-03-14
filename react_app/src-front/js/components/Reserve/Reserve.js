import React, { PropTypes, Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
//Actions
import { routeActions } from 'react-router-redux';
import * as TimetableActions from '../../actions/timetable';
import * as PlanActions from '../../actions/plan';
// Material-UI-components
import {
  Paper, FlatButton, FloatingActionButton, LinearProgress,
  Card, CardActions, CardHeader, CardMedia, CardTitle, CardText
} from 'material-ui';
import Colors from 'material-ui/lib/styles/colors';
//Icons
import ContentAdd from 'material-ui/lib/svg-icons/content/add';
import Flag from 'material-ui/lib/svg-icons/content/flag';
//Components
import Header from './Header';
import EditTimetable from './EditTimetable';

class Plans extends Component {
  constructor(props, context) {
    super(props, context);
    props.actions.fetchPlans();
  }

  render() {
    const { plans, isFetching, closedPlaces, actions } = this.props;
    const { fetchPlacesbyType, createPlan } = actions;
    const converted = plans.reduce((pre, plan) => {
      const typeId = plan.type.id;
      pre[typeId] ?
      pre[typeId].push(plan) :
      pre[typeId] = [plan];
      return pre;
    }, {})

    return (
      <div>
        <Header/>
        {isFetching &&
          <LinearProgress
            style={{position: 'absolute', top:0, left: 0 }}
            color={Colors.indigo500}
            mode="indeterminate"
          />
        }
        {Object.keys(converted).sort().map(key =>
          <div className="plan-type-wrap" key={key}>
            <div className="type-label">
              <p><Flag/><span>{converted[key][0].type.name}</span></p>
            </div>
            <div className="plan-type-card-wrap">
              {converted[key].map(plan =>
                <Card className={`flightCard ${plan.active === 0 ? 'deactive' : ''}`} key={plan.id}>
                  <CardHeader
                    title={plan.place.name}
                    textStyle={{textAlign: 'left'}}
                    style={{height: 40, padding: 10, fontSize: '2rem'}}
                  />
                  <CardMedia
                    style={{height: 124}}
                    overlayContainerStyle={{padding: 0, margin: 0}}
                    overlayContentStyle={{padding: 0, margin: 0}}
                  >
                    <img
                      src={`/admin/single/flight/places/${plan.place.id}/picture`}
                      style={{height: 124}}
                    />
                  </CardMedia>
                  <CardText style={{wordWrap: 'break-word', height: 95, overflow: 'hidden'}}>{plan.description}</CardText>
                  <CardActions>
                    <FlatButton
                      label="予約"
                      style={{marginLeft: 50}}
                      onClick={() => actions.push(`/timetable/${plan.id}`)}
                    />
                  </CardActions>
                </Card>
              )}
            </div>
          </div>
        )}
      </div>
    );
  }
}

Plans.propTypes = {
  plans: PropTypes.array.isRequired,
  isFetching: PropTypes.bool.isRequired,
  didInvalidate: PropTypes.bool.isRequired,
  updatedAt: PropTypes.number.isRequired,
  closedPlaces: PropTypes.object,
  actions: PropTypes.object.isRequired
};

function mapStateToProps(state) {
  const { plans, types, isFetching, didInvalidate, updatedAt } = state.plans;
  return {
    plans,
    isFetching,
    didInvalidate,
    updatedAt,
    closedPlaces: state.disposable.closedPlaces
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign(routeActions, TimetableActions, PlanActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Plans);
