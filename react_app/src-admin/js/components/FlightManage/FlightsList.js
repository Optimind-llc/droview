import React, { PropTypes, Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
//Actions
import { routeActions } from 'react-router-redux';
import * as TimetableActions from '../../actions/Flight/timetable';
import * as PlanActions from '../../actions/Flight/plan';

// Material-UI-components
import {
  FlatButton, RaisedButton, Checkbox, AutoComplete, MenuItem, Paper,
  GridList, GridTile, Dialog, FloatingActionButton, LinearProgress,
  Card, CardActions, CardHeader, CardMedia, CardTitle, CardText
} from 'material-ui';
import Colors from 'material-ui/lib/styles/colors';

import ContentAdd from 'material-ui/lib/svg-icons/content/add';
import FontIcon from 'material-ui/lib/font-icon';
//Components
import EditTimetable from './EditTimetable';
import CreatePlan from './CreatePlan';


class FlightsList extends Component {
  constructor(props, context) {
    super(props, context);
    props.actions.fetchPlans();
    this.state = {
      open: false,
      title: 'LEDチカチカに場所を追加'
    };
  }

  toTimetable(key) {
    const { actions } = this.props;
    actions.push(`/admin/single/flight/timetable/${key}`);
  }

  toEdit(key) {
    const { actions } = this.props;
    actions.push(`/admin/single/flight/plan/${key}/edit`);
  }

  handleOpen(typeId) {
    this.setState({
      open: true,
      typeId
    });
  }

  handleClose() {
    this.setState({open: false});
  }

  render() {
    const { plans, isFetching, didInvalidate, closedPlaces, actions } = this.props;
    const { fetchTimetable, fetchPlacesbyType, createPlan } = actions;
    const { typeId } = this.state;


    return (
      <Paper className="content-wrap" zDepth={1}>
        {isFetching && <LinearProgress color={'red'} mode="indeterminate"/>}
        {Object.keys(plans).map(key =>
          <div className="plan-type-wrap">
            <div className="type-label">
              <i className="fa fa-thumb-tack"></i>{key}
            </div>
            <div className="plan-type-card-wrap">
              {plans[key].map(plan =>
                <Card className={`flightCard ${plan.active === 0 ? 'deactive' : ''}`}>
                  <CardHeader
                    title={plan.place.name}
                    textStyle={{textAlign: 'left'}}
                    style={{height: 40, padding: 10, fontSize: '2rem'}}
                  />
                  <CardMedia
                    overlay={
                      <CardTitle
                        title={
                          <p className="card-title">
                            <span>講座</span><span>{`${plan.reserved}/${plan.open}`}</span>
                            <span>人数</span><span>{`${plan.reserved}/${plan.open}`}</span>
                          </p>
                        }
                        style={{padding: 0, margin: 0}}/>
                    }
                    overlayContainerStyle={{padding: 0, margin: 0}}
                    overlayContentStyle={{padding: 0, margin: 0}}
                  >
                    <img src={plan.place.path} />
                  </CardMedia>
                  <CardText>{plan.description}</CardText>
                  <CardActions>
                    <FlatButton label="予約" style={{opacity:1}} onClick={this.toTimetable.bind(this, plan.id)}/>
                    <FlatButton label="設定" onClick={this.toEdit.bind(this, plan.id)}/>
                  </CardActions>
                </Card>
              )}
                <Card className="flightCard-add" style={{backgroundColor: Colors.grey200}}>
                  <FloatingActionButton
                    backgroundColor={Colors.indigo100}
                    style={{marginTop: 135}}
                    onTouchTap={this.handleOpen.bind(this, plans[key][0].type.id)}>
                    <ContentAdd />
                  </FloatingActionButton>
                </Card>
            </div>
          </div>
        )}
        {this.state.open &&
        <CreatePlan
          typeId={typeId}
          closedPlaces={closedPlaces}
          handleClose={this.handleClose.bind(this)}
          fetchPlacesbyType={fetchPlacesbyType}
          createPlan={createPlan}/>}
      </Paper>
    );
  }
}

FlightsList.propTypes = {
  plans: PropTypes.object.isRequired,
  isFetching: PropTypes.bool.isRequired,
  didInvalidate: PropTypes.bool.isRequired,
  closedPlaces: PropTypes.object,
  actions: PropTypes.object.isRequired
};

function mapStateToProps(state) {
  const { plans, isFetching, didInvalidate } = state.plans;
  return {
    plans: plans || {},
    isFetching: isFetching || false,
    didInvalidate: didInvalidate || false,
    closedPlaces: state.disposable.closedPlaces
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign(routeActions, TimetableActions, PlanActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(FlightsList);
