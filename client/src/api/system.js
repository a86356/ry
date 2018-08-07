



import {fetch} from "./fetch";
//权限列表
export  function getAuthList(data) {
    return fetch({
        url: 'v1/userauth/get-auth-list',
        method: 'get',
        data:data
    })
}

//更新权限
export function updateAuth(data) {
    return fetch({
        url: 'v1/userauth/update',
        method: 'post',
        data:data
    })
}
//添加权限
export function AddAuth(data) {
    return fetch({
        url: 'v1/userauth/add',
        method: 'post',
        data:data
    })
}

//删除权限
export function DeleteAuth(data) {
    return fetch({
        url: 'v1/userauth/delete',
        method: 'post',
        data:data
    })
}


//管理员添加
export function AddUser(data) {
    return fetch({
        url: 'v1/user/add',
        method: 'post',
        data:data
    })
}

//管理员删除
export function DeleteUser(data) {
    return fetch({
        url: 'v1/user/delete',
        method: 'get',
        data:data
    })
}

//管理员更新
export function UpdateUser(data) {
    return fetch({
        url: 'v1/user/update',
        method: 'post',
        data:data
    })
}

//管理员查询
export function ReadUser(data) {
    return fetch({
        url: 'v1/user/read',
        method: 'get',
        data:data
    })
}
