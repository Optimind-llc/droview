import React, { PropTypes, Component } from 'react';
import ReactDOM from 'react-dom';
import { WEBPAY_PUBLIC_KEY } from '../../../config/env';

class CreditCard extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      num: 1
    };
  }

  componentDidMount() {
    const webpayForm = ReactDOM.findDOMNode(this.refs.webpayForm);
    const script = document.createElement('script');
    script.setAttribute('src', 'https://checkout.webpay.jp/v2/');
    script.setAttribute('data-key', WEBPAY_PUBLIC_KEY);
    script.setAttribute('data-lang', 'ja');
    script.setAttribute('data-token-name', 'webpayToken');
    script.setAttribute('data-partial', 'true');
    script.setAttribute('class', 'webpay-button');
    script.setAttribute('id', 'webpayButton');
    webpayForm.appendChild(script);
  }

  componentWillUnmount() {
    const webpayForm = ReactDOM.findDOMNode(this.refs.webpayForm);
    webpayForm.removeChild(document.getElementById('webpayButton'));
    //for preventing webpay error
    window.WebPay = void(0);
    window.WebPayCheckoutHelper = void(0);
  }

  handleChange(e) {
    let num;
    if (e.target.value > 10) num = 10;
    else if (e.target.value < 1) num = 1;
    else num = e.target.value;
    this.setState({ num: num });
  }

  handleClick(e) {
    let num;
    if (e.target.className === 'btn-add') {
      num = this.state.num + 1;
    } else if (e.target.className === 'btn-sub') {
      num = this.state.num - 1;
    }

    if (num > 10) num = 10;
    else if (num < 1) num = 1;

    this.setState({ num: num });
  }

  handleSubmit(e) {
    e.preventDefault();
    const request = {
      'num': e.target.children['1'].children['1'].children['0'].value,
      'amount': e.target.children['2'].children['1'].value,
      'webpay-token': e.target.children['3'].children['1'].children['1'].value
    };
    this.props.fetchWebpay(request);
  }

  render() {
    const payment = this.state.num * 1000;
    return (
      <div className="panel-body">
        <div className="buy-head">
          <p>クレジットカードを利用してチケットを購入します</p>
          <div className="card-list">
            <div className="card-icon visa"></div>
            <div className="card-icon master"></div>
            <div className="card-icon jcb"></div>
            <div className="card-icon amex"></div>
            <div className="card-icon diners"></div>
          </div>
        </div>

        <form className="form-card" onSubmit={this.handleSubmit.bind(this)}>
          <input type="text" className="hidden" />
          <div className="form-group ticket">
            <label><i className="i2 fa fa-ticket"></i>　チケット枚数</label>
            <div className="input-area">
              <input type="number" min="1" max="10" name="ticket" value={this.state.num} onChange={this.handleChange.bind(this)} />
              <input type="button" className="btn-add" onClick={this.handleClick.bind(this)} />
              <input type="button" className="btn-sub" onClick={this.handleClick.bind(this)} />
            </div>
            <label>枚</label>
          </div>
          <div className="payment">
            <label><i className="i2 fa fa-jpy"></i>　支払額</label>
            <output name="payment">{payment}</output>
            <label>円</label>
          </div>
          <div className="webpayForm" ref="webpayForm"></div>
          <div className="pay">
            <input type="submit" value="チケットを購入" className="btn btn-danger" id="payBywebpay"/>
          </div>
        </form>
      </div>
    );
  }
}

CreditCard.propTypes = {
  fetchWebpay: PropTypes.func.isRequired
};

export default CreditCard;
