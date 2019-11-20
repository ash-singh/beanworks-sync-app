import React, {Component} from 'react';
import DataTable from 'react-data-table-component';

import './Account.css';
import {FetchAccounts} from "../../services/FetchAccounts";

const columns = [
    {
        name: 'Name',
        selector: 'name',
        sortable: true,
    },
    {
        name: 'Status',
        selector: 'status',
        sortable: true,
    },

    {
        name: 'Bank Account Number',
        selector: 'bank_account_number',
        sortable: false,
    },

    {
        name: 'Bank Account Type',
        selector: 'bank_account_type',
        sortable: false,
    },

    {
        name: 'Updated On',
        selector: 'updated_on.date',
        sortable: true,
    },
];

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
                        <h3> Total Records: { this.state.record_count }</h3>
                    </div>

                    <DataTable
                        title="Account"
                        columns={columns}
                        data={accounts}
                        pagination
                    />

                </div>
            );
        }

    }
}

export default Account;