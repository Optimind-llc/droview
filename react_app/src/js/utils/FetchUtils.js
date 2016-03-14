import fetch from 'isomorphic-fetch';
import { constantCase } from 'change-case';
import { _CSRF_TOKEN, _DOMAIN_NAME, _MYPAGE_DOMAIN_NAME } from '../../config/env';
import * as urls from '../../config/url';
import { keyToCamel, keyToSnake } from './ChangeCaseUtils';

function expectStatusCode(method) {
  switch(method) {
    case 'GET':
    case 'PUT':
    case 'PATCH': return 200;
    case 'POST': return 200;
    case 'DELETE': return 200;
  }
}

export function customFetch(url, method, body) {
  let request = {
    method: method,
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-Token': _CSRF_TOKEN
    },
    credentials: 'same-origin',
  };

  if (method !== 'GET' && body) {
    request.body = JSON.stringify(keyToSnake(body))
  }

  return fetch(_DOMAIN_NAME + url, request)
    .then(response => {
      if (response.status === expectStatusCode(method)) {
        return response;
      }
      if (response.status < 200 && response.status >= 300) {
        var error = new Error(response.statusText);
        throw error;
      }
    })
    .then(response => {
      return response.json()
    })
}
