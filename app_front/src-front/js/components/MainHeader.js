import React, { Component } from 'react';

class MainHeader extends Component {
  render() {
    return (
      <header>
        {/* navbar */}
        <nav id="mainNav" className="navbar navbar-inverse">
          <div className="container" id="sampleScrollSpy">
            <div className="navbar-header">
              <button type="button" className="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span className="sr-only">メニュー</span>
                <span className="icon-bar"></span>
                <span className="icon-bar"></span>
                <span className="icon-bar"></span>
              </button>
              <a className="navbar-brand linkInThePage" href="http://l.com">
                Droview
              </a>
            </div>
            <div id="navbar" className="navbar-collapse collapse">
              <ul className="nav navbar-nav navbar-left">
                <li><a className="linkInThePage" href="http://l.com/logout">ログアウト</a></li>
              </ul>
            </div>
          </div>
        </nav>
      </header>
    );
  }
}

export default MainHeader;
