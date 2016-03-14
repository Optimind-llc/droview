import React, { PropTypes, Component } from 'react';

class TimetableColumns extends Component {
  handleSubmit(e) {
    e.preventDefault();
    const { fetchTestToken } = this.props;
    fetchTestToken({id: e.target[0].value});
  }

  render() {
    const { columns } = this.props;
    const renderColumns = columns.map((t, i) => {
      if (t.c === '0'){
        return <div className="rsv rsv-close" key={i}><p>ー</p></div>
      } else if (t.c === '1') {
        return <div className="rsv rsv-reserved" key={i}><p>予約済み</p></div>
      } else if (t.c === '2') {
        return <div className="rsv rsv-myreservation" key={i}><p>自分の予約</p></div>
      } else if (t.c === '3') {
        return (
          <form onSubmit={this.handleSubmit.bind(this)} key={i}>
            <input type="hidden" name="id" value={ t.id } />
            <input type="submit" value={ t.t } className="rsv rsv-open"/>
          </form>
        )
      }
    })

    return (
      <div className="time-table-colmuns">
        {renderColumns}
      </div>
    );
  }
}

TimetableColumns.propTypes = {
  columns: PropTypes.array.isRequired,
  fetchTestToken: PropTypes.func.isRequired
};

export default TimetableColumns;
