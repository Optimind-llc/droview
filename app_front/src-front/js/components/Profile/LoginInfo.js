import React, { PropTypes, Component } from 'react';
require('bootstrap-social');

class LoginInfo extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      oldPass: {value: null, err: false},
      pass: {value: null, err: false},
      passConf: {value: null, err: false}
    };
  }

  handleChange(e) {
    const {name, value} = e.target;
    switch (name) {
    case 'oldPass':
      if (/^[a-z\d]{6,20}$/i.test(value)) {
        this.setState({ oldPass: {value: value, err: false }});
      } else {
        this.setState({ oldPass: {value: value, err: '英数字6文字以上で入力してください'}});
      }
      break;
    case 'pass':
      if (/^[a-z\d]{6,20}$/i.test(value)) {
        this.setState({ pass: {value: value, err: false }});
      } else {
        this.setState({ pass: {value: value, err: '英数字6文字以上で入力してください' }});
      }
      break;
    case 'passConf':
      if (value === this.state.pass.value) {
        this.setState({ passConf: {value: value, err: false }});
      } else {
        this.setState({ passConf: {value: value, err: 'パスワードが一致していません' }});
      }
      break;
    default :
    }
  }

  handleSubmit(e) {
    e.preventDefault();
    const request = {
      old_password: e.target.children['1'].children['0'].children['1'].children['0'].value,
      password: e.target.children['1'].children['1'].children['1'].children['0'].value,
      password_confirmation: e.target.children['1'].children['2'].children['1'].children['0'].value
    };

    this.props.postChangePassword(request);
  }

  renderPass() {
    const {oldPass, pass, passConf} = this.state;

    let hasErr;
    if (oldPass.err || pass.err || passConf.err || oldPass.value === null || pass.value === null || passConf.value === null) {
      hasErr = true;
    } else {
      hasErr = false;
    }

    return (
      <div>
        <div className={oldPass.err ? 'form-group has-error' : 'form-group'}>
          <label htmlFor="" className="col-sm-2 control-label">パスワード</label>
          <div className={oldPass.err ? 'col-sm-6' : 'col-sm-8'}>
            <input type="password" name="oldPass" className="form-control" id="" placeholder="現在のパスワード" value={oldPass.value}/>
          </div>
          {oldPass.err && <div className="col-sm-3 help-block">{oldPass.err}</div> }
        </div>
        <div className={pass.err ? 'form-group has-error' : 'form-group'}>
          <label htmlFor="inputPassword3" className="col-sm-2 control-label"> </label>
          <div className={pass.err ? 'col-sm-6' : 'col-sm-8'}>
            <input type="password" name="pass" className="form-control" id="" placeholder="新しいパスワード(確認)" value={pass.value}/>
          </div>
          {pass.err && <div className="col-sm-3 help-block">{pass.err}</div> }
        </div>
        <div className={passConf.err ? 'form-group has-error' : 'form-group'}>
          <label htmlFor="inputPassword3" className="col-sm-2 control-label"> </label>
          <div className={passConf.err ? 'col-sm-6' : 'col-sm-8'}>
            <input type="password" name="passConf" className="form-control" id="" placeholder="新しいパスワード" value={passConf.value}/>
          </div>
          {passConf.err && <div className="col-sm-3 help-block">{passConf.err}</div> }
        </div>
        <div className="form-group">
          <div className="col-sm-8 col-sm-offset-2">
            <button type="submit" className={hasErr ? 'btn btn-danger disabled' : 'btn btn-danger'}>
              パスワードを変更
            </button>
          </div>
        </div>
      </div>
    );
  }

  renderSocial() {
    const socialIcon = this.props.user.auth.map((t, i)=>
      <div className="col-sm-2" key={i}>
        <a className={'btn btn-block btn-social btn-' + t}>
          <span className={'fa-' + t}></span> {t}
        </a>
      </div>
    );

    return (
    <div className="form-group">
      <label htmlFor="inputPassword3" className="col-sm-2 control-label">ソーシャル連携</label>
      {socialIcon}
    </div>
    );
  }

  render() {
    const { email, auth } = this.props.user;

    return (
      <form className="form-horizontal" onChange={this.handleChange.bind(this)} onSubmit={this.handleSubmit.bind(this)}>
        <div className="form-group">
          <label className="col-sm-2 control-label">メールアドレス</label>
          <label className="col-sm-2 control-label">{email}</label>
        </div>
        {auth.length === 0 && this.renderPass()}
        {auth.length > 0 && this.renderSocial()}
      </form>
    );
  }
}

LoginInfo.propTypes = {
  user: PropTypes.object.isRequired,
  postChangePassword: PropTypes.func.isRequired

};

export default LoginInfo;
