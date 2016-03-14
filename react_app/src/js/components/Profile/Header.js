import React, { Component } from 'react';

class Header extends Component {
  render() {
    return (
      <div className="content-head">
        <h3>ドローンの予約</h3>
        <p>ドローンを予約するのにはチケットが必要です
          チケットは１枚1,000円でご購入いただけます
          遠隔ドローンではクレジットカードとPayPalでのお支払いが可能です
          チケットの反映はお支払い完了後、即時に行います。
        </p>
      </div>
    );
  }
}

export default Header;
