import {
  REQUEST_LOG,
  REQUEST_LOG_SUCCESS,
  REQUEST_LOG_FAIL,
  SET_LOG_PAGE,
  CLEAR_LOG
} from '../constants/ActionTypes';

const initialState = {
  data: [],
  isFetching: false,
  didInvalidate: false
};

export default function reservation(state = initialState, action) {
  switch (action.type) {
  case REQUEST_LOG:
    return Object.assign({}, state, {
      isFetching: true,
      didInvalidate: false
    });

  case REQUEST_LOG_SUCCESS:
    return Object.assign({}, state, {
      isFetching: false,
      didInvalidate: false,
      data: action.data
    });

  case REQUEST_LOG_FAIL:
    return Object.assign({}, state, {
      isFetching: false,
      didInvalidate: true
    });
  default:
    return state;
  }
}


/*
function change(state = {
  isFetching: false,
  didInvalidate: false,
  data: null
}, action) {
  switch (action.type) {
  case REQUEST_LOG:
    return Object.assign({}, state, {
      isFetching: true,
      didInvalidate: false
    });

  case REQUEST_LOG_SUCCESS:
    return Object.assign({}, state, {
      isFetching: false,
      didInvalidate: false,
      data: action.data
    });

  case REQUEST_LOG_FAIL:
    return Object.assign({}, state, {
      isFetching: false,
      didInvalidate: true
    });

  default:
    return state;
  }
}

export default function logs(state = { page: 1 }, action) {
  switch (action.type) {
  case REQUEST_LOG:
  case REQUEST_LOG_SUCCESS:
  case REQUEST_LOG_FAIL:
    return Object.assign({}, state, {
      [action.page]: change(state[action.page], action)
    });

  case SET_LOG_PAGE:
    return Object.assign({}, state, { page: action.page });

  case CLEAR_LOG:
    return { page: 1 };
  default:
    return state;
  }
}
*/