//引入fetch.js文件
import { fetch } from './fetch'


//登陆
export  function login(data) {
    return fetch({
        url: 'v1/user/login',
        method: 'post',
        data: data,
    })
}

//获得用户菜单
export  function getMenu(url,type,data) {
    return fetch({
        url: url,
        method: type,
        data: data,
    })
}

//获得用户信息
export  function getUserInfo() {
    return fetch({
        url: '',
        method: "get",
    })
}

//测试

export function success(){
    return fetch({
        url: '',
        method: 'get'
    })
}