import React, { PropTypes, Component } from 'react';
import { FlatButton, RaisedButton, Dialog, CircularProgress } from 'material-ui';
import { connectionTestOff } from '../../actions/application';

class ReservationModal extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { confirmReservation, testResults, reservation, reserve, clearDisposable } = this.props;
    let open = false;
    let isTesting = true;
    let data;
    let isReserving = false;

    if (
      typeof confirmReservation !== 'undefined' && 
      confirmReservation.didInvalidate === false &&
      confirmReservation.isFetching === false
    ) {
      open = true;
    }

    if (
      open &&
      typeof testResults !== 'undefined' &&
      testResults.isFetching === false
    ) {
      isTesting = false;
    }

    if (open && !isTesting) {
      data = testResults.data;
    };

    if (
      open &&
      !isTesting &&
      typeof reservation !== 'undefined' &&
      reservation.isFetching
    ) {
      isReserving = true;
    }

    const actions = [
      <FlatButton
        label="キャンセル"
        disabled={isTesting || isReserving}
        onTouchTap={() => clearDisposable()}
        onClick={() => clearDisposable()}
      />,
      <FlatButton
        label="予約確定"
        secondary={true}
        disabled={isTesting || isReserving}
        onTouchTap={() => reserve(data)}
        onClick={() => reserve(data)}
      />
    ];

    return (
      <div>
        <Dialog
          title={isTesting ? '接続テストを行っています...' : isReserving ? '予約中です...' : '接続環境：良'}
          actions={actions}
          modal={true}
          open={open}
          bodyStyle={{height: 348}}
        >
          {isTesting &&
            <div className="row connection-status">
              <CircularProgress style={{position: 'absolute', top: 0, bottom: 0, left: 0, right: 0, margin: 'auto'}}/>
            </div>
          }
          {isReserving &&
            <div className="row connection-status">
              <CircularProgress style={{position: 'absolute', top: 0, bottom: 0, left: 0, right: 0, margin: 'auto'}}/>
            </div>
          }
          {!isTesting && !isReserving &&
            <div className="row connection-status">
              <h4>接続テスト結果<span className="help">※必ず接続テストを行った場所でご利用ください</span></h4>
              <div className="col-sm-6">
                <div className="status-body">
                  <table className="table">
                    <tbody>
                    <tr>
                      <td><span className="">IPアドレス</span></td>
                      <td>{ data.ip_address}</td>
                    </tr>
                    <tr>
                      <td><span className="">ダウンロード速度</span></td>
                      <td>{ data.down_load}Kbps</td>
                    </tr>
                    <tr>
                      <td><span className="">アップロード通信速度</span></td>
                      <td>{ data.up_load}kbps</td>
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
                      <td>{ data.browser}</td>
                    </tr>
                    <tr>
                      <td><span className="">接続方式</span></td>
                      <td>{ data.connection_method}</td>
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
          }
        </Dialog>
      </div>
    );
  }
}

ReservationModal.propTypes = {
  confirmReservation: PropTypes.object,
  testResults: PropTypes.object,
  reservation: PropTypes.object,
  reserve: PropTypes.func.isRequired
};

export default ReservationModal;
