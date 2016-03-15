import fetchMiddleware from './fetchMiddleware';
import errorMiddleware from './errorMiddleware';

const Middlewares = [
  fetchMiddleware,
  errorMiddleware,
];

export default Middlewares;
