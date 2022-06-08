import {isString} from "ant-design-vue/es/_util/util";
import store from "@/store";

export function checkAccess(data: any, logic: string = 'or'): boolean {
    let access:string[]  =   []
    if (isString(data)){
        access  =   data.split(',')
    }
    if (Array.isArray(data)){
        access  =   data
    }
    const intersect = Array.from(new Set([...store.getters.userInfo.permission].filter(v => new Set(access).has(v))))
    if (intersect.length == 0 || logic == 'and' && intersect.length != access.length){
        return false
    }
    return true;
}





