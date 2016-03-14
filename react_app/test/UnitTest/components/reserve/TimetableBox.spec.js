import expect from 'expect';
import React from 'react';
import TestUtils from 'react-addons-test-utils';
//components
import TimetableBox from '../../../../src/js/components/Reserve/TimetableBox';
import TimetableColumns from '../../../../src/js/components/Reserve/TimetableColumns';

function setup(propOverrides) {
  const props = Object.assign({
    isFetching: false,
    didInvalidate: false,
    fetchTestToken: expect.createSpy(),
    fetchTimetableIfNeeded: expect.createSpy(),
    data: {
      date: [
        {c: 'className', d: 'date'},
        {c: 'className', d: 'date'},
        {c: 'className', d: 'date'}
      ],
      timetable: [
        [{c: 'className', id: '11', t: 'time'}, {c: 'className', id: '12', t: 'time'}],
        [{c: 'className', id: '21', t: 'time'}, {c: 'className', id: '22', t: 'time'}],
        [{c: 'className', id: '31', t: 'time'}, {c: 'className', id: '32', t: 'time'}]
      ]
    }
  }, propOverrides);

  const renderer = TestUtils.createRenderer();

  renderer.render(
    <TimetableBox {...props} />
  );

  const output = renderer.getRenderOutput();

  return {
    props: props,
    output: output,
    renderer: renderer
  };
}

describe('components', () => {
  describe('TimatableBox', () => {

    it('should render components correctly', () => {
      const { output, props } = setup();
      let expectedElement = (
        <div>
          <div>
            <ul className="date-warp">
              <Date className="className" dateNodes="date"/>
              <Date className="className" dateNodes="date"/>
              <Date className="className" dateNodes="date"/>
            </ul>
            <div className="timetable">
              <TimetableColumns
                columns={[{c: 'className', id: '11', t: 'time'}, {c: 'className', id: '12', t: 'time'}]}
                fetchTestToken={function noRefCheck() {}}/>
              <TimetableColumns
                columns={[{c: 'className', id: '21', t: 'time'}, {c: 'className', id: '22', t: 'time'}]}
                fetchTestToken={function noRefCheck() {}}/>
              <TimetableColumns
                columns={[{c: 'className', id: '31', t: 'time'}, {c: 'className', id: '32', t: 'time'}]}
                fetchTestToken={function noRefCheck() {}}/>
            </div>
          </div>
        </div>
      );
      expect(output).toEqualJSX(expectedElement);
    });
  });
});
