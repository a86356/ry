import React, { Component } from 'react';
import '../../style/login.less';
import { Form, Icon, Input, Button, Checkbox, message, Spin } from 'antd';

import {success} from "../../api/User";
const FormItem = Form.Item;



class NormalLoginForm extends Component {
    state = {
        isLoding:false,
    };
    handleSubmit = (e) => {
        e.preventDefault();
        this.props.form.validateFields((err, values) => {

            if (!err) {

                success(values).then(res=>{
                    let code =res.code;
                    if(code=='0'){
                        this.setState({
                            isLoding: true,
                        });

                        localStorage.setItem('token',"aaaaaa");

                        message.success('login successed!'); //成功信息
                        let that = this;
                        setTimeout(function() { //延迟进入
                            that.props.history.push({pathname:'/app',state:values});
                        }, 1000);
                    }else{
                        message.error(res.msg);
                    }
                },err=>{
                    message.error('login failed!'); //失败信息
                });
            }
        });
    };

    render() {
        const { getFieldDecorator } = this.props.form;
        return (
            this.state.isLoding?<Spin size="large" className="loading" />:
            <div className="login">
                <div className="login-form">
                    <div className="login-logo">
                        <div className="login-name">SPA</div>
                    </div>
                    <Form onSubmit={this.handleSubmit} style={{maxWidth: '300px'}}>
                        <FormItem>
                            {getFieldDecorator('username', {
                                rules: [{ required: true, message: '请输入用户名!' }],
                            })(
                                <Input  prefix={<Icon type="user" style={{ fontSize: 13 }} />} placeholder="用户名" />
                            )}
                        </FormItem>
                        <FormItem>
                            {getFieldDecorator('password', {
                                rules: [{ required: true, message: '请输入密码!' }],
                            })(
                                <Input prefix={<Icon type="lock" style={{ fontSize: 13 }} />} type="password" placeholder="密码" />
                            )}
                        </FormItem>
                        <FormItem style={{marginBottom:'0'}}>
                            <Button type="primary" htmlType="submit" className="login-form-button" style={{width: '100%'}}>
                                登录
                            </Button>
                        </FormItem>
                    </Form>
                </div>
            </div>
        );
    }
}

const Login = Form.create()(NormalLoginForm);
export default Login;