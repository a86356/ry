import React, { Component } from 'react';
import { Layout, Menu, Icon } from 'antd';
import { Link } from 'react-router-dom';

const { Sider } = Layout;
const SubMenu = Menu.SubMenu;

export default class SiderCustom extends Component{
    constructor(props){
        super(props);
        this.state = {
            collapsed: false,
            firstHide: true, //第一次先隐藏暴露的子菜单
            selectedKey: '', //选择的路径
            openKey: '', //打开的路径（选择的上一层）
            routes:[
                {
                    id:'0',
                    title:'测试父亲',
                    path:'/app/system/user/index',
                    child:[{
                        id:'1',
                        title:'测试儿子',
                        path:'/app/system/user/index'
                    }]
                },

            ]
        }
    }
    componentDidMount() {
        this.setMenuOpen(this.props);
    }
    componentWillReceiveProps(nextProps) {
        this.onCollapse(nextProps.collapsed);
        this.setMenuOpen(nextProps);
    }
    setMenuOpen = props => {
        const {path} = props;
        this.setState({
            openKey: path.substr(0, path.lastIndexOf('/')),
            selectedKey: path
        });
    };
    onCollapse = (collapsed) => {
        this.setState({
            collapsed,
            firstHide: collapsed,
        });
    };
    menuClick = e => {
        this.setState({
            selectedKey: e.key
        });
    };
    openMenu = v => {
        console.log(v);
        this.setState({
            openKey: v[v.length - 1],
            firstHide: false,
        })
    };
    render(){
        const { collapsed, firstHide, openKey, selectedKey } = this.state;
        return(
            <Sider
                trigger={null}
                collapsed={collapsed}
            >
                <div className="logo" style={collapsed?{backgroundSize:'70%'}:{backgroundSize:'30%'}}/>
                <Menu
                    theme="dark"
                    mode="inline"
                    selectedKeys={[selectedKey]}
                    onClick={this.menuClick}
                    onOpenChange={this.openMenu}
                    openKeys={firstHide ? null : [openKey]}
                >
                    <SubMenu
                        key="/app/system"
                        title="系统设置"
                    >


                        <Menu.Item key="/app/system/user">
                            <Link to={'/app/system/user/index'}><span>管理员</span></Link>
                        </Menu.Item>
                        <Menu.Item key="/app/system/group">
                            <Link to={'/app/system/group/index'}><span>管理组</span></Link>
                        </Menu.Item>
                        <Menu.Item key="/app/system/auth">
                            <Link to={'/app/system/auth/index'}><span>后台权限</span></Link>
                        </Menu.Item>
                        <Menu.Item key="/app/system/menu">
                            <Link to={'/app/system/menu/index'}><span>后台菜单</span></Link>
                        </Menu.Item>
                    </SubMenu>

                    <SubMenu
                        key="/app/systemss"
                        title="系统设置"
                    >
                        <Menu.Item key="/app/system/user">
                            <Link to={'/app/system/user/index'}><span>管理员</span></Link>
                        </Menu.Item>
                        <Menu.Item key="/app/system/group">
                            <Link to={'/app/system/group/index'}><span>管理组</span></Link>
                        </Menu.Item>
                        <Menu.Item key="/app/system/auth">
                            <Link to={'/app/system/auth/index'}><span>后台权限</span></Link>
                        </Menu.Item>
                        <Menu.Item key="/app/system/menu">
                            <Link to={'/app/system/menu/index'}><span>后台菜单</span></Link>
                        </Menu.Item>
                    </SubMenu>

                    <Menu.Item key={"/app/form"}>
                        <Link to={"/app/form"}><span>表单</span></Link>
                    </Menu.Item>

                </Menu>
            </Sider>
        )
    }
}