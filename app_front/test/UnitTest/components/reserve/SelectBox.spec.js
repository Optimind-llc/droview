import React from 'react';
import expect from 'expect';
import TestUtils from 'react-addons-test-utils';
import expectJSX from 'expect-jsx';
expect.extend(expectJSX);
import Icon from 'react-fa';
//components
import SelectBox from '../../../../src/js/components/Reserve/SelectBox';

function setup(propOverrides) {
  const props = Object.assign({
    selector: {
      flightTypes: [
        {id: 1, name: 'type1', en: 'en1', checked: true},
        {id: 2, name: 'type2', en: 'en2', checked: false}
      ],
      places: [
        {id: 1, path: '/path1', active: true, checked: true},
        {id: 2, path: '/path2', active: true, checked: false}
      ],
      week: 0
    },
    isFetching: false,
    fetchTimetableIfNeeded: expect.createSpy()
  }, propOverrides);

  const renderer = TestUtils.createRenderer();

  renderer.render(
    <SelectBox {...props} />
  );

  const output = renderer.getRenderOutput();

  return {
    props: props,
    output: output
  };
}

describe('components', () => {
  describe('SelectBox', () => {
    it('should render components correctly', () => {
      const { output, props } = setup();
      let expectedElement = (
      <form className="select-box" onChange={function noRefCheck() {}}>
        <div className="type clearfix">
          <p id="type">タイプ　<a href=""><Icon Component="span" name="question-circle"/></a></p>
          <div>
            <input checked={true} id="en1" name="type" type="radio" value={1}/>
            <label htmlFor="en1">type1</label>
          </div>
          <div>
            <input checked={false} id="en2" name="type" type="radio" value={2}/>
            <label htmlFor="en2">type2</label>
          </div>
        </div>
        <div className="place clearfix">
          <p id="place">場所　<a href=""><Icon Component="span" name="question-circle"/></a></p>
          <input checked={true} name="place" style={{background: 'url(/path1)', backgroundSize: '150px 100px'}} type="radio" value={1}/>
          <input checked={false} name="place" style={{background: 'url(/path2)', backgroundSize: '150px 100px'}} type="radio" value={2}/>
        </div>
      </form>
      );
      expect(output).toEqualJSX(expectedElement);
    });

    it('should return selected type on radio button is changed', () => {
      const { output, props } = setup();
      output.props.onChange({ target: { checked: true, name: 'type', value:'1' } });
      expect(props.fetchTimetableIfNeeded).toHaveBeenCalledWith(1, null);
    });

    it('should return selected type on radio button is changed', () => {
      const { output, props } = setup();
      output.props.onChange({ target: { checked: true, name: 'place', value:'1' } });
      expect(props.fetchTimetableIfNeeded).toHaveBeenCalledWith(null, 1);
    });
  });
});
