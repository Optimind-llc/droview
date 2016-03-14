import React, { PropTypes, Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
//Actions
import { routeActions } from 'react-router-redux';
import * as PlanActions from '../../actions/Flight/plan';
//Components
import {
  TextField, FlatButton, Card, CardHeader, CardTitle, CardMedia, CardText, CardActions
} from 'material-ui';
import Colors from 'material-ui/lib/styles/colors';

class EditPlan extends Component {
  constructor(props) {
    super(props);
    const { routeParams, actions } = props;
    actions.fetchPlan(routeParams.id);
    this.state = {};
  }

  componentWillReceiveProps(nextProps) {
    const { plan } = nextProps;
    const reshape = p => {
      p.open = p.flights.length;
      p.reserved = p.flights.reduce((prev, flight) => {
        return prev + Number(flight.users);
      }, 0);
      delete p.flights;
      return p;
    }

    if (plan) {
      this.setState({...reshape(plan)});
    }
  }

  handleChange(e) {
    this.setState({ description: e.target.value })
  }

  handleSubmit() {
    const { id, actions } = this.props;
    const { description } = this.state;
    actions.updatePlan(id, description);
  }

  activate() {
    const { id, plan, actions } = this.props;
    actions.activatePlan(id);
    actions.push('/admin/single/flight/plans');
  }

  deactivate() {
    const { id, plan, actions } = this.props;  
    actions.deactivatePlan(id);
    actions.push('/admin/single/flight/plans');
  }

  delete() {
    const { id, plan, actions } = this.props;  
    actions.deletePlan(id);
    actions.push('/admin/single/flight/plans');
  }

  cancel() {
    this.props.history.goBack();
  }

  render() {
    const { plan, isFetching } = this.props;

    return (
      <div>
        {plan &&
        <Card className="flightCard-edit">
          <CardHeader
            title={plan.type.name}
            subtitle={plan.place.name}
            titleStyle={{textAlign: 'left', fontSize: '2rem'}}
            style={{height: 80, padding: 18}}
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
            overlayContentStyle={{padding: 10}}
          >
            <img src={plan.place.path} />
          </CardMedia>
          <CardText>
            <TextField
              style={{marginLeft: 35, width: 400}}
              hintText="プランの説明を書いてください"
              multiLine={true}
              rows={1}
              rowsMax={5}
              defaultValue={plan.description}
              value={this.state.description}
              onChange={this.handleChange.bind(this)}
            />
          </CardText>
          <CardActions>
          <div className="actions">
            <FlatButton
              label="キャンセル"
              style={{float: 'left'}}
              onTouchTap={this.cancel.bind(this)}
            />
            <FlatButton
              label="更新"
              secondary={true}
              style={{float: 'right'}}
              onTouchTap={this.handleSubmit.bind(this)}
            />
            {plan.active === 1 &&
            <FlatButton
              label="休講"
              style={{float: 'right'}}
              labelStyle={{color: Colors.red700}}
              onTouchTap={this.deactivate.bind(this)}
            />}
            {plan.active === 0 &&
            [<FlatButton
              label="開講"
              style={{float: 'right'}}
              labelStyle={{color: Colors.red700}}
              onTouchTap={this.activate.bind(this)}
            />,
            <FlatButton
              label="削除"
              style={{float: 'right'}}
              labelStyle={{color: Colors.red700}}
              onTouchTap={this.delete.bind(this)}
            />]}
          </div>
          </CardActions>
        </Card>}
      </div>
    )
  }
}

EditPlan.propTypes = {
  actions: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  const { disposable } = state;
  const { id } = ownProps.params;
  return {
    id,
    plan: disposable.plan.plan || null,
    isFetching: disposable.plan.isFetching || false,
    didInvalidate:  disposable.plan.didInvalidate || false,
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign(routeActions, PlanActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(EditPlan);