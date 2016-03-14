import {
  ADD_SIDE_ALERT,
  DELETE_SIDE_ALERT,
} from '../constants/ActionTypes';

function change(state = [], action) {
  switch (action.type) {
    case ADD_SIDE_ALERT:
      return [
        ...state,
        {
          key: Date.now(),
          data: {
            status: action.status,
            messageId: action.messageId,
            value: action.value || ''    
          }
        }
      ]

    case DELETE_SIDE_ALERT:
      return state.filter(alert =>
        action.keys.indexOf(alert.key) === -1
      )

    default:
      return state;
  }
}

const initialState = {
  side: []
};

export default function alert(state = initialState, action) {
  switch (action.type) {
    case ADD_SIDE_ALERT:
    case DELETE_SIDE_ALERT:
      return Object.assign({}, state, {
        side: change(state.side, action)
      });

    default:
      return state;
  }
}



// function change(state = [], action) {
//   switch (action.type) {
//     case ADD_SIDE_ALERT:
//       return Object.assign({}, state, {
//         [Date.now()]: {
//           status: action.status,
//           messageId: action.messageId,
//           value: action.value
//         }
//       });

//     case DELETE_SIDE_ALERT:
//       return Object.keys(state)
//       .filter(key => action.keys.indexOf(key) === -1)
//       .reduce((alerts, key) => {
//         alerts[key] = {
//           status: state[key].status,
//           messageId: state[key].messageId,
//           value: state[key].value
//         };
//         return alerts;
//       }, {});

//     default:
//       return state;
//   }
// }