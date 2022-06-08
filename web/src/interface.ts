/**
 * common
 * @author MrZhong [ 799149346@qq.com / http://github.com/Mr-ZhongWeiWei ]
 * @date 2022/4/2
 */
type PagInfo    =   {
    count: number | string,
    current: number | string,
    limit: number | string
}

export interface Response {
    code: number,
    message: string,
    data?: any,
    pageInfo?: PagInfo
}
