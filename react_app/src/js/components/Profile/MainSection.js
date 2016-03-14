import React, { PropTypes, Component } from 'react';
import { browserHistory } from 'react-router';
//components
import LoginInfo from './LoginInfo';
import UserProf from './UserProf';

class MainSection extends Component {
  handleClick() {
    const { destroy } = this.props.actions;
    destroy();
  }

  render() {
    const { user, address, actions: {
      UpdateUserProf, fetchAddress, postChangePassword}
    } = this.props;

    return (
      <div className="content-boody">
        <div className="row">
          <h4>ログイン情報</h4>
          <div className="wrap-white">
            <LoginInfo user={user} postChangePassword={postChangePassword}/>
          </div>
        </div>
        {user.name && !user.isFetching &&
        <div className="row">
          <h4>プロフィール</h4>
          {user.didInvalidate && <p>ユーザー情報の更新に失敗しました</p>}
          <div className="wrap-white">
            <UserProf
              user={user}
              address={address}
              UpdateUserProf={UpdateUserProf}
              fetchAddress={fetchAddress}/>
          </div>
        </div>}
        <div>
          <button type="button" className="btn btn-default" onClick={this.handleClick.bind(this)}>
            <p id="signout">退会する</p>
          </button>
        </div>
      </div>
    );
  }
}

MainSection.propTypes = {
  user: PropTypes.object.isRequired,
  address: PropTypes.object.isRequired,
  actions: PropTypes.object.isRequired
};

export default MainSection;
