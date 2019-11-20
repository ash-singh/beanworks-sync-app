import React, {Component} from 'react';
import './Vendor.css';
import {FetchVendors} from "../../services/FetchVendors";

class Vendor extends Component {
    constructor(props){
        super(props);
        this.state = {
            error : null,
            isLoaded : false,
            record_count: 0,
            vendors : []
        };
    }

    componentDidMount() {

        FetchVendors()
            .then(
                (result) => {
                    this.setState({
                        isLoaded : true,
                        record_count: result.count,
                        vendors : result.data
                    });
                },
                (error) => {
                    this.setState({
                        isLoaded: true,
                        error
                    })
                },
            );
    }

    render() {
        const {error, isLoaded, vendors} = this.state;

        if (error) {
            return (
                <div className="medium-12 columns">
                    <a href="/home" className="button success">Home</a>
                    <a href="/account" className="button success">Account</a>
                    <a href="/sync" className="button ">Database Management</a>
                    <h2>Vendor</h2>
                    <div> Error Loading</div>
                    <a href="/vendor" className="button success">Refresh</a>
                </div>
            );
        } else if (!isLoaded) {
            return <div>Loading ...</div>
        } else {
            return (
                <div className="row" id="Body">
                    <div className="medium-12 columns">
                        <a href="/home" className="button success">Home</a>
                        <a href="/account" className="button success">Account</a>
                        <a href="/sync" className="button ">Database Management</a>
                        <h2>Vendor</h2>
                        <a href="/vendor" className="button success">Refresh</a>
                        <h3> Total Account: { this.state.record_count }</h3>
                    </div>
                    <ol className="item">
                        {
                            vendors.map(vendor => (
                                <li key={vendor.id} align="start">
                                    <div>
                                        <p className="title">{vendor.name}</p>
                                        <p className="body">{vendor.status}</p>
                                    </div>
                                </li>

                            ))
                        }
                    </ol>
                </div>
            );
        }

    }
}
export default Vendor;