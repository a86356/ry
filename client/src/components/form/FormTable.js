import React, { Component } from 'react';
import { Table, Icon, Popconfirm } from 'antd';

export default class FormTable extends Component{
    constructor(props){
        super(props);
    }
    render(){
        const { checkChange, onDelete, editClick, dataSource, loading } = this.props;
        const rowSelection = {
                onChange: checkChange,
        };
        const columns = [
            {
                title: 'id',
                dataIndex: 'menu_id',
                width: 50,
            },
            {
                title: 'pid',
                dataIndex: 'pid',
                width: 50,
            },
            {
            title: '名称',
            dataIndex: 'name',
            width: 80,
        }, {
            title: '键名',
            dataIndex: 'key',
            width: 80,
        }, {
            title: '路由',
            dataIndex: 'router',
            width: 80,
        },{
            title: '排序',
            dataIndex: 'sort',
            width: 50,
        },{
            title: '模块',
            dataIndex: 'module',
            width: 100,
        },{
            title: '控制器',
            dataIndex: 'controller',
            width:100,
        },
            {
                title: '方法',
                dataIndex: 'action',
                width:100,
            },
            {
            title: '操作',
            dataIndex: 'operate',
            width:100,
            render: (text, record) =>
                <div className='opera'>
                    <span onClick={() => editClick(record.key)}>
                         <Icon type="edit" /> 修改
                    </span><br />
                    <span><Popconfirm title="确定要删除吗?" onConfirm={() => onDelete(record.key)}><Icon type="minus-square-o" /> 删除 </Popconfirm></span>
                </div>
        }];
        return(
            <Table
                rowSelection={rowSelection}
                columns={columns}
                dataSource={dataSource}
                bordered={true}
                scroll={{x:'100%'}}
                className='formTable'
                loading={loading}
            />
        )
    }
}
