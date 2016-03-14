import React from 'react';
import expect from 'expect';
import TestUtils from 'react-addons-test-utils';
import SelectDate from '../../../../src/js/components/Reserve/SelectDate';

function setup(propOverrides) {
  const props = Object.assign({
    isFetching: false,
    fetchTimetableIfNeeded: expect.createSpy()
  }, propOverrides);

  const renderer = TestUtils.createRenderer();

  renderer.render(
    <SelectDate {...props} />
  );

  const output = renderer.getRenderOutput();

  return {
    props: props,
    output: output,
  };
}

describe('components', () => {
  describe('SelectDate', () => {
    it('should render components correctly', () => {
      const { output, props } = setup();
      let expectedElement = (
        <div className="page-nation clearfix" onClick={function noRefCheck() {}}>
          <div className="btn-buck"/>
          <div className="btn-next" />
        </div>
      );
      expect(output).toEqualJSX(expectedElement);
    });

    it('should return 1 on next button clicked', () => {
      const { output, props } = setup();
      output.props.onClick({ target: { className: 'btn-next' } });
      expect(props.fetchTimetableIfNeeded).toHaveBeenCalledWith(null, null, 1);
    });

    it('should return -1 on buck button clicked', () => {
      const { output, props } = setup();
      output.props.onClick({ target: { className: 'btn-buck' } });
      expect(props.fetchTimetableIfNeeded).toHaveBeenCalledWith(null, null, -1);
    });
  });
});
