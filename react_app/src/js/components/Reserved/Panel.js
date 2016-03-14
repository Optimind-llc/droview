import React, { PropTypes, Component } from 'react';

class Panel extends Component {
  handleClick(e) {
    window.open('/mypage/flight', '', 'width=500,height=400');
    const { getJwtIfNeeded, status } = this.props;
    getJwtIfNeeded(status.pivot.id);
  }

  handleCancel(e) {
    const { cancel, isCanceling, status: {pivot: {id}} } = this.props;
    if (isCanceling.indexOf(id)) {
      cancel({id});
    };
  }

  render() {
    const { status, style } = this.props;

    return (
      <div className="panel panel-default reservation" style={style}>
        <div className="panel-heading">
          <h5 className="panel-title">{ status.flight_at}</h5>
          <button type="button" className="btn btn-info btn-sm" onClick={this.handleClick.bind(this)}>
              フライト画面へ
          </button>
        </div>
        <div className="panel-body">
          <div className="row main-status">
            <div className="col-sm-4">
              <img alt="" src="/img/demo.jpg"/>
            </div>
            <div className="col-sm-8">
              <div className="status-body">
                <table className="table">
                  <tbody>
                  <tr>
                    <td><span className="">タイプ</span></td>
                    <td>{ status.type}</td>
                  </tr>
                  <tr>
                    <td><span className="">飛行場所</span></td>
                    <td>{ status.place}</td>
                  </tr>
                  <tr>
                    <td><span className="">利用時間</span></td>
                    <td>15分</td>
                  </tr>
                  <tr>
                    <td><span className="">機体情報</span></td>
                    <td>試作機 No.3</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div className="row connection-status">
            <h4>接続テスト結果<span className="help">※必ず接続テストを行った場所でご利用ください</span></h4>
            <div className="col-sm-6">
              <div className="status-body">
                <table className="table">
                  <tbody>
                  <tr>
                    <td><span className="">IPアドレス</span></td>
                    <td>{ status.env.ip_address}</td>
                  </tr>
                  <tr>
                    <td><span className="">ダウンロード速度</span></td>
                    <td>{ status.env.down_load}Kbps</td>
                  </tr>
                  <tr>
                    <td><span className="">アップロード通信速度</span></td>
                    <td>{ status.env.up_load}kbps</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div className="col-sm-6">
              <div className="status-body">
                <table className="table">
                  <tbody>
                  <tr>
                    <td><span className="">ブラウザ</span></td>
                    <td>{ status.env.browser}</td>
                  </tr>
                  <tr>
                    <td><span className="">接続方式</span></td>
                    <td>{ status.env.connection_method}</td>
                  </tr>
                  <tr>
                    <td><span className="">遅延時間</span></td>
                    <td>なし</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div className="row cancel">
            <p onClick={this.handleCancel.bind(this)}>キャンセル</p>
          </div>
        </div>
      </div>
    );
  }
}

Panel.propTypes = {
  status: PropTypes.object.isRequired,
  isCanceling: PropTypes.array.isRequired,
  cancel: PropTypes.func.isRequired
};

export default Panel;
