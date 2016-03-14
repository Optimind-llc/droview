import * as types from '../constants/PlanActionTypes';

const initialState = {
  plans: [],
  isFetching: false,
  didInvalidate: false,
  updatedAt: 0,
}

export default function plans(state = initialState , action) {
  switch (action.type) {
    case types.REQUEST_PLANS:
    case types.REQUEST_PLAN:
    case types.CREATE_PLAN:
    case types.UPDATE_PLAN:
    case types.ACTIVATE_PLAN:
    case types.DEACTIVATE_PLAN:
    case types.DELETE_PLAN:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case types.REQUEST_PLANS_SUCCESS:
    case types.REQUEST_PLAN_SUCCESS:
    case types.CREATE_PLAN_SUCCESS:
    case types.UPDATE_PLAN_SUCCESS:
    case types.ACTIVATE_PLAN_SUCCESS:
    case types.DEACTIVATE_PLAN_SUCCESS:
    case types.DELETE_PLAN_SUCCESS:
      const { type, payload: {plans}, meta:{timestamp} } = action;
      let nextPlans = [];

      if (timestamp < state.updatedAt) {
        return state;
      }

      if (type === types.REQUEST_PLAN_SUCCESS) {
        nextPlans = state.plans.filter(plan => 
          plans.map(p => p.id).indexOf(plan.id) === -1
        ).concat(plans);
      } else {
        nextPlans = plans;
      }

      return Object.assign({}, state, {
        plans: nextPlans.sort((a, b) =>　a.place.id - b.place.id),
        isFetching: false,
        didInvalidate: false,
        updatedAt: timestamp
      });

    case types.REQUEST_PLANS_FAIL:
    case types.REQUEST_PLAN_FAIL:
    case types.CREATE_PLAN_FAIL:
    case types.UPDATE_PLAN_FAIL:
    case types.ACTIVATE_PLAN_FAIL:
    case types.DEACTIVATE_PLAN_FAIL:
    case types.DELETE_PLAN_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}
