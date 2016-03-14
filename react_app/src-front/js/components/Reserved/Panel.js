import React, { PropTypes, Component } from 'react';
import { FlatButton, CircularProgress } from 'material-ui';

class Panel extends Component {
  handleClick(e) {
    window.open('/mypage/flight', '', 'width=500,height=400');
    const { getJwtIfNeeded, status } = this.props;
    getJwtIfNeeded(status.pivot.id);
  }

  render() {
    const { status, style, isCanceling, cancel } = this.props;
    return (
      <div className="panel panel-default reservation" style={style}>
        {isCanceling.indexOf(status.pivot.id) !== -1 &&
          <div style={{
            position: 'absolute', width: '100%', height: '100%', zIndex: 1000, backgroundColor: 'rgba(255,255,255, 0.6)'
          }}>
            <CircularProgress style={{position: 'absolute', top: 0, bottom: 0, left: 0, right: 0, margin: 'auto'}}/>
          </div>
        }
        <div className="panel-heading">
          <h5 className="panel-title">{ status.flightAt}</h5>
          <button type="button" className="btn btn-info btn-sm" onClick={this.handleClick.bind(this)}>
              フライト画面へ
          </button>
        </div>
        <div className="panel-body">
          <div className="row main-status">
            <div className="col-sm-4">
              <img src={`/admin/single/flight/places/${status.plan.place.id}/picture`}/>
            </div>
            <div className="col-sm-8">
              <div className="status-body">
                <table className="table">
                  <tbody>
                  <tr>
                    <td><span className="">タイプ</span></td>
                    <td>{ status.plan.type.name}</td>
                  </tr>
                  <tr>
                    <td><span className="">飛行場所</span></td>
                    <td>{ status.plan.place.name}</td>
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
                    <td>{ status.pivot.ipAddress}</td>
                  </tr>
                  <tr>
                    <td><span className="">ダウンロード速度</span></td>
                    <td>{ status.pivot.downLoad}Kbps</td>
                  </tr>
                  <tr>
                    <td><span className="">アップロード通信速度</span></td>
                    <td>{ status.pivot.upLoad}kbps</td>
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
                    <td>{ status.pivot.browser}</td>
                  </tr>
                  <tr>
                    <td><span className="">接続方式</span></td>
                    <td>{ status.pivot.connectionMethod}</td>
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
            <FlatButton
              style={{float: 'right', color: 'red', marginRight: 20}}
              rippleColor="red"
              label="キャンセル"
              secondary={true}
              disabled={isCanceling.indexOf(status.pivot.id) !== -1}
              onTouchTap={() => cancel(status.id, status.pivot.id)}
              onClick={() => cancel(status.id, status.pivot.id)}
            />
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
