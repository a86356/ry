import React, { Component } from 'react';
import { Router, Route, Switch } from 'react-router-dom';
import history from '../components/common/history';

import App from '../components/common/App';
import Login from '../components/common/Login';
import Test1 from '../components/common/Test1';
import NoMatch from '../components/common/404';



class MRoute extends Component {
  render() {
    return (
      <Router history={history}>
        <Switch>
          <Route exact path="/" component={App}/>
          <Route path="/login" component={Login}/>
          <Route path="/system/auth" component={Test1}/>
          <Route component={NoMatch}/>
        </Switch>
      </Router>
    );
  }
}

export default MRoute;