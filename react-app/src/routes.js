import React from 'react';
import {BrowserRouter,  Route, Redirect, Switch} from 'react-router-dom';

import Welcome from '././components/Welcome/Welcome';
import Home from '././components/Home/Home';
import Login from '././components/Login/Login';
import Signup from '././components/Signup/Signup';
import NotFound from '././components/NotFound/NotFound';
import Account from '././components/Account/Account'
import Vendor from '././components/Vendor/Vendor'
import Sync from '././components/Sync/Sync'
import Logout from '././components/Logout/Logout'


const Routes = () => (
    <BrowserRouter >
        <Switch>
            <Route exact path="/" component={Welcome}/>
            <Route path="/home" component={Home}/>
            <Route path="/login" component={Login}/>
            <Route path="/signup" component={Signup}/>
            <Route path="/account" component={Account}/>
            <Route path="/vendor" component={Vendor}/>
            <Route path="/sync" component={Sync}/>
            <Route path="/logout" component={Logout}/>
            <Route path="*" component={NotFound}/>
        </Switch>
    </BrowserRouter>
);

export default Routes;