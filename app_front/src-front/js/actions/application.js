import * as types from '../constants/ApplicationActionTypes';

export function changeLocale(locale) {
  return {
    type: types.CANGE_LOCALE,
    locale
  };
}
