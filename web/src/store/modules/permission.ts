import {asyncRouterMap, constantRouterMap} from '@/config/router.config'
import { RouteRecordRaw } from 'vue-router'
import * as layout from "@/layouts/index";
import { defineAsyncComponent, markRaw } from 'vue'
const _import = (path: string) => defineAsyncComponent(() => import(`@/views/${path}.vue`))
interface TreeNode{
    name: string,
    path: string,
    meta: any
    component: any,
    redirect: string,
    children?: TreeNode[]
}

function makeTree(treeNodes: TreeNode[]): TreeNode[] {
    const nodesMap = new Map<number, TreeNode>(
        treeNodes.map(node => [node.meta.id, node])
    );

    const virtualRoot = { } as Partial<TreeNode>;
    treeNodes.forEach((node, i) => {
        const parent = nodesMap.get(node.meta.parent_id) ?? virtualRoot;
        (parent.children ??= []).push(node);
    });

    return virtualRoot.children ?? [];
}

export default {
    state: {
        routers: constantRouterMap,
        addRouters: []
    },
    mutations: {
        SET_ROUTERS: (state: { addRouters: any; routers: RouteRecordRaw[] }, routers: ConcatArray<RouteRecordRaw>) => {
            state.addRouters = routers
            state.routers = constantRouterMap.concat(routers)
        }
    },
    actions: {
        GenerateRoutes({commit}: any, data: any) {
            return new Promise<void>( resolve => {
                const { menus } = data
                const layoutArr: any = layout
                menus.map((item: TreeNode)=>{
                    if (Object.keys(layoutArr).includes(item.component)){
                        item.component = markRaw(layoutArr[item.component])
                    }else {
                        item.component = markRaw(_import(item.component))
                    }
                })
                const routers    =   makeTree(menus)
                commit('SET_ROUTERS', [...routers,...asyncRouterMap])
                resolve()
            })
        }
    }
}
