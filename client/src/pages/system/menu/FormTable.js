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
                dataIndex: 'auth_id',
                width: 50,
            },
            {
                title: '名称',
                dataIndex: 'auth_name',
                width: 80,
            }, {
                title: '模块',
                dataIndex: 'module_name',
                width: 100,
            },{
                title: '控制器',
                dataIndex: 'auth_c',
                width:100,
            },
            {
                title: '方法',
                dataIndex: 'auth_a',
                width:100,
            },
            {
                title: '排序',
                dataIndex: 'sort_order',
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
                className='formTable'
                loading={loading}

            />
        )
    }
}
