import axios from 'axios'
//定义fetch函数，config为配置
export function fetch(config){
    //返回promise对象
    return new Promise((resolve,reject) =>{
        //创建axios实例，把基本的配置放进去
        const instance = axios.create({
            //定义请求文件类型

            timeout: 3000,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            withCredentials:"include",
            params:{
                'access-token':1223,
            },
            //定义请求根目录
            baseURL: 'http://localhost/yiicms/server/web/index.php/'
        });
        //请求成功后执行的函数
        instance(config).then(res =>{
            resolve(res.data);
            //失败后执行的函数
        }).catch(err => {
            reject(err.data);
        })
    });
}