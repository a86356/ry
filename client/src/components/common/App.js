import React, {Component} from 'react';
import {Redirect, Route, Switch} from 'react-router-dom';
import {Layout} from 'antd';
import '../../style/index.less';

import SiderCustom from './SiderCustom';
import HeaderCustom from './HeaderCustom';
import UForm from '../form/Form';
import noMatch from './404';
import UserIndex from '../../pages/system/user/User'

import {getUserInfo} from "../../api/User";

const {Content, Footer} = Layout;

export default class App extends Component {
    state = {
        collapsed: false,
        username:''
    };
    componentDidMount(){


        getUserInfo().then(res=>{

        },err=>{

        });


    }


    render() {
        const {collapsed} = this.state;
        const {location} = this.props;
        let name;
        if (localStorage.getItem("token") === null) {
            return <Redirect to="/login"/>
        } else {
         //   name = location.state === undefined ? JSON.parse(localStorage.getItem("mspa_user")).username : location.state.username;
            name = 'admin';
        }

        return (
            <Layout className="ant-layout-has-sider" style={{height: '100%'}}>
                <SiderCustom collapsed={collapsed} path={location.pathname}/>
                <Layout>
                    <HeaderCustom collapsed={collapsed} toggle={this.toggle} username={name}/>
                    <Content style={{margin: '0 16px'}}>
                        <Switch>
                            <Route exact path={'/app'} component={UForm} />
                            <Route exact path={'/app/form'} component={UForm} />
                            <Route exact path={'/app/system/user/index'} component={UserIndex} />
                            <Route component={noMatch} />
                        </Switch>
                    </Content>
                    <Footer style={{textAlign: 'center'}}>
                        SPA ©2017-2018 Created by WZ
                    </Footer>
                </Layout>
            </Layout>
        );
    }
}
