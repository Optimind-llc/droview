'use strict'
import http from 'http';

for (var userId = 10; userId >= 0; userId--) {
  for (var flightId = 10; flightId >= 0; flightId--) {
    const URL = `http://l.com/dbtest/${userId}/${flightId}`;

    http.get(URL, (res) => {
      let body = '';
      res.setEncoding('utf8');

      res.on('data', (chunk) => {
          body += chunk;
      });

      res.on('end', (res) => {
          res = JSON.parse(body);
          console.log(res);
      });
    }).on('error', (e) => {
      console.log(e.message);
    });
  };
};