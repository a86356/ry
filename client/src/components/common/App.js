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
import { message } from 'antd';
const {Content, Footer} = Layout;

export default class App extends Component {
    state = {
        collapsed: false,
        username:''
    };
    componentDidMount(){


        //用户信息
        getUserInfo().then(res=>{
            let code =res.code;
            if(code=='000000'){
                this.setState({
                    username: res.data.username,
                });
            }else{
                message.error(res.msg);
            }
        },err=>{
            message.error(err);
        });


    }


    render() {
        const {username} = this.state;
        const {location} = this.props;

        if (localStorage.getItem("accessToken") === null) {
            return <Redirect to="/login"/>
        }

        return (
            <Layout className="ant-layout-has-sider" style={{height: '100%'}}>
                <SiderCustom  path={location.pathname}/>
                <Layout>
                    <HeaderCustom   username={username}/>
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
