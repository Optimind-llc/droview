import expect from 'expect';
import React from 'react';
import TestUtils from 'react-addons-test-utils';
import TimetableColumns from '../../../../src/js/components/Reserve/TimetableColumns';

function setup(propOverrides) {
  const props = Object.assign({
    columns: [
      {c: '0', id: '111', t: 'time'},
      {c: '1', id: '222', t: 'time'},
      {c: '2', id: '333', t: 'time'},
      {c: '3', id: '444', t: 'time'}
    ],
    fetchTestToken: expect.createSpy()
  }, propOverrides);

  const renderer = TestUtils.createRenderer();

  renderer.render(
    <TimetableColumns {...props} />
  );

  const output = renderer.getRenderOutput();

  return {
    props: props,
    output: output,
    renderer: renderer
  };
}

describe('components', () => {
  describe('TimetableColumns', () => {
    it('should render components correctly', () => {
      const { output, props } = setup();
      let expectedElement = (
        <div className="time-table-colmuns">
          <div className="rsv rsv-close">
            <p>ー</p>
          </div>
          <div className="rsv rsv-reserved">
            <p>予約済み</p>
          </div>
          <div className="rsv rsv-myreservation">
            <p>自分の予約</p>
          </div>
          <form onSubmit={function noRefCheck() {}}>
            <input name="id" type="hidden" value="444"/>
            <input className="rsv rsv-open" type="submit" value="time"/>
          </form>
        </div>
      );
      expect(output).toEqualJSX(expectedElement);
    });

    it('should call fetchTestToken correctly', () => {
      const { output, props } = setup();
      output.props.children['3'].props.onSubmit({
        target: [{ value: '1'}, { value: '2'}],
        preventDefault: expect.createSpy()
      });
      //expect(output.props.children['3'].props.onSubmit).toEqual("expectedElement");
      expect(props.fetchTestToken).toHaveBeenCalledWith({id: '1'});
    });
  });
});
