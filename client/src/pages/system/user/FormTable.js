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
                dataIndex: 'user_id',
                width: 50,
            },
            {
                title: '用户名',
                dataIndex: 'username',
                width: 80,
            },{
                title: '昵称',
                dataIndex: 'nickname',
                width:100,
            },
            {
                title: '手机号',
                dataIndex: 'phone',
                width:100,
            },
            {
                title: '管理组',
                dataIndex: 'group_id',
                width:100,
            },
            {
                title: '当前状态',
                dataIndex: 'status',
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
