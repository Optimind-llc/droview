import React, { Component } from 'react';
import expect from 'expect';
import TestUtils from 'react-addons-test-utils';
import expectJSX from 'expect-jsx';
expect.extend(expectJSX);
//components
import MainSection from '../../../../src/js/components/Reserve/MainSection';
import SelectBox from '../../../../src/js/components/Reserve/SelectBox';
import SelectDate from '../../../../src/js/components/Reserve/SelectDate';
import TimetableBox from '../../../../src/js/components/Reserve/TimetableBox';

function setup(propOverrides) {
  const props = Object.assign({
    plans: {},
    selector: {},
    timetableKey: '1_1_0',
    data: { date: [], timetable:[] },
    isFetching: false,
    actions: {
      changeSelectorStatus: expect.createSpy(),
      fetchTestToken: expect.createSpy(),
      fetchDefaultStatus: expect.createSpy(),
      fetchTimetableIfNeeded: expect.createSpy(),
      modalOff: expect.createSpy(),
      reserve: expect.createSpy()
    },
    modal: false
  }, propOverrides);

  const renderer = TestUtils.createRenderer();

  renderer.render(
    <MainSection {...props} />
  );

  const output = renderer.getRenderOutput();

  return {
    props: props,
    output: output,
  };
}

function callComponentWillReceiveProps(props = {}, nextProps = {}) {
  class ParentClass extends Component {
    constructor() {
      super();
      this.state = props;
    }
    render() {
      return <MainSection {...this.state} />;
    }
  }
  const parent = TestUtils.renderIntoDocument(<ParentClass />);
  parent.setState(nextProps);
  return { parent, nextProps };
}

describe('components', () => {
  describe('MainSection', () => {
    it('should render components correctly', () => {
      const { output, props } = setup();
      let expectedElement = (
      <div>
        <SelectBox
          selector = {props.selector}
          isFetching = {props.isFetching}
          fetchTimetableIfNeeded = {function noRefCheck() {}}/>
        <div className="timetable-box">
          <SelectDate
            isFetching = {props.isFetching}
            fetchTimetableIfNeeded = {function noRefCheck() {}}/>
          <TimetableBox
            isFetching = {props.isFetching}
            didInvalidate = {props.didInvalidate}
            isOld = {props.isOld}
            data = {props.data}
            fetchTimetableIfNeeded = {function noRefCheck() {}}
            fetchTestToken = {function noRefCheck() {}}/>
        </div>
      </div>
      );
      expect(output).toEqualJSX(expectedElement);
    });

    it('should call fetchDefaultStatus at componentDidMount', () => {
      const props = {
        selector: {
          flightTypes: [{id: 1, name: 'type1', en: 'en1', checked: true}],
          places: [{id: 1, path: '/path1', active: true, checked: true}],
          week: 0
        },
        timetableKey: '1_1_0',
        data: { date: [], timetable:[] },
        isFetching: false,
        actions: {
          fetchDefaultStatus: expect.createSpy(),
          fetchTestToken: expect.createSpy(),
          fetchTimetableIfNeeded: expect.createSpy(),
          reserve: expect.createSpy(),
          modalOff: expect.createSpy(),
        },
        modal: false
      };
      TestUtils.renderIntoDocument(<MainSection {...props} />)
      expect(props.actions.fetchDefaultStatus).toHaveBeenCalledWith();
    });

    it('should call modalOff and reserve at ComponentWillReceiveProps', (doen) => {
      const props = {
        selector: {
          flightTypes: [{id: 1, name: 'type1', en: 'en1', checked: true}],
          places: [{id: 1, path: '/path1', active: true, checked: true}],
          week: 0
        },
        timetableKey: '1_1_0',
        data: { date: [], timetable:[] },
        isFetching: false,
        actions: {
          fetchDefaultStatus: expect.createSpy(),
          fetchTestToken: expect.createSpy(),
          fetchTimetableIfNeeded: expect.createSpy(),
          reserve: expect.createSpy(),
          modalOff: expect.createSpy(),
        },
        modal: false
      };

      const { nextProps } = callComponentWillReceiveProps(
        props,
        Object.assign(props, { modal: true })
      );

      setTimeout(function(){
        expect(nextProps.actions.modalOff).toHaveBeenCalledWith();
        expect(nextProps.actions.reserve).toHaveBeenCalledWith({token: 'token'}, '1_1_0');
        doen();
      }, 1500);
    });
  });
});
