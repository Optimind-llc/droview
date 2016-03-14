import expect from 'expect';
import * as types from '../../../src/js/constants/ActionTypes';
import * as actions from '../../../src/js/actions/reservation';

describe('reservation actions', () => {
  it('modalOff should create MODAL_OFF action', () => {
    expect(actions.modalOff()).toEqual({
      type: types.MODAL_OFF
    });
  });
});