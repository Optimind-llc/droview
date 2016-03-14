/*eslint-disable max-len,quotes*/
export default {
  "reserve.success":          `予約が完了しました`,
  "cancel.success":           `予約をキャンセルしました`,
  "updateUserProf.success":   `プロフィールを更新しました`,
  "changePassword.success":   `パスワードを変更しました`,
  "addTicket.success":        `チケットを追加しました`,

  "reserve.fail": `予約が失敗しました　{reason, plural,
    =overLimit {予約可能上限に達しています}
    =doubleBooking {同じ時間にすでに予約をしています}
    =noTicket {所持チケットが不足しています}
    =server {もう一度やり直してください}}`,
  "cancel.fail": `予約がキャンセルできませんでした　{reason, plural,
    =overLimit {キャンセル可能時刻を過ぎています}
    =server {もう一度やり直してください}}`,

  "getReservationToken.fail": `予約トークンの取得に失敗しました`,
  "getReservations.fail":     `予約情報の取得に失敗しました`,
  "getLog.fail":              `ログの取得に失敗しました`,
  "getUserInfo.fail":         `ユーザー情報の取得に失敗しました`,
  "conectionTest.fail":       `接続テストを実行できませんでした　もう一度やり直してください`,
  "updateUserProf.fail":      `プロフィールの更新に失敗しました　もう一度やり直してください`,
  "changePassword.fail":      `パスワードを変更できませんでした　もう一度やり直してください`,
  "addTicket.fail":           `チケットの購入に失敗しました　もう一度やり直してください`,
  "destroy.fail":             `退会できませんでした　もう一度やり直してください`,

  "user": "ユーザー",
  "role": "ロール",
  "permission": "パーミッション",
  "sideAlert.success": `{attribute, plural,
  	=user {ユーザー} =role {ロール} =permission {パーミッション}}{method, plural,
  	=activate {の停止を解除} =deactivate {を停止} =restore{を復旧} =destroy{を削除} =delete{を完全に削除}}しました`,
  "sideAlert.fail":`{attribute, plural,
  	=user {ユーザー} =role {ロール} =permission {パーミッション}}{method, plural,
  	=activate {の停止解除} =deactivate {の停止} =restore{の復旧} =destroy{の削除} =delete{の完全削除}}に失敗しました　もう一度実行してください`,
}