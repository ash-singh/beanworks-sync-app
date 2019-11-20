import React, {Component} from 'react';
import DataTable from 'react-data-table-component';
import {FetchPipelineLogs} from "../../services/FetchPipelineLogs";

const columns = [
    {
        name: 'Item',
        selector: 'item',
        sortable: true,
    },
    {
        name: 'Status',
        selector: 'status',
        sortable: true,
    },

    {
        name: 'Timestamp',
        selector: 'timestamp.date',
        sortable: true,
    },
];

class PipelineLogs extends Component {

    constructor(props) {
        super(props);
        this.state = {
            success_message: '',
            failure_message: '',
            pipeline_id: props.data.id,
            error: null,
            isLoaded: false,
            logs: [],
            record_count: 0
        };
    }

    componentDidMount() {

        FetchPipelineLogs(this.state.pipeline_id)
            .then(
                (result) => {
                    this.setState({
                        isLoaded: true,
                        record_count: result.count,
                        logs: result.data
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
        const {error, isLoaded, logs} = this.state;
        if (error) {
            return (
                <div> Error Loading Logs</div>
            );
        } else if (!isLoaded) {
            return <div>Loading ...</div>
        } else {
            return (
                <DataTable
                    title="Pipeline Logs"
                    columns={columns}
                    data={logs}
                />

            );
        }

    }
}
export default PipelineLogs;