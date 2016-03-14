import {
	REQUEST_PLANS,
	REQUEST_PLANS_SUCCESS,
	REQUEST_PLANS_FAIL,
} from '../../constants/ActionTypes';

const reshape = p => {
  p.open = p.flights.length;
  p.reserved = p.flights.reduce((prev, flight) => {
    return prev + Number(flight.users);
  }, 0);
  delete p.flights;
  return p;
}

const convert = (prev, plan) => {
  const typeName = plan.type.name;
  const planId = plan.place.id;
  prev[typeName] ? prev[typeName].push(plan) : prev[typeName] = [plan];
  return prev;
}

const initialState = {
  isFetching: false,
  didInvalidate: false,
  plans: {}
}

export default function plans(state = initialState , action) {
  switch (action.type) {
    case REQUEST_PLANS:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REQUEST_PLANS_SUCCESS:
      const { plans } = action.payload;
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: false,
        plans: plans.map(reshape).reduce(convert, {})
      });

    case REQUEST_PLANS_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}
