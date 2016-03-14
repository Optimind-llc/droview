import React, { PropTypes, Component } from 'react';

class PinCode extends Component {
  handleSubmit(e) {
    e.preventDefault();
    const pin = {pin: e.target.children['1'].value};
    this.props.fetchPin(pin);
  }

  render() {
    return (
		<div className="panel-body">
			<div className="buy-head">
				<p>お持ちのシリアルコードを入力してください</p>
			</div>
			<div>
				<form className="form-pin" onSubmit={this.handleSubmit.bind(this)}>
					<button className="btn btn-danger" type="submit">チケットを購入</button>
					<input type="text" name="pin" className=""/>
				</form>
			</div>
		</div>
    );
  }
}

PinCode.propTypes = {
  fetchPin: PropTypes.func.isRequired
};

export default PinCode;
