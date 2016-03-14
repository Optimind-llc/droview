import React, { PropTypes, Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

import { Paper, FlatButton, TextField } from 'material-ui';
import Colors from 'material-ui/lib/styles/colors';

class CreatePlan extends Component {
  constructor(props, context) {
    super(props, context);
    props.fetchPlacesbyType(1, 'closed');
    this.state = {
      selected: null,
      description: null
    };
  }

  handleChange(e) {
    this.setState({ description: e.target.value })
  }

  handleSelect(place) {
    this.setState({ selected: place })
  }

  handleSubmit() {
    const { typeId, handleClose, createPlan } = this.props;
    const { selected: {id}, description} = this.state;
    handleClose();
    createPlan(typeId, id, description);
  }

  render() {
    const { closedPlaces, isFetching } = this.props.closedPlaces;
    const { selected, description } = this.state;

    return (
      <div>
        <Paper zDepth={1} id="modal-create-plan" >
          <p>場所を選択してください</p>
          <div className="select-place">
            {!isFetching && closedPlaces && closedPlaces.map(place =>
              <div onClick={this.handleSelect.bind(this, place)}>
                <img src={place.path}/>
              </div>
            )}
          </div>
          <div className="create-plan-form">
            <img src={selected ? selected.path : ''}/>
            <TextField
              style={{float: 'right'}}
              hintText="プランの説明を書いてください"
              multiLine={true}
              rows={1}
              rowsMax={5}
              value={description}
              onChange={this.handleChange.bind(this)}
            />
          </div>
          <div className="actions">
            <FlatButton
              label="キャンセル"
              secondary={true}
              onTouchTap={this.props.handleClose}
            />
            <FlatButton
              label="作成"
              primary={true}
              keyboardFocused={true}
              onTouchTap={this.handleSubmit.bind(this)}
            />
          </div>
        </Paper>
        <div className="modal-overlay"></div>
      </div>
    );
  }
}

CreatePlan.propTypes = {
  typeId: PropTypes.number.isRequired,
  closedPlaces: PropTypes.func.isRequired,
  handleClose: PropTypes.func.isRequired,
  fetchPlacesbyType: PropTypes.func.isRequired,
  createPlan: PropTypes.func.isRequired,
};

export default CreatePlan;
