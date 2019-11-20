import React, {Component} from 'react';
import DataTable from 'react-data-table-component';

import './Vendor.css';
import {FetchVendors} from "../../services/FetchVendors";

const columns = [
    {
        name: 'Name',
        selector: 'name',
        sortable: true,
    },

    {
        name: 'Email',
        selector: 'email',
        sortable: false,
    },

    {
        name: 'Status',
        selector: 'status',
        sortable: true,
    },

    {
        name: 'Updated On',
        selector: 'updated_on.date',
        sortable: true,
    },
];

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
                        <h3> Total Records: { this.state.record_count }</h3>
                    </div>

                    <DataTable
                        columns={columns}
                        data={vendors}
                        pagination
                    />
                </div>
            );
        }

    }
}
export default Vendor;