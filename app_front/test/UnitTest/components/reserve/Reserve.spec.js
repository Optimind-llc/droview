import React from 'react';
import expect from 'expect';
import TestUtils from 'react-addons-test-utils';
import nock from 'nock';
import Reserve from '../../../../src/js/components/Reserve/Reserve';

function setup(actionsOverrides, stateOverrides) {
  const actions = Object.assign({
    requestTimetableSuccess: expect.createSpy(),
    setPlaceStatus: expect.createSpy(),
    changePlaceChecked: expect.createSpy(),
    setTypeStatus: expect.createSpy(),
    changeTypeChecked: expect.createSpy()
  }, actionsOverrides);

  const state = Object.assign({
    modal: false,
    plans: {some: 'object'}
  }, stateOverrides);

  const store = {
    getState: function() {
      return state;
    },
    subscribe: function() {},
    dispatch: function() {}
  };

  const renderer = TestUtils.createRenderer();

  renderer.render(
    <Reserve store={store}/>
  );

  const output = renderer.getRenderOutput();

  return {
    actions: actions,
    state: state,
    output: output
  };
}

describe('components', () => {
  describe('Reserve', () => {
    it('should set props correctly part1', () => {
      const { output, state } = setup({}, {
        timetables: {
          '1_1_0': {
            isFetching: false,
            didInvalidate: false,
            isOld: false,
            lastUpdated: '12345678',
            data: {some: 'data'}
          },
          plans:{}
        },
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
        }
      });
      const timetableKey = '1_1_0';

      expect(output.props.selector).toBe(state.selector);
      expect(output.props.timetableKey).toBe(timetableKey);
      expect(output.props.data).toBe(state.timetables[timetableKey].data);
      expect(output.props.isFetching).toBe(state.timetables[timetableKey].isFetching);
      expect(output.props.didInvalidate).toBe(state.timetables[timetableKey].didInvalidate);
      expect(output.props.isOld).toBe(state.timetables[timetableKey].isOld);
      expect(output.props.lastUpdated).toBe(state.timetables[timetableKey].lastUpdated);
      expect(output.props.modal).toBe(state.modal);
    });

    it('should set props correctly part2', () => {
      const { output, state } = setup({}, {
        timetables: {
          '1_1_0': {
            isFetching: true,
            didInvalidate: true,
            isOld: true,
            lastUpdated: '12345678',
            data: {some: 'data'}
          },
          '3_4_3': {
            isFetching: false,
            didInvalidate: false,
            isOld: false,
            lastUpdated: '87654321',
            data: {some: 'data'}
          },
          plans:{}
        },
        selector: {
          flightTypes: [
            {id: 1, name: 'type1', en: 'en1', checked: false},
            {id: 2, name: 'type2', en: 'en2', checked: false},
            {id: 3, name: 'type3', en: 'en3', checked: true}
          ],
          places: [
            {id: 1, path: '/path1', active: true, checked: false},
            {id: 2, path: '/path2', active: true, checked: false},
            {id: 3, path: '/path3', active: false, checked: false},
            {id: 4, path: '/path4', active: false, checked: true}
          ],
          week: 3
        }
      });
      const timetableKey = '3_4_3';

      expect(output.props.selector).toBe(state.selector);
      expect(output.props.timetableKey).toBe(timetableKey);
      expect(output.props.data).toBe(state.timetables[timetableKey].data);
      expect(output.props.isFetching).toBe(state.timetables[timetableKey].isFetching);
      expect(output.props.didInvalidate).toBe(state.timetables[timetableKey].didInvalidate);
      expect(output.props.isOld).toBe(state.timetables[timetableKey].isOld);
      expect(output.props.lastUpdated).toBe(state.timetables[timetableKey].lastUpdated);
      expect(output.props.modal).toBe(state.modal);
    });

    it('should set props correctly part3', () => {
      const { output, state } = setup({}, {
        timetables: {
          '1_1_0': {
            isFetching: false,
            didInvalidate: false,
            isOld: false,
            lastUpdated: '12345678',
            data: {some: 'data'}
          },
          plans:{}
        },
        selector: {
          flightTypes: [
            {id: 1, name: 'type1', en: 'en1', checked: false},
            {id: 2, name: 'type2', en: 'en2', checked: true},
            {id: 3, name: 'type3', en: 'en3', checked: false}
          ],
          places: [
            {id: 1, path: '/path1', active: true, checked: true},
            {id: 2, path: '/path2', active: true, checked: false},
            {id: 3, path: '/path3', active: false, checked: false},
            {id: 4, path: '/path4', active: false, checked: false}
          ],
          week: 0
        }
      });
      const timetableKey = '2_1_0';

      expect(output.props.selector).toBe(state.selector);
      expect(output.props.timetableKey).toBe(timetableKey);
      expect(output.props.data).toBe(undefined);
      expect(output.props.isFetching).toBe(true);
      expect(output.props.didInvalidate).toBe(undefined);
      expect(output.props.isOld).toBe(undefined);
      expect(output.props.lastUpdated).toBe(undefined);
      expect(output.props.modal).toBe(state.modal);
    });
  });
});
