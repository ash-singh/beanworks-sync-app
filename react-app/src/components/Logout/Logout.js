import React, {Component} from 'react';
import {Redirect} from 'react-router-dom';

class Logout extends Component {

    render() {
        sessionStorage.clear('token');
        return (<Redirect to={'/login'}/>);
    }
}

export default Logout;