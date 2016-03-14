/*eslint-disable max-len,quotes*/
export default {
  "reserve.success":          `予約が完了しました`,
  "cancel.success":           `予約をキャンセルしました`,
  "updateUserProf.success":   `プロフィールを更新しました`,
  "changePassword.success":   `パスワードを変更しました`,
  "addTicket.success":        `チケットを追加しました`,

  "reserve.fail.isPast":           `予約が失敗しました 予約可能時刻を過ぎています`,
  "reserve.fail.overLimit":        `予約が失敗しました 予約可能上限に達しています`,
  "reserve.fail.doubleBooking":    `予約が失敗しました 同じ時間にすでに予約をしています`,
  "reserve.fail.notEnoughTickets": `予約が失敗しました 所持チケットが不足しています`,
  "reserve.fail.crowded":          `予約が失敗しました このフライトはすでに満員です`,
  "reserve.fail.unknown":          `予約が失敗しました もう一度やり直してください`,
  "test.fail": `接続テストを実行できませんでした　もう一度やり直してください`,

  "getReservations.fail":     `予約情報の取得に失敗しました`,
  "getLog.fail":              `ログの取得に失敗しました`,
  "getUserInfo.fail":         `ユーザー情報の取得に失敗しました`,
  "updateUserProf.fail":      `プロフィールの更新に失敗しました　もう一度やり直してください`,
  "changePassword.fail":      `パスワードを変更できませんでした　もう一度やり直してください`,
  "addTicket.fail":           `チケットの購入に失敗しました　もう一度やり直してください`,
  "destroy.fail":             `退会できませんでした　もう一度やり直してください`,
}