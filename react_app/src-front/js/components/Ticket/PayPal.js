import React, { Component } from 'react';
import { PAYPAL_PUBLIC_KEY } from '../../../config/env';

class PayPal extends Component {
  render() {
    return (
		<div className="panel-body">
			<div className="buy-head">
				<p><span className="icon paypal small"></span>を利用してチケットを購入します</p>
			</div>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick" />
				<input type="hidden" name="encrypted" value={PAYPAL_PUBLIC_KEY} />
        <button type="submit" className="btn btn-danger">チケットを購入</button>
			</form>

			<div className="row">
				<div className="col-md-6">
					<h4>Step1</h4>
					<p>購入ボタンをクリックするとPayPalのページへ進みます。PayPalへログインしてください</p>
					<img src="/img/paypal1.jpg" />
				</div>
				<div className="col-md-6">
					<h4>Step2</h4>
					<p>送付先情報と合計金額を確認し、「同意して続行」ボタンをクリックしてお支払いを完了します。</p>
					<img src="/img/paypal2.jpg" />
				</div>
			</div>
		</div>
    );
  }
}

export default PayPal;
