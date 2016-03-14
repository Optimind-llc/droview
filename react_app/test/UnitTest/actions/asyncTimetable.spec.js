import expect from 'expect';
import { applyMiddleware } from 'redux';
import nock from 'nock';
import thunk from 'redux-thunk';
const middlewares = [ thunk ];
import * as actions from '../../../src/js/actions/timetable';
import * as types from '../../../src/js/constants/ActionTypes';
import { _DOMAIN_NAME } from '../../../src/config/env';
import { REQUEST_TIMETABLE, REQUEST_DEFAULT_STATUS } from '../../../src/config/url';

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

function mockStore2(getState, expectedAction) {
  function mockStoreWithoutMiddleware() {
    return {
      getState() {
        return typeof getState === 'function' ?
            getState() :
            getState;
      },

      dispatch(action) {
        return action;
      }
    };
  }
  const mockStoreWithMiddleware = applyMiddleware(...middlewares)(mockStoreWithoutMiddleware);
  return mockStoreWithMiddleware();
}

describe('fetchTimetableIfNeeded', () => {
  afterEach(() => {
    nock.cleanAll();
  });

  it('should fetch and return SUCCESS when type changed part1', (done) => {
    nock(_DOMAIN_NAME)
      .post(REQUEST_TIMETABLE)
      .reply(200, ['date', 'timetable']);

    const state = {
      selector: {
        flightTypes: [
          {id: 1, name: 'type1', en: 'en1', checked: true},
          {id: 2, name: 'type2', en: 'en2', checked: false},
          {id: 3, name: 'type3', en: 'en3', checked: false}
        ],
        places: [
          {id: 1, path: '/path1', active: true, checked: true},
          {id: 2, path: '/path2', active: true, checked: false},
          {id: 3, path: '/path3', active: false, checked: false},
          {id: 4, path: '/path4', active: false, checked: false}
        ],
        week: 0
      },
      timetables: {
        plans: {
          1: [1, 2],
          2: [1, 2, 3],
          3: [1, 3, 4],
          4: [2, 3, 4]
        }
      }
    };
    const expectedActions = [
      {
        type: types.CHANGE_TYPE_CHECKED,
        id: 2
      }, {
        type: types.CHANGE_PLACE_ACTIVE,
        ids: [1, 2, 3]
      }, {
        type: types.CHANGE_PLACE_CHECKED,
        id: 1
      }, {
        type: types.CHANGE_WEEK,
        week: 0
      }, {
        type: types.REQUEST_TIMETABLE,
        key: '2_1_0'
      }, {
        type: types.REQUEST_TIMETABLE_SUCCESS,
        key: '2_1_0',
        data: ['date', 'timetable']
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchTimetableIfNeeded(2, null, null));
  });

  it('should fetch and return SUCCESS when type changed part2', (done) => {
    nock(_DOMAIN_NAME)
      .post(REQUEST_TIMETABLE)
      .reply(200, ['date', 'timetable']);

    const state = {
      selector: {
        flightTypes: [
          {id: 1, name: 'type1', en: 'en1', checked: true},
          {id: 2, name: 'type2', en: 'en2', checked: false},
          {id: 3, name: 'type3', en: 'en3', checked: false}
        ],
        places: [
          {id: 1, path: '/path1', active: true, checked: true},
          {id: 2, path: '/path2', active: true, checked: false},
          {id: 3, path: '/path3', active: false, checked: false},
          {id: 4, path: '/path4', active: false, checked: false}
        ],
        week: 0
      },
      timetables: {
        plans: {
          1: [1, 2],
          2: [1, 2, 3],
          3: [1, 3, 4],
          4: [2, 3, 4]
        }
      }
    };
    const expectedActions = [
      {
        type: types.CHANGE_TYPE_CHECKED,
        id: 4
      }, {
        type: types.CHANGE_PLACE_ACTIVE,
        ids: [2, 3, 4]
      }, {
        type: types.CHANGE_PLACE_CHECKED,
        id: 2
      }, {
        type: types.CHANGE_WEEK,
        week: 0
      }, {
        type: types.REQUEST_TIMETABLE,
        key: '4_2_0'
      }, {
        type: types.REQUEST_TIMETABLE_SUCCESS,
        key: '4_2_0',
        data: ['date', 'timetable']
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchTimetableIfNeeded(4, null, null));
  });

  it('should fetch and return SUCCESS when place changed', (done) => {
    nock(_DOMAIN_NAME)
      .post(REQUEST_TIMETABLE)
      .reply(200, ['date', 'timetable']);

    const state = {
      selector: {
        flightTypes: [
          {id: 1, name: 'type1', en: 'en1', checked: true},
          {id: 2, name: 'type2', en: 'en2', checked: false},
          {id: 3, name: 'type3', en: 'en3', checked: false}
        ],
        places: [
          {id: 1, path: '/path1', active: true, checked: true},
          {id: 2, path: '/path2', active: true, checked: false},
          {id: 3, path: '/path3', active: false, checked: false},
          {id: 4, path: '/path4', active: false, checked: false}
        ],
        week: 0
      },
      timetables: {
        plans: {
          1: [1, 2],
          2: [1, 2, 3],
          3: [1, 3, 4],
          4: [2, 3, 4]
        }
      }
    };
    const expectedActions = [
      {
        type: types.CHANGE_TYPE_CHECKED,
        id: 1
      }, {
        type: types.CHANGE_PLACE_ACTIVE,
        ids: [1, 2]
      }, {
        type: types.CHANGE_PLACE_CHECKED,
        id: 2
      }, {
        type: types.CHANGE_WEEK,
        week: 0
      }, {
        type: types.REQUEST_TIMETABLE,
        key: '1_2_0'
      }, {
        type: types.REQUEST_TIMETABLE_SUCCESS,
        key: '1_2_0',
        data: ['date', 'timetable']
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchTimetableIfNeeded(null, 2, null));
  });

  it('should fetch and return SUCCESS when week changed', (done) => {
    nock(_DOMAIN_NAME)
      .post(REQUEST_TIMETABLE)
      .reply(200, ['date', 'timetable']);

    const state = {
      selector: {
        flightTypes: [
          {id: 1, name: 'type1', en: 'en1', checked: true},
          {id: 2, name: 'type2', en: 'en2', checked: false},
          {id: 3, name: 'type3', en: 'en3', checked: false}
        ],
        places: [
          {id: 1, path: '/path1', active: true, checked: true},
          {id: 2, path: '/path2', active: true, checked: false},
          {id: 3, path: '/path3', active: false, checked: false},
          {id: 4, path: '/path4', active: false, checked: false}
        ],
        week: 0
      },
      timetables: {
        plans: {
          1: [1, 2],
          2: [1, 2, 3],
          3: [1, 3, 4],
          4: [2, 3, 4]
        }
      }
    };
    const expectedActions = [
      {
        type: types.CHANGE_TYPE_CHECKED,
        id: 1
      }, {
        type: types.CHANGE_PLACE_ACTIVE,
        ids: [1, 2]
      }, {
        type: types.CHANGE_PLACE_CHECKED,
        id: 1
      }, {
        type: types.CHANGE_WEEK,
        week: 1
      }, {
        type: types.REQUEST_TIMETABLE,
        key: '1_1_1'
      }, {
        type: types.REQUEST_TIMETABLE_SUCCESS,
        key: '1_1_1',
        data: ['date', 'timetable']
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchTimetableIfNeeded(null, null, 1));
  });

  it('should fetch and return FAIL', (done) => {
    nock(_DOMAIN_NAME)
        .post(REQUEST_TIMETABLE)
        .replyWithError('something happened');

    const state = {
      selector: {
        flightTypes: [
          {id: 1, name: 'type1', en: 'en1', checked: true},
          {id: 2, name: 'type2', en: 'en2', checked: false},
          {id: 3, name: 'type3', en: 'en3', checked: false}
        ],
        places: [
          {id: 1, path: '/path1', active: true, checked: true},
          {id: 2, path: '/path2', active: true, checked: false},
          {id: 3, path: '/path3', active: false, checked: false},
          {id: 4, path: '/path4', active: false, checked: false}
        ],
        week: 0
      },
      timetables: {
        plans: {
          1: [1, 2],
          2: [1, 2, 3],
          3: [1, 3, 4],
          4: [2, 3, 4]
        }
      }
    };
    const expectedActions = [
      {
        type: types.CHANGE_TYPE_CHECKED,
        id: 4
      }, {
        type: types.CHANGE_PLACE_ACTIVE,
        ids: [2, 3, 4]
      }, {
        type: types.CHANGE_PLACE_CHECKED,
        id: 2
      }, {
        type: types.CHANGE_WEEK,
        week: 0
      }, {
        type: types.REQUEST_TIMETABLE,
        key: '4_2_0'
      }, {
        type: types.REQUEST_TIMETABLE_FAIL,
        key: '4_2_0'
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchTimetableIfNeeded(4, null, null));
  });

  it('need not fetch', (done) => {
    const state = {
      selector: {
        flightTypes: [
          {id: 1, name: 'type1', en: 'en1', checked: true},
          {id: 2, name: 'type2', en: 'en2', checked: false},
          {id: 3, name: 'type3', en: 'en3', checked: false}
        ],
        places: [
          {id: 1, path: '/path1', active: true, checked: true},
          {id: 2, path: '/path2', active: true, checked: false},
          {id: 3, path: '/path3', active: false, checked: false},
          {id: 4, path: '/path4', active: false, checked: false}
        ],
        week: 0
      },
      timetables: {
        plans: {
          1: [1, 2],
          2: [1, 2, 3],
          3: [1, 3, 4],
          4: [2, 3, 4]
        },
        '4_2_0': {}
      }
    };
    const expectedActions = [
      {
        type: types.CHANGE_TYPE_CHECKED,
        id: 4
      }, {
        type: types.CHANGE_PLACE_ACTIVE,
        ids: [2, 3, 4]
      }, {
        type: types.CHANGE_PLACE_CHECKED,
        id: 2
      }, {
        type: types.CHANGE_WEEK,
        week: 0
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchTimetableIfNeeded(4, null, null));
  });
});

describe('fetchDefaultStatus', () => {
  afterEach(() => {
    nock.cleanAll();
  });

  it('fetch SUCCESS ver1', (done) => {
    const flightTypes = 'types';
    const places = 'places';
    const plans = [
      {type_id: '1', place_id: '1'},
      {type_id: '1', place_id: '2'},
      {type_id: '1', place_id: '3'},
      {type_id: '2', place_id: '1'},
      {type_id: '2', place_id: '2'},
      {type_id: '3', place_id: '1'},
      {type_id: '3', place_id: '3'}
    ];
    const key = '1_1_0';
    const data = 'data';

    nock(_DOMAIN_NAME)
      .post(REQUEST_DEFAULT_STATUS)
      .reply(200, {selector: {types: flightTypes, places, plans}, timetable: {key, data}});

    const state = {user: {}};
    const expectedActions = [
      {
        type: types.SET_PLANS,
        plans: {1: [1, 2, 3], 2: [1, 2], 3: [1, 3]}
      }, {
        type: types.REQUEST_TIMETABLE_SUCCESS,
        key,
        data
      }, {
        type: types.SET_TYPE_STATUS,
        status: flightTypes
      }, {
        type: types.SET_PLACE_STATUS,
        status: places
      }, {
        type: types.CHANGE_TYPE_CHECKED,
        id: 1
      }, {
        type: types.CHANGE_PLACE_ACTIVE,
        ids: [1, 2, 3]
      }, {
        type: types.CHANGE_PLACE_CHECKED,
        id: 1
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchDefaultStatus());
  });

  it('fetch SUCCESS ver2', (done) => {
    const flightTypes = 'types';
    const places = 'places';
    const plans = [
      {type_id: '1', place_id: '2'},
      {type_id: '1', place_id: '3'},
      {type_id: '2', place_id: '1'},
      {type_id: '2', place_id: '2'},
      {type_id: '3', place_id: '1'},
      {type_id: '3', place_id: '3'}
    ];
    const key = '1_1_0';
    const data = 'data';

    nock(_DOMAIN_NAME)
      .post(REQUEST_DEFAULT_STATUS)
      .reply(200, {selector: {types: flightTypes, places, plans}, timetable: {key, data}});

    const state = {user: {}};
    const expectedActions = [
      {
        type: types.SET_PLANS,
        plans: {1: [2, 3], 2: [1, 2], 3: [1, 3]}
      }, {
        type: types.REQUEST_TIMETABLE_SUCCESS,
        key,
        data
      }, {
        type: types.SET_TYPE_STATUS,
        status: flightTypes
      }, {
        type: types.SET_PLACE_STATUS,
        status: places
      }, {
        type: types.CHANGE_TYPE_CHECKED,
        id: 1
      }, {
        type: types.CHANGE_PLACE_ACTIVE,
        ids: [2, 3]
      }, {
        type: types.CHANGE_PLACE_CHECKED,
        id: 2
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchDefaultStatus());
  });

  it('fetch SUCCESS ver3', (done) => {
    const flightTypes = 'types';
    const places = 'places';
    const plans = [
      {type_id: '12', place_id: '5'},
      {type_id: '13', place_id: '6'},
      {type_id: '11', place_id: '7'},
      {type_id: '10', place_id: '7'},
      {type_id: '10', place_id: '8'},
      {type_id: '10', place_id: '9'}
    ];
    const key = '1_1_0';
    const data = 'data';

    nock(_DOMAIN_NAME)
      .post(REQUEST_DEFAULT_STATUS)
      .reply(200, {selector: {types: flightTypes, places, plans}, timetable: {key, data}});

    const state = {user: {}};
    const expectedActions = [
      {
        type: types.SET_PLANS,
        plans: {10: [7, 8, 9], 11: [7], 12: [5], 13: [6]}
      }, {
        type: types.REQUEST_TIMETABLE_SUCCESS,
        key,
        data
      }, {
        type: types.SET_TYPE_STATUS,
        status: flightTypes
      }, {
        type: types.SET_PLACE_STATUS,
        status: places
      }, {
        type: types.CHANGE_TYPE_CHECKED,
        id: 10
      }, {
        type: types.CHANGE_PLACE_ACTIVE,
        ids: [7, 8, 9]
      }, {
        type: types.CHANGE_PLACE_CHECKED,
        id: 7
      }
    ];
    const store = mockStore(state, expectedActions, done);
    store.dispatch(actions.fetchDefaultStatus());
  });
});
