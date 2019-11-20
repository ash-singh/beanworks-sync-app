import React, {Component} from 'react';
import './Home.css';

class Home extends Component {
    render() {
        return (
            <div className="row " id="Body">
                <div className="medium-6 columns">
                    <a href="/account" className="button success">Account</a>
                    <a href="/vendor" className="button success">Vendor</a>
                    <a href="/sync" className="button ">Database Management</a>

                </div>
                <div className="medium-6 columns">
                    <a style={{float: "right"}} href="/logout" className="button ">Signoff</a>
                </div>


            </div>
        );
    }
}
export default Home;