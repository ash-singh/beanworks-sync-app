import React, {Component} from 'react';
import DataTable from 'react-data-table-component';
import './Sync.css';
import {CreatePipeline} from "../../services/CreatePipeline";
import {FetchPipelines} from "../../services/FetchPipelines";

const columns = [
    {
        name: 'Operation',
        selector: 'operation',
        sortable: true,
    },
    {
        name: 'Status',
        selector: 'status',
        sortable: true,
    },

    {
        name: 'Total',
        selector: 'total_count',
        sortable: false,
    },

    {
        name: 'Failed',
        selector: 'failed_count',
        sortable: false,
    },

    {
        name: 'Success',
        selector: 'success_count',
        sortable: false,
    },

    {
        name: 'DateTime',
        selector: 'created_on.date',
        sortable: true,
    },
];

class Sync extends Component {

    constructor(props) {
        super(props);
        this.state = {
            success_message: '',
            failure_message: '',
            error: null,
            isLoaded: false,
            pipelines: [],
            record_count: 0
        };
        this.createPipeline = this.createPipeline.bind(this);
    }

    componentDidMount() {

        FetchPipelines()
            .then(
                (result) => {
                    this.setState({
                        isLoaded: true,
                        record_count: result.count,
                        pipelines: result.data
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

    createPipeline() {
        CreatePipeline(this.state).then((result) => {
            let responseJson = result;
            if (responseJson.status === "OK" && responseJson.message) {
                this.setState({success_message: responseJson.message});
                this.forceUpdate();
            } else {
                this.setState({failure_message: responseJson.message});
            }
        });
    }
    render() {
        const {error, isLoaded, pipelines} = this.state;
        if (error) {
            return (
                <div className="medium-12 columns">
                    <a href="/home" className="button success">Home</a>
                    <a href="/account" className="button success">Account</a>
                    <a href="/vendor" className="button success">Vendor</a>
                    <h2>Sync Pipeline</h2>
                    <div> Error Loading</div>
                    <a href="/sync" className="button success">Refresh</a>
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
                        <a href="/vendor" className="button success">Vendor</a>
                        <div className="medium-12 columns">
                            <h2>Sync data from Xero Organization</h2>
                            <a href="/sync" className="button success">Refresh</a>

                            <input type="submit" className="button" value="Sync Data Now" onClick={this.createPipeline}/>

                            <div className="error">{ this.state.failure_message } </div>
                            <div className="success">{ this.state.success_message } </div>
                        </div>

                    </div>


                    <DataTable
                        title="Data Sync Pipelines"
                        columns={columns}
                        data={pipelines}
                        pagination
                    />
                </div>
            );
        }

    }
}
export default Sync;