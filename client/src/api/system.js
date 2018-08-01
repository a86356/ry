



import {fetch} from "./fetch";
//权限列表
export  function getAuthList(data) {
    return fetch({
        url: 'v1/userauth/get-auth-list',
        method: 'get',
        data:data
    })
}

//添加更新权限
export function updateAddAuth(data) {
    return fetch({
        url: 'v1/userauth/addOrUpdate',
        method: 'post',
        data:data
    })
}