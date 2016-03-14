import expect from 'expect';
import { applyMiddleware } from 'redux';
import nock from 'nock';
import thunk from 'redux-thunk';
const middlewares = [ thunk ];
import * as actions from '../../../src/js/actions/reservation';
import * as types from '../../../src/js/constants/ActionTypes';
import { _DOMAIN_NAME } from '../../../src/config/env';
import { REQUEST_RESERVATIONS, REQUEST_TEST_TOKEN, RESERVE, CANCEL } from '../../../src/config/url';

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

describe('fetchReservations', () => {
  afterEach(() => {
    nock.cleanAll();
  });

  it('fetch SUCCESS', (done) => {
    nock(_DOMAIN_NAME)
      .post(REQUEST_RESERVATIONS)
      .reply(200, [
        {flight_at: '2015-12-29 09:00:00'},
        {flight_at: '2015-12-29 09:20:00'}
      ]);

    const state = {user: {}};
    const expectedActions = [
      {
        type: types.REQUEST_RESERVATIONS
      }, {
        type: types.REQUEST_RESERVATIONS_SUCCESS,
        data: [
          {flight_at: '2015-12-29 09:00:00'},
          {flight_at: '2015-12-29 09:20:00'}
        ]
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchReservations());
  });

  it('fetch FAIL', (done) => {
    nock(_DOMAIN_NAME)
      .post(REQUEST_RESERVATIONS)
      .replyWithError('something happened');

    const state = {user: {}};
    const expectedActions = [
      {
        type: types.REQUEST_RESERVATIONS
      }, {
        type: types.REQUEST_RESERVATIONS_FAIL
      }, {
        type: types.ADD_SIDE_ALERT,
        status: 'danger',
        messageId: 'getReservations.fail',
        value: null
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchReservations());
  });
});

describe('fetchTestToken', () => {
  afterEach(() => {
    nock.cleanAll();
  });

  it('fetch SUCCESS and return success', (done) => {
    nock(_DOMAIN_NAME)
      .post(REQUEST_TEST_TOKEN)
      .reply(200, {jwt: 'jwtToken', msg: {type: 'success', msg: 'message'}});

    const request = {id: '123'};
    const state = {user: {}};
    const expectedActions = [
      {
        type: types.SET_TEST_TOKEN,
        value: 'jwtToken'
      }, {
        type: types.MODAL_ON
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchTestToken(request));
  });

  it('fetch SUCCESS but return error', (done) => {
    nock(_DOMAIN_NAME)
      .post(REQUEST_TEST_TOKEN)
      .reply(200, {jwt: '', msg: {type: 'error', msg: 'error message'}});

    const request = {id: '123'};
    const state = {user: {}};
    const expectedActions = [
      {
        type: types.ADD_SIDE_ALERT,
        status: 'danger',
        messageId: 'reserve.fail',
        value: { reason: 'error message' }
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchTestToken(request));
  });

  it('fetch FAIL', (done) => {
    nock(_DOMAIN_NAME)
      .post(REQUEST_TEST_TOKEN)
      .replyWithError('something happened');

    const request = {id: '123'};
    const state = {user: {}};
    const expectedActions = [
      {
        type: types.ADD_SIDE_ALERT,
        status: 'danger',
        messageId: 'conectionTest.fail',
        value: null
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchTestToken(request));
  });
});

describe('reserve', () => {
  afterEach(() => {
    nock.cleanAll();
  });

  it('reserve SUCCESS and return success', (done) => {
    nock(_DOMAIN_NAME)
      .post(RESERVE)
      .reply(200, {
        jwt: {'123': 'jwtToken'},
        msg: {'type': 'success', 'msg': 'message'},
        reservations: '1'
      });

    const request = {token: 'jwtTestToken'};
    const key = '1_1_0';
    const state = {user: {}};
    const expectedActions = [
      {
        type: types.DELETE_TEST_TOKEN
      }, {
        type: types.SET_CONF_TOKEN,
        token: {'123': 'jwtToken'}
      }, {
        type: types.UPDATE_USERINFO_RESERVATION,
        num: '1'
      }, {
        type: types.TIMETABLE_IS_OLD,
        key: key
      }, {
        type: types.ADD_SIDE_ALERT,
        status: 'success',
        messageId: 'reserve.success',
        value: null
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.reserve(request, key));
  });

  it('reserve SUCCESS but return error', (done) => {
    nock(_DOMAIN_NAME)
      .post(RESERVE)
      .reply(200, {
        jwt: {'id': '123', 'token': 'jwtToken'},
        msg: {'type': 'error', 'msg': 'error message'},
        reservations: '1'
      });

    const request = {token: 'jwtTestToken'};
    const key = '1_1_0';
    const state = {user: {}};
    const expectedActions = [
      {
        type: types.ADD_SIDE_ALERT,
        status: 'danger',
        messageId: 'reserve.fail',
        value: { reason: 'error message' }
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.reserve(request, key));
  });

  it('reserve FAIL', (done) => {
    nock(_DOMAIN_NAME)
      .post(RESERVE)
      .replyWithError('something happened');

    const request = {token: 'jwtTestToken'};
    const key = '1_1_0';
    const state = {user: {}};
    const expectedActions = [
      {
        type: types.ADD_SIDE_ALERT,
        status: 'danger',
        messageId: 'reserve.fail',
        value: { reason: 'server' }
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.reserve(request, key));
  });
});

describe('cancel', () => {
  afterEach(() => {
    nock.cleanAll();
  });

  it('cancel SUCCESS and return success', (done) => {
    nock(_DOMAIN_NAME)
      .post(CANCEL)
      .reply(200, {
        msg: {type: 'success', msg: 'message'},
        reservations: '0',
        data: [{flight_at: '2015-12-29 09:00:00'}, {flight_at: '2015-12-29 09:20:00'}]
      });

    const request = {id: '123'};
    const state = {user: {}};
    const expectedActions = [
      {
        type: types.DO_CANCEL_ACTION,
        id: request.id
      }, {
        type: types.DONE_CANCEL_ACTION,
        id: request.id
      }, {
        type: types.DELETE_CONF_TOKEN,
        key: request.id
      }, {
        type: types.REQUEST_RESERVATIONS_SUCCESS,
        data: [{flight_at: '2015-12-29 09:00:00'}, {flight_at: '2015-12-29 09:20:00'}]
      }, {
        type: types.UPDATE_USERINFO_RESERVATION,
        num: '0'
      }, {
        type: types.ADD_SIDE_ALERT,
        status: 'success',
        messageId: 'cancel.success',
        value: null
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.cancel(request));
  });

  it('cancel SUCCESS but return error', (done) => {
    nock(_DOMAIN_NAME)
      .post(CANCEL)
      .reply(200, {
        msg: {type: 'error', msg: 'error message'},
      });

    const request = {id: '123'};
    const state = {user: {}};
    const expectedActions = [
      {
        type: types.DO_CANCEL_ACTION,
        id: request.id
      }, {
        type: types.DONE_CANCEL_ACTION,
        id: request.id
      }, {
        type: types.ADD_SIDE_ALERT,
        status: 'danger',
        messageId: 'cancel.fail',
        value: { reason: 'error message' }
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.cancel(request));
  });

  it('cancel FAIL', (done) => {
    nock(_DOMAIN_NAME)
      .post(CANCEL)
      .replyWithError('something happened');

    const request = {id: '123'};
    const state = {user: {}};
    const expectedActions = [
      {
        type: types.DO_CANCEL_ACTION,
        id: request.id
      }, {
        type: types.DONE_CANCEL_ACTION,
        id: request.id
      }, {
        type: types.ADD_SIDE_ALERT,
        status: 'danger',
        messageId: 'cancel.fail',
        value: { reason: 'server' }
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.cancel(request));
  });
});
