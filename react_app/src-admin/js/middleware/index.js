import thunk from 'redux-thunk';
import promise from 'redux-promise';
import fetchMiddleware from './fetchMiddleware';
import errorMiddleware from './errorMiddleware';
import updateTimetableMiddleware from './updateTimetableMiddleware';

const Middlewares = [
  fetchMiddleware,
  errorMiddleware,
  updateTimetableMiddleware,
  thunk,
  promise
]

export default Middlewares;
