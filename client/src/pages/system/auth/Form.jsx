import React, { Component } from 'react';
import './form.less';

import axios from 'axios';
import Mock from 'mockjs';
import moment from 'moment';
import { Row, Col, Input, Icon, DatePicker, Button, Popconfirm,message } from 'antd';

import BreadcrumbCustom from 'components/common/BreadcrumbCustom';
import address from './request/address.json';
import data from './request/data.json';
import CollectionCreateForm from './CustomizedForm';
import FormTable from './FormTable';

import {getAuthList,updateAddAuth} from "api/system";
import {getAuthList} from "../../../api/system";
import {getMenu} from "../../../api/User";

const Search = Input.Search;
const InputGroup = Input.Group;
const options = [];
const { RangePicker } = DatePicker;
Mock.mock('/address', address);
Mock.mock('/data', data);

//数组中是否包含某项
function isContains(arr, item){
    arr.map(function (ar) {
        if(ar === item){
            return true;
        }
    });
    return false;
}
//找到对应元素的索引
function catchIndex(arr, key){ //获取INDEX
    var i=0;
    arr.map(function (ar, index) {
        if(ar.auth_id==key){
            i=index;
        }
    });
    return i;
}
//替换数组的对应项
function replace(arr, item, place){ //arr 数组,item 数组其中一项, place 替换项
    arr.map(function (ar) {
        if(ar.key === item){
            arr.splice(arr.indexOf(ar),1,place)
        }
    });
    return arr;
}

export default class UForm extends Component{
    constructor(props) {
        super(props);
        this.state = {
            userName: '',
            address: '',
            timeRange: '',
            visible: false, //新建窗口隐藏
            dataSource: [],
            count: data.length,
            selectedRowKeys: [],
            selectedRows:[],
            tableRowKey: 0,
            isUpdate: false,
            loading: true,
            page:1,
        };
    }
    //getData
    getData = () => {
        getAuthList({page:this.state.page,name:this.state.userName}).then(res=>{
            let code =res.code;
            if(code=='000000'){

                var list=res.data.list;

                for(let i=0;i<list.length;i++){
                    list[i]['key']=list[i]['auth_id']
                }
                this.setState({
                    dataSource: list,
                    loading:false
                })
            }else{
                message.error(res.msg);
            }
        },err=>{
            message.error(err);
        });

    };


    //用户名输入
    onChangeUserName = (e) => {
        const value = e.target.value;
        this.setState({
            userName: value,
        })
    };


    //渲染
    componentDidMount(){
        this.getData();
    }
    //搜索按钮
    btnSearch_Click = () => {
        this.getData();
    };

    //新建信息弹窗
    CreateItem = () => {
        this.setState({
            visible: true,
            isUpdate: false,
        });
        const form = this.form;
        form.resetFields();
    };
    //接受新建表单数据
    saveFormRef = (form) => {
        this.form = form;
    };
    //填充表格行
    handleCreate = () => {
        const { dataSource, count } = this.state;
        const form = this.form;
        form.validateFields((err, values) => {

            if (err) {
                return;
            }

            values.key = count;
            values.address = values.address.join(" / ");
            values.createtime = moment().format("YYYY-MM-DD hh:mm:ss");

            form.resetFields();
            this.setState({
                visible: false,
                dataSource: [...dataSource, values],
                count: count+1,
            });
        });
    };
    //取消
    handleCancel = () => {
        this.setState({ visible: false });
    };
    //批量删除
    MinusClick = () => {
        const { dataSource, selectedRowKeys } = this.state;

        console.log(selectedRowKeys);
    };
    //单个删除
    onDelete = (key) => {
        console.log(key)
    };
    //点击修改
    editClick = (key) => {

        const form = this.form;
        const { dataSource } = this.state;

        const index = catchIndex(dataSource, key);

        form.setFieldsValue({
            auth_id: key,
            auth_name: dataSource[index].auth_name,
            module_name: dataSource[index].module_name,
            auth_c: dataSource[index].auth_c,
            auth_a: dataSource[index].auth_a,
            sort_order: dataSource[index].sort_order,
        });
        this.setState({
            visible: true,
            tableRowKey: key,
            isUpdate: true,
        });
    };
    //更新修改
    handleUpdate = () => {
        const form = this.form;
     //   const { dataSource, tableRowKey } = this.state;
        form.validateFields((err, values) => {
            if (err) {
                return;
            }

            updateAddAuth(values).then(res=>{
              let code =res.code;
              if(code=='000000'){

                  var list=res.data.list;

                  for(let i=0;i<list.length;i++){
                      list[i]['key']=list[i]['auth_id']
                  }
                  this.setState({
                      visible: false,
                  });
              }else{
                  message.error(res.msg);
              }
          },err=>{
              message.error(err);
          });



        });
    };
    //单选框改变选择
    checkChange = (selectedRowKeys, selectedRows) => {

        this.setState({
            selectedRowKeys: selectedRowKeys,
            selectedRows: selectedRows,
        });

    };
    render(){
        const { userName, address, timeRange, dataSource, visible, isUpdate, loading } = this.state;

        return(
            <div>
                <BreadcrumbCustom paths={['系统管理','权限管理']}/>
                <div className='formBody'>
                    <Row gutter={16}>
                        <Col className="gutter-row" sm={8}>
                            <Search
                                placeholder="Input Name"
                                prefix={<Icon type="user" />}
                                value={userName}
                                onChange={this.onChangeUserName}
                            />
                        </Col>
                    </Row>
                    <Row gutter={16}>
                        <div className='plus' onClick={this.CreateItem}>
                            <Icon type="plus-circle" />
                        </div>
                        <div className='minus'>
                            <Popconfirm title="确定要批量删除吗?" onConfirm={this.MinusClick}>
                                <Icon type="minus-circle" />
                            </Popconfirm>
                        </div>
                        <div className='btnOpera'>
                            <Button type="primary" onClick={this.btnSearch_Click} style={{marginRight:'10px'}}>查询</Button>
                        </div>
                    </Row>
                    <FormTable
                        dataSource={dataSource}
                        checkChange={this.checkChange}
                        onDelete={this.onDelete}
                        editClick={this.editClick}
                        loading={loading}
                    />
                    {isUpdate?
                        <CollectionCreateForm ref={this.saveFormRef} visible={visible} onCancel={this.handleCancel} onCreate={this.handleUpdate} title="修改信息" okText="更新"
                    /> : <CollectionCreateForm ref={this.saveFormRef} visible={visible} onCancel={this.handleCancel} onCreate={this.handleCreate} title="新建信息" okText="创建"
                    />}
                </div>
            </div>
        )
    }
}