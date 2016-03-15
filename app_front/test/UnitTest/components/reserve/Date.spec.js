import expect from 'expect';
import React from 'react';
import TestUtils from 'react-addons-test-utils';
import Date from '../../../../src/js/components/Reserve/Date';

function setup(propOverrides) {
  const props = Object.assign({
    className: "rsv reserved",
    dateNodes: "10月10日(日)"
  }, propOverrides);

  const renderer = TestUtils.createRenderer();

  renderer.render(
    <Date {...props} />
  );

  const output = renderer.getRenderOutput();

  return {
    props: props,
    output: output,
    renderer: renderer
  };
}

describe('components', () => {
  describe('Date', () => {
    it('should render date correctly', () => {
      const { output, props } = setup();

      expect(output.props.className).toBe(props.className);
      expect(output.props.children.props.children).toBe(props.dateNodes);
    });
  });
});
