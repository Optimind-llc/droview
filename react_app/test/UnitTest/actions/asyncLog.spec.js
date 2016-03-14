import expect from 'expect';
import { applyMiddleware } from 'redux';
import nock from 'nock';
import thunk from 'redux-thunk';
const middlewares = [ thunk ];
import * as actions from '../../../src/js/actions/log';
import * as types from '../../../src/js/constants/ActionTypes';
import { _DOMAIN_NAME } from '../../../src/config/env';
import { REQUEST_LOG } from '../../../src/config/url';

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

describe('fetchLog', () => {
  afterEach(() => {
    nock.cleanAll();
  });

  it('fetch SUCCESS', (done) => {
    nock(_DOMAIN_NAME)
      .post(REQUEST_LOG)
      .reply(200, [
        {action: '1'},
        {action: '1'}
      ]);

    const state = {user: {}};
    const expectedActions = [
      {
        type: types.REQUEST_LOG
      }, {
        type: types.REQUEST_LOG_SUCCESS,
        data: [
          {action: '1'},
          {action: '1'}
        ]
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchLog());
  });

  it('fetch FAIL', (done) => {
    nock(_DOMAIN_NAME)
      .post(REQUEST_LOG)
      .replyWithError('something happened');

    const state = {user: {}};
    const expectedActions = [
      {
        type: types.REQUEST_LOG
      }, {
        type: types.REQUEST_LOG_FAIL
      }, {
        type: types.ADD_SIDE_ALERT,
        status: 'danger',
        messageId: 'getLog.fail',
        value: null
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchLog());
  });
});
