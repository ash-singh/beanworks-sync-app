import React, {Component} from 'react';

import './Footer.css';

class Footer extends Component {
  render() {
    return (
      <div className="row" id="footer">
        <div className="medium-12 columns">
          <p>(C) Beanworks Accounting in cloud<a href=""> Sync App</a></p>
        </div>
      </div>
    );
  }
}

export default Footer;