import React, {Component} from 'react';
import {Redirect, Route, Switch} from 'react-router-dom';
import {Layout} from 'antd';
import '../../style/index.less';

import SiderCustom from './SiderCustom';
import HeaderCustom from './HeaderCustom';
import noMatch from './404';
import UForm from '../../pages/system/auth/Form'
import Welcome from '../../pages/system/welcome/Welcome'

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

        if (localStorage.getItem("accessToken") === null) {
            return <Redirect to="/login"/>
        }

        return (
            <Layout className="ant-layout-has-sider" style={{height: '100%'}}>
                <SiderCustom />
                <Layout>
                    <HeaderCustom   username={username}/>
                    <Content style={{margin: '0 16px'}}>
                        <Switch>

                            <Route exact path={'/'} component={Welcome} />
                            <Route exact path={'/system/auth'} component={Welcome} />
                            <Route exact path={'/form'} component={UForm} />
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
