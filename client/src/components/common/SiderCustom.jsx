import React, { Component } from 'react';
import { Layout, Menu, Icon,message } from 'antd';
import { Link } from 'react-router-dom';
import {getMenu} from "../../api/User";

const { Sider } = Layout;
const SubMenu = Menu.SubMenu;




export default class SiderCustom extends Component{
    constructor(props){
        super(props);
        this.state = {
            collapsed: false,
            selectedKey: '', //选择的路径
            openKeys: '', //打开的路径（选择的上一层）
            routes:[],
        }
    }
    componentDidMount() {

        //获得菜单
        getMenu().then(res=>{
            let code =res.code;
            if(code=='000000'){

                this.setState({
                    routes:res.data
                })

            }else{
                message.error(res.msg);
            }
        },err=>{

        })
    }


    menuClick = e => {
        this.setState({
            selectedKey: e.key
        });
    };


    render(){
        const { selectedKey } = this.state;

        const menulist = this.state.routes.map((item) => {
            return (
                <SubMenu key={item.key} title={item.name}>
                    {item.child.map((v2) => {
                        return (
                            <Menu.Item key={v2.key}><Link to={"/app"+v2.router}><span>{v2.name}</span></Link></Menu.Item>
                        )
                    })}
                </SubMenu>
            )
        });
        return(
            <Sider
                trigger={null}
            >
                <div className="logo" style={{backgroundSize:'30%'}}/>

                <Menu
                    theme="dark"
                    mode="inline"
                    selectedKeys={[selectedKey]}
                    onClick={this.menuClick}
                >
                    {menulist}
                </Menu>
            </Sider>
        )
    }
}