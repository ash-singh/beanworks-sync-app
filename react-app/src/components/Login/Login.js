import React, {Component} from 'react';
import {Redirect} from 'react-router-dom';
import './Login.css';
import {LoginApi} from '../../services/LoginApi';

class Login extends Component {

    constructor() {
        super();
        this.state = {
            email: '',
            password: '',
            message: '',
            redirectToReferrer: false
        };

        this.login = this.login.bind(this);
        this.onChange = this.onChange.bind(this);

    }

    login() {
        if (this.state.email && this.state.password) {
            LoginApi(this.state).then((result) => {
                let responseJson = result;
                if (responseJson.status === "OK" && responseJson.data.token) {
                    sessionStorage.setItem('token', JSON.stringify(responseJson.data.token));
                    this.setState({redirectToReferrer: true});
                } else {
                    this.setState({message: responseJson.message});
                }
            });
        }
    }

    onChange(e) {
        this.setState({[e.target.name]: e.target.value});
        this.setState({message: ''});
    }

    render() {
        if (this.state.redirectToReferrer || sessionStorage.getItem('token')) {
            return (<Redirect to={'/home'}/>)
        }

        return (
            <div className="row" id="Body">
                <div className="error">{ this.state.message } </div>
                <div className="medium-5 columns left">
                    <h4>Login</h4>
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email" onChange={this.onChange}/>
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Password" onChange={this.onChange}/>
                    <input type="submit" className="button success" value="Sign-in" onClick={this.login}/>
                </div>
            </div>
        );
    }
}

export default Login;