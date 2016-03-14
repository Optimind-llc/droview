import {
  SET_TYPE_STATUS,
  CHANGE_TYPE_CHECKED,
  SET_PLACE_STATUS,
  CHANGE_PLACE_ACTIVE,
  CHANGE_PLACE_CHECKED,
  CHANGE_WEEK
} from '../constants/ActionTypes';

const initialState = {
  flightTypes: [
    {id: null, name: '', en: '', checked: true},
    {id: null, name: '', en: '', checked: false},
    {id: null, name: '', en: '', checked: false}
  ],
  places: [
    {id: null, path: '', active: false, checked: true},
    {id: null, path: '', active: false, checked: false},
    {id: null, path: '', active: false, checked: false},
    {id: null, path: '', active: false, checked: false}
  ],
  week: 0
};

export default function selector(state = initialState, action) {
  switch (action.type) {
  case SET_TYPE_STATUS:
    return {
      flightTypes: action.status.map(t => Object.assign({}, t, { checked: false })),
      places: [...state.places],
      week: state.week
    };

  case CHANGE_TYPE_CHECKED:
    return {
      flightTypes: state.flightTypes.map(t =>
          Number(t.id) === action.id ?
          Object.assign({}, t, { checked: true }) :
          Object.assign({}, t, { checked: false })
        ),
      places: [...state.places],
      week: state.week
    };

  case SET_PLACE_STATUS:
    return {
      flightTypes: [...state.flightTypes],
      places: action.status.map(p => Object.assign({}, p, { active: true, checked: false})),
      week: state.week
    };

  case CHANGE_PLACE_ACTIVE:
    return {
      flightTypes: [...state.flightTypes],
      places: state.places.map(p =>
          action.ids.some(i => Number(p.id) === i) ?
          Object.assign({}, p, { active: true }) :
          Object.assign({}, p, { active: false })
        ),
      week: state.week
    };

  case CHANGE_PLACE_CHECKED:
    return {
      flightTypes: [...state.flightTypes],
      places: state.places.map(p =>
          Number(p.id) === action.id ?
          Object.assign({}, p, { checked: true }) :
          Object.assign({}, p, { checked: false })
        ),
      week: state.week
    };

  case CHANGE_WEEK:
    return {
      flightTypes: [...state.flightTypes],
      places: [...state.places],
      week: action.week
    };

  default:
    return state;
  }
}
