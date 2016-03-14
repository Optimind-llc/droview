if (process.env.NODE_ENV === 'development') {
  //console.log(process.env.NODE_ENV);	
  module.exports = require('./dev');
} else {
  module.exports = require('./prod');
}
