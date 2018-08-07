import React, { Component } from 'react';
import { Modal, Form, Input } from 'antd';
import { Select } from 'antd';

const Option = Select.Option;

const FormItem = Form.Item;

class CustomizedForm extends Component{
    state = {

    };
    constructor(props){
        super(props);
    }
    componentDidMount(){

    }


   updateInput(isUpdate){
       if(isUpdate){
           return(
               <Input  readOnly/>
           )
       }else{
           return(
               <Input />
           )
       }
   }

    render(){
        const { visible, onCancel, onCreate, form, okText, title,isUpdate } = this.props;
        const { getFieldDecorator } = form;
        const FormItemLayout = {
            labelCol: { span: 5 },
            wrapperCol: { span: 16 },
        };

        const  updateInput=this.updateInput(isUpdate);
        return (
            <Modal
                visible={visible}
                title={title}
                okText={okText}
                onCancel={onCancel}
                onOk={onCreate}
            >
                <Form layout="horizontal">
                    <FormItem label="用户id" {...FormItemLayout} >
                        {getFieldDecorator('user_id', {

                        })(
                            <Input  readOnly/>
                        )}
                    </FormItem>

                    <FormItem label="用户名" {...FormItemLayout} >
                        {getFieldDecorator('username', {
                            rules: [{ required: true, message: '请输入用户名' }],
                        })(
                            updateInput
                        )}
                    </FormItem>
                    <FormItem label="密码" {...FormItemLayout} >
                        {getFieldDecorator('password', {
                            rules: [{ required: true, message: '请输入密码' }],
                        })(
                            updateInput
                        )}
                    </FormItem>
                    <FormItem label="昵称" {...FormItemLayout} >
                        {getFieldDecorator('nickname', {
                            rules: [{ required: true, message: '请输入昵称' }],
                        })(
                            <Input />
                        )}
                    </FormItem>
                    <FormItem label="手机号" {...FormItemLayout} >
                        {getFieldDecorator('phone', {
                            rules: [{ required: true, message: '请输入手机号' }],
                        })(
                            <Input />
                        )}
                    </FormItem>
                    <FormItem label="管理组" {...FormItemLayout} >
                        {getFieldDecorator('group_id', {
                           // rules: [{ required: true, message: '请输入方法！' }],
                        })(
                            <Select defaultValue="超级管理员" >
                                <Option value="1">超级管理员</Option>
                            </Select>
                        )}
                    </FormItem>
                    <FormItem label="状态" {...FormItemLayout} >
                        {getFieldDecorator('status', {
                            rules: [{ required: true, message: '请输入状态值' }],
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