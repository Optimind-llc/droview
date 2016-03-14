import fetch from 'isomorphic-fetch';
import { _CSRF_TOKEN, _DOMAIN_NAME, _ADMIN_DOMAIN_NAME } from '../../config/env';
import { keyToSnake } from './ChangeCaseUtils';
import { camelizeKeys } from 'humps'

function expectStatusCode(method) {
  switch (method) {
    case 'GET':
    case 'PUT':
    case 'PATCH': return 200;
    case 'POST': return 200;
    case 'DELETE': return 200;
    default: return 200;
  }
}

export function customFetch(url, method, body) {
  const request = {
    method,
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-Token': _CSRF_TOKEN
    },
    credentials: 'same-origin',
  };

  if (method !== 'GET' && body) {
    request.body = JSON.stringify(keyToSnake(body));
  }

  return fetch(_DOMAIN_NAME + _ADMIN_DOMAIN_NAME + url, request)
    .then(response => {
      if (response.status === expectStatusCode(method)) {
        return response;
      }
      if (response.status >= 400) {
        const error = new Error(response.statusText);
        throw error;
      }
    })
    .then(response => response.json());
}



export function callApi(endpoint, method, body) {
  const request = {
    method,
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-Token': _CSRF_TOKEN
    },
    credentials: 'same-origin',
  };

  if (method !== 'GET' && body) {
    request.body = JSON.stringify(keyToSnake(body));
  }

  return fetch(_DOMAIN_NAME + _ADMIN_DOMAIN_NAME + endpoint, request)
    .then(response => 
      response.json().then(json => ({ json, response }))
    )
    .then(({ json, response }) => {
      if (!response.ok) {
        return Promise.reject(json);
      }

      return camelizeKeys(json);
    })
}
