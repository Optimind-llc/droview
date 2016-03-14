import React, { Component } from 'react';
// Components
import { Paper } from 'material-ui';
import Colors from 'material-ui/lib/styles/colors';

class Dashboard extends Component {
  render() {
    return (
      <div style={{ minHeight: '600px', background: Colors.blueGrey50}}>
        <section className="content-header">
          <h1>Dashboard</h1>
        </section>
        <section className="content">
          <Paper className="content-wrap" zDepth={1}>
            <div className="box-header with-border">
              <h3 className="box-title">Welcome Admin Istrator!</h3>
              <div className="box-tools pull-right">
                <button className="btn btn-box-tool" data-widget="collapse"><i className="fa fa-minus" /></button>
              </div>
            </div>
            <div className="box-body">
              <p>All the functionality is for show with the exception of the <strong>User Management</strong> to the left. This boilerplate comes with a fully functional access control library to manage users/roles/permissions.</p>
              <p>Keep in mind it is a work in progress and their may be bugs or other issues I have not come across. I will do my best to fix them as I receive them.</p>
            </div>
          </Paper>
        </section>
      </div>
    );
  }
}

export default Dashboard;
