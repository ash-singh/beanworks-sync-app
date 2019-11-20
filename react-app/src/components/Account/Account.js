import React, {Component} from 'react';
import './Account.css';
import {FetchAccounts} from "../../services/FetchAccounts";

class Account extends Component {

    constructor(props) {
        super(props);
        this.state = {
            error: null,
            isLoaded: false,
            record_count: 0,
            accounts: []
        };
    }

    componentDidMount() {

        FetchAccounts()
            .then(
                (result) => {
                    this.setState({
                        isLoaded: true,
                        record_count: result.count,
                        accounts: result.data
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
        const {error, isLoaded, accounts} = this.state;

        if (error) {
            return (
                <div className="medium-12 columns">
                    <a href="/home" className="button success">Home</a>
                    <a href="/vendor" className="button success">Vendor</a>
                    <a href="/sync" className="button ">Database Management</a>
                    <h2>Account</h2>
                    <div> Error Loading</div>
                    <a href="/account" className="button success">Refresh</a>
                </div>
            );
        } else if (!isLoaded) {
            return <div>Loading ...</div>
        } else {
            return (
                <div className="row" id="Body">
                    <div className="medium-12 columns">
                        <a href="/home" className="button success">Home</a>
                        <a href="/vendor" className="button success">Vendor</a>
                        <a href="/sync" className="button ">Database Management</a>
                        <h2>Account</h2>
                        <a href="/account" className="button success">Refresh</a>
                        <h3> Total Account: { this.state.record_count }</h3>
                    </div>
                    <ol className="item">
                        {
                            accounts.map(account => (
                                <li key={account.id} align="start">
                                    <div>
                                        Name: <p className="title">{account.name}</p>
                                        Status: <p className="body">{account.status}</p>
                                        Bank Account Number: <p className="body">{account.bank_account_number}</p>
                                        Account ID: <p className="body">{account.account_id}</p>
                                        Bank Account Type: <p className="body">{account.bank_account_type}</p>
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

export default Account;