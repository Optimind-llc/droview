import * as types from '../constants/PlanActionTypes';
import { CALL_API } from '../middleware/fetchMiddleware';

export function fetchPlans() {
  return {
    [CALL_API]: {
      types: [
        types.REQUEST_PLANS,
        types.REQUEST_PLANS_SUCCESS,
        types.REQUEST_PLANS_FAIL
      ],
      endpoint: '/droview/plans/fetch',
      method: 'GET',
      body: null
    }
  };
}

export function fetchPlan(id) {
  return {
    [CALL_API]: {
      types: [
        types.REQUEST_PLAN,
        types.REQUEST_PLAN_SUCCESS,
        types.REQUEST_PLAN_FAIL
      ],
      endpoint: `/droview/plan/${id}/fetch`,
      method: 'GET',
      body: null
    }
  };
}
