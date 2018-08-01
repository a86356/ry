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
                    <FormItem label="权限id" {...FormItemLayout} >
                        {getFieldDecorator('auth_id', {
                            rules: [{ required: true, message: '请输入权限id！' }],
                        })(
                            <Input  readOnly />
                        )}
                    </FormItem>

                    <FormItem label="名称" {...FormItemLayout} >
                        {getFieldDecorator('auth_name', {
                            rules: [{ required: true, message: '请输入名称！' }],
                        })(
                            <Input />
                        )}
                    </FormItem>
                    <FormItem label="模块" {...FormItemLayout} >
                        {getFieldDecorator('module_name', {
                            rules: [{ required: true, message: '请输入模块' }],
                        })(
                            <Input />
                        )}
                    </FormItem>
                    <FormItem label="控制器" {...FormItemLayout} >
                        {getFieldDecorator('auth_c', {
                            rules: [{ required: true, message: '请输入控制器！' }],
                        })(
                            <Input />
                        )}
                    </FormItem>
                    <FormItem label="方法" {...FormItemLayout} >
                        {getFieldDecorator('auth_a', {
                            rules: [{ required: true, message: '请输入方法！' }],
                        })(
                            <Input />
                        )}
                    </FormItem>
                    <FormItem label="排序" {...FormItemLayout} >
                        {getFieldDecorator('sort_order', {
                            rules: [{ required: true, message: '请输入排序数字！' }],
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