import React, { Component } from 'react';
import { Modal, Form, Input, Radio, InputNumber, Cascader, Select, AutoComplete } from 'antd';

const FormItem = Form.Item;
const options = [];

class CustomizedForm extends Component{
    state = {
    };
    constructor(props){
        super(props);
    }
    componentDidMount(){

    }
    render(){
        const { visible, onCancel, onCreate, form, okText, title } = this.props;
        const { getFieldDecorator } = form;
        const FormItemLayout = {
            labelCol: { span: 5 },
            wrapperCol: { span: 16 },
        };

        return (
            <Modal
                visible={visible}
                title={title}
                okText={okText}
                onCancel={onCancel}
                onOk={onCreate}
            >
                <Form layout="horizontal">
                    <FormItem label="名称" {...FormItemLayout} >
                        {getFieldDecorator('name', {
                            rules: [{ required: true, message: '请输入名称！' }],
                        })(
                            <Input />
                        )}
                    </FormItem>
                    <FormItem label="模块" {...FormItemLayout} >
                        {getFieldDecorator('module', {
                            rules: [{ required: true, message: '请输入模块！' }],
                        })(
                            <Input />
                        )}
                    </FormItem>
                    <FormItem label="控制器" {...FormItemLayout} >
                        {getFieldDecorator('controller', {
                            rules: [{ required: true, message: '请输入控制器！' }],
                        })(
                            <Input />
                        )}
                    </FormItem>
                    <FormItem label="方法" {...FormItemLayout} >
                        {getFieldDecorator('address', {
                            rules: [{ required: true, message: '请选择地址！' }],
                        })(
                            <Cascader options={options}/>
                        )}
                    </FormItem>
                    <FormItem label="手机号" {...FormItemLayout} >
                        {getFieldDecorator('phone', {
                            rules: [{
                                pattern: /^1(3|4|5|7|8)\d{9}$/, message: "手机号码格式不正确！"
                            },{
                                required: true, message: '请输入手机号！'
                            }],
                        })(
                            <Input  style={{ width: '100%' }} />
                        )}
                    </FormItem>
                    <FormItem label="邮箱" {...FormItemLayout} >
                        {getFieldDecorator('email', {
                            rules: [{
                                type: 'email', message: '邮箱格式不正确！',
                            }, {
                                required: true, message: '请输入邮箱！',
                            }],
                        })(
                            <Input />
                        )}
                    </FormItem>

                </Form>
            </Modal>
        );
    }
}

const CollectionCreateForm = Form.create()(CustomizedForm);
export default CollectionCreateForm;