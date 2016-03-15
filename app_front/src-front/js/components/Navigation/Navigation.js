import React, { Component } from 'react';
import { Link } from 'react-router';
import NavHeader from './NavHeader';
import Icon from 'react-fa';

class Navigation extends Component {
  render() {
    return (
      <div className="side-bar col-md-2">
        <NavHeader />
        <div className="side-bar-menu">
          <ul className="nav nav-pills nav-stacked">
            <li className="link reserved">
              <Link to="/reserved" activeClassName="active" >
                <Icon name="map-pin" /> 予約リスト
              </Link>
            </li>
            <li className="link reserve">
              <Link to="/reserve" activeClassName="active" >
                <Icon name="calendar" /> フライト予約
              </Link>
            </li>
            <li className="link ticket" id="link-ticket">
              <Link to="/ticket" activeClassName="active" >
                <Icon name="ticket" /> チケット購入
              </Link>
            </li>
            <li className="link log">
              <Link to="/log" activeClassName="active" >
                <Icon name="list" /> イベントログ
              </Link>
            </li>
            <li className="link profile">
              <Link to="/profile" activeClassName="active" >
                <Icon name="user" /> ユーザー設定
              </Link>
            </li>
          </ul>
        </div>
      </div>
    );
  }
}

export default Navigation;
