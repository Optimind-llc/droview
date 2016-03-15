import expect from 'expect';
import { applyMiddleware } from 'redux';
import nock from 'nock';
import thunk from 'redux-thunk';
const middlewares = [ thunk ];
import * as actions from '../../../src/js/actions/userinfo';
import * as types from '../../../src/js/constants/ActionTypes';
import { _DOMAIN_NAME } from '../../../src/config/env';
import { REQUEST_USER_INFO, UPDATE_USER_PROF, CHANGE_PASSWORD } from '../../../src/config/url';

function mockStore(getState, expectedActions, done) {
  if (!Array.isArray(expectedActions)) {
    throw new Error('expectedActions should be an array of expected actions.');
  }
  if (typeof done !== 'undefined' && typeof done !== 'function') {
    throw new Error('done should either be undefined or function.');
  }

  function mockStoreWithoutMiddleware() {
    return {
      getState() {
        return typeof getState === 'function' ?
          getState() :
          getState;
      },

      dispatch(action) {
        const expectedAction = expectedActions.shift();
        expect(action).toEqual(expectedAction);
        if (done && !expectedActions.length) {
          done();
        }
        return action;
      }
    };
  }
  const mockStoreWithMiddleware = applyMiddleware(
    ...middlewares
  )(mockStoreWithoutMiddleware);

  return mockStoreWithMiddleware();
}

describe('fetchUserInfo', () => {
  afterEach(() => {
    nock.cleanAll();
  });

  it('fetch SUCCESS', (done) => {
    nock(_DOMAIN_NAME)
      .post(REQUEST_USER_INFO)
      .reply(200, {name: 'user'});

    const state = {user: {}};
    const expectedActions = [
      {
        type: types.REQUEST_USERINFO
      }, {
        type: types.REQUEST_USERINFO_SUCCESS,
        data: {name: 'user'}
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchUserInfo());
  });

  it('fetch FAIL', (done) => {
    nock(_DOMAIN_NAME)
      .post(REQUEST_USER_INFO)
      .replyWithError('something happened');

    const state = {user: {}};
    const expectedActions = [
      {
        type: types.REQUEST_USERINFO
      }, {
        type: types.REQUEST_USERINFO_FAIL
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchUserInfo());
  });
});

describe('UpdateUserProf', () => {
  afterEach(() => {
    nock.cleanAll();
  });

  it('fetch SUCCESS', (done) => {
    nock(_DOMAIN_NAME)
      .post(UPDATE_USER_PROF)
      .reply(200, {userProf: {name: 'user'}, msg: {type: 'error', msg: 'message'}});

    const request = {name: 'user'}
    const state = {user: {}};
    const expectedActions = [
      {
        type: types.REQUEST_USERINFO
      }, {
        type: types.REQUEST_USERINFO_SUCCESS,
        data: {name: 'user'}
      }, {
        type: types.ADD_SIDE_ALERT,
        status: 'success',
        messageId: 'updateUserProf.success',
        value: null
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.UpdateUserProf(request));
  });

  it('fetch FAIL', (done) => {
    nock(_DOMAIN_NAME)
      .post(UPDATE_USER_PROF)
      .replyWithError('something happened');

    const request = {name: 'user'}
    const state = {user: {}};
    const expectedActions = [
      {
        type: types.REQUEST_USERINFO
      }, {
        type: types.REQUEST_USERINFO_FAIL
      }, {
        type: types.ADD_SIDE_ALERT,
        status: 'danger',
        messageId: 'updateUserProf.fail',
        value: null
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.UpdateUserProf(request));
  });
});

describe('postChangePassword', () => {
  afterEach(() => {
    nock.cleanAll();
  });

  it('fetch SUCCESS', (done) => {
    nock(_DOMAIN_NAME)
      .post(CHANGE_PASSWORD)
      .reply(200, {type: 'error', msg: 'message'});

    const request = {name: 'user'}
    const state = {user: {}};
    const expectedActions = [
      {
        type: types.CHANGE_PASS
      }, {
        type: types.ADD_SIDE_ALERT,
        status: 'success',
        messageId: 'changePassword.success',
        value: null
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.postChangePassword(request));
  });

  it('fetch FAIL', (done) => {
    nock(_DOMAIN_NAME)
      .post(CHANGE_PASSWORD)
      .replyWithError('something happened');

    const request = {name: 'user'}
    const state = {user: {}};
    const expectedActions = [
      {
        type: types.CHANGE_PASS
      }, {
        type: types.ADD_SIDE_ALERT,
        status: 'danger',
        messageId: 'changePassword.fail',
        value: null
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.postChangePassword(request));
  });
});