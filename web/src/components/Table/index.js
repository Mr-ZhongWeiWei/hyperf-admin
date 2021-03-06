import {defineComponent, ref, toRefs} from 'vue'
import get from 'lodash.get'
import './ToolBar.less'
import {tableProps} from "ant-design-vue/lib/table";
import { TOOL_BAR_DEFAULT_LAYOUT } from "@/store/mutation-types";
export default defineComponent({
    props: Object.assign({}, tableProps(), {
        showToolBar: {
            type: Boolean,
            default: true
        },
        rowKey: {
            type: [String, Function],
            default: 'key'
        },
        data: {
            type: Function,
            required: true
        },
        pageNum: {
            type: Number,
            default: 1
        },
        pageSize: {
            type: Number,
            default: 10
        },
        showSizeChanger: {
            type: Boolean,
            default: true
        },
        size: {
            type: String,
            default: 'default'
        },
        alert: {
            type: [Object, Boolean],
            default: null
        },
        rowSelection: {
            type: Object,
            default: null
        },
        // /** @Deprecated */
        showAlertInfo: {
            type: Boolean,
            default: false
        },
        showPagination: {
            type: [String , Boolean],
            default: 'auto'
        },
        pageURI: {
            type: Boolean,
            default: false
        },
        resposeKeys: {
            type: Object,
            default: {
                data: 'data',
                current: 'page',
                total: 'totalCount'
            }
        },
        layout: {
            type: String,
            default: TOOL_BAR_DEFAULT_LAYOUT
        }
    }),
    setup(prop){
        const props     =   toRefs(prop)
        const needTotalList         =   ref([])
        const selectedRows          =   ref([])
        const selectedRowKeys       =   ref([])
        const loading          =   ref(false)
        const dataSource       =   ref([])
        const pagination       =   ref({})
        const setSize = ref(props.size.value)

        const tableColumns  =   ref(props.columns.value)
        const checkedArr  =   ref([])
        const checkedField  =   ref([])
        const indeterminate =   ref(false)
        const checkedAll =   ref(true)

        tableColumns.value = props.columns.value
        props.columns.value.map(item=>{
            checkedArr.value.push(item.dataIndex)
            checkedField.value.push(item.dataIndex)
        })


        const refresh = (bool = true)=>{
            !bool && (pagination.value = Object.assign({}, {
                current: 1, pageSize: props.pageSize.value
            }))
            loadData()
        }

        /**
         * ??????????????????
         * @param {Object} paginationObject ???????????????
         * @param {Object} filters ????????????
         * @param {Object} sorter ????????????
         */
        const loadData  =   (paginationObject, filters, sorter)=>{
            loading.value = true
            const parameter = Object.assign({
                    page: (paginationObject && paginationObject.current) || props.showPagination.value && pagination.value.current || props.pageNum.value,
                    limit: (paginationObject && paginationObject.pageSize) || props.showPagination.value && pagination.value.pageSize || props.pageSize.value
                },
                (sorter && sorter.field && {
                    sortField: sorter.field
                }) || {},
                (sorter && sorter.order && {
                    sortOrder: sorter.order
                }) || {}, {
                    ...filters
                }
            )
            const result = props.data.value(parameter)
            // eslint-disable-next-line
            if ((typeof result === 'object' || typeof result === 'function') && typeof result.then === 'function') {
                result.then(r => {
                    pagination.value = props.showPagination.value && Object.assign({}, pagination.value, {
                        current: r[props.resposeKeys.value.current], // ?????????????????????????????????
                        total: r[props.resposeKeys.value.total], // ??????????????????????????????
                        showSizeChanger: props.showSizeChanger.value,
                        pageSize: (paginationObject && paginationObject.pageSize) || pagination.value.pageSize
                    }) || false
                    // ??????????????????????????????????????????????????????????????? 0 ,????????????????????????
                    if (r.data.length === 0 && props.showPagination.value && pagination.value.current > 1) {
                        pagination.value.current--
                        loadData()
                        return
                    }

                    // ??????????????????????????????????????? r[props.resposeKeys.value.total] ??? this.showPagination = true ??? page ??? pageSize ?????? ??? totalCount ???????????? page * pageSize ?????????
                    // ??????????????????????????????????????????????????????????????? table ????????????
                    try {
                        if ((['auto', true].includes(props.showPagination) && r[props.resposeKeys.value.total] <= (r[props.resposeKeys.value.current] * pagination.value.pageSize))) {
                            pagination.value.hideOnSinglePage = true
                        }
                    } catch (e) {
                        pagination.value = false
                    }
                    dataSource.value = r[props.resposeKeys.value.data] // ??????????????????????????????
                    loading.value = false
                })
            }
        }

        const initTotalList = (columns)=>{
            let totalList = []
            columns && columns instanceof Array && columns.forEach(column => {
                if (column.needTotal) {
                    totalList.push({
                        ...column,
                        total: 0
                    })
                }
            })
            return totalList
        }

        /**
         * ???????????????????????????????????? total ??????
         * @param selectedRowKeys
         * @param selectedRows
         */
        const updateSelect = (selectedRowKeysData, selectedRowsData)=> {
            selectedRows.value = selectedRowsData
            selectedRowKeys.value = selectedRowKeysData
            const list = needTotalList.value
            needTotalList.value = list.map(item => {
                return {
                    ...item,
                    total: selectedRows.value.reduce((sum, val) => {
                        const total = sum + parseInt(get(val, item.dataIndex))
                        return isNaN(total) ? 0 : total
                    }, 0)
                }
            })

        }


        return {
            needTotalList,
            selectedRowKeys,
            selectedRows,
            loading,
            dataSource,
            pagination,
            loadData,
            refresh,
            initTotalList,
            updateSelect,
            setSize,
            tableColumns,
            checkedArr,
            checkedField,
            indeterminate,
            checkedAll
        }
    },
    watch: {
        checkedArr (val){
            this.indeterminate = !!val.length && val.length < this.checkedField.length;
            this.checkedAll = val.length === this.checkedField.length;
        },
        'localPagination.current' (val) {
            this.pageURI && this.$router.push({
                ...this.$route,
                name: this.$route.name,
                params: Object.assign({}, this.$route.params, {
                    pageNo: val
                })
            })
            this.needTotalList = this.initTotalList(this.columns)
            this.selectedRowKeys = []
            this.selectedRows = []
        },
        pageNum (val) {
            Object.assign(this.localPagination, {
                current: val
            })
        },
        pageSize (val) {
            Object.assign(this.localPagination, {
                pageSize: val
            })
        },
        showSizeChanger (val) {
            Object.assign(this.localPagination, {
                showSizeChanger: val
            })
        }
    },
    created() {
        const { page } = this.$route.params
        const localPageNum = this.pageURI && (page && parseInt(page)) || this.pageNum
        this.pagination = ['auto', true].includes(this.showPagination) && Object.assign({}, this.pagination, {
            current: localPageNum,
            pageSize: this.pageSize,
            showSizeChanger: this.showSizeChanger
        }) || false
        this.needTotalList = this.initTotalList(this.columns)
        this.loadData()
    },
    render() {

        /**
         * ?????? table ????????????
         */
        const clearSelected = ()=>{
            if (this.rowSelection) {
                this.rowSelection.onChange([], [])
                this.updateSelect([], [])
            }
        }
        /**
         * ???????????? table ?????????????????? clear ??????????????????????????????????????????
         * @param callback
         * @returns {*}
         */
        const renderClear = (callback)=>{
            if (this.selectedRowKeys.length <= 0) return null
            return (
                <a style="margin-left: 24px" onClick={() => {
                    callback()
                    clearSelected()
                }}>??????</a>
            )
        }

        const renderAlert = ()=>{
            // ?????????????????????
            const needTotalItems = this.needTotalList.map((item) => {
                return (
                    <span style="margin-right: 12px">
                      {item.title}?????? <a style="font-weight: 600">{item.total}</a>
                    </span>
                )
            })

            // ?????? ?????? ??????
            const clearItem = (typeof this.alert.clear === 'boolean' && this.alert.clear) ? (
                renderClear(this.clearSelected)
            ) : (this.alert !== null && typeof this.alert.clear === 'function') ? (
                renderClear(this.alert.clear)
            ) : null

            // ?????? alert ??????
            return (
                <a-alert showIcon={true} v-slots={{
                    message: ()=> {
                        return (
                            <>
                                <span style="margin-right: 12px">?????????: <a style="font-weight: 600">{this.selectedRows.length}</a></span>
                                {needTotalItems}
                                {clearItem}
                            </>
                        )
                    }
                }} style="margin-bottom: 16px"></a-alert>
            )
        }

        /**
         * ????????????????????????
         * @returns {JSX.Element}
         */
        const renderToolBar = ()=>{
            const renderToolBarSetUp    =   ()=>{
                return (
                    <a-space size={12}>
                        <a-tooltip placement="top" title='??????'>
                            <ReloadOutlined onClick={() => {
                                this.refresh()
                            }} class="toolbar-icon"/>
                        </a-tooltip>
                        <a-tooltip placement="top" title='??????'>
                            <a-dropdown placement='bottom' trigger={['click']} v-slots={{
                                overlay: () => {
                                    return (
                                        <a-menu>
                                            <a-menu-item onClick={() => this.setSize = 'default'} key="0">
                                                ??????
                                            </a-menu-item>
                                            <a-menu-item  onClick={() => this.setSize = 'middle'} key="1">
                                                ??????
                                            </a-menu-item>
                                            <a-menu-item onClick={() => this.setSize = 'small'} key="3">
                                                ??????
                                            </a-menu-item>
                                        </a-menu>
                                    )
                                }
                            }}>
                                <ColumnHeightOutlined class="toolbar-icon"/>
                            </a-dropdown>
                        </a-tooltip>
                        <a-tooltip placement="top" title='?????????'>
                            <a-popover placement="bottomLeft" v-slots={{
                                content: () => {
                                    return (
                                        <a-checkbox-group value={this.checkedArr} onChange={(checkedValue) => {
                                            this.checkedArr = checkedValue
                                            let field = []
                                            this.columns.map(val => {
                                                if (this.checkedArr.indexOf(val.dataIndex) != -1) {
                                                    field.push(val)
                                                }
                                            })
                                            this.tableColumns = field
                                        }}>
                                            {this.columns.map(item => {
                                                return (
                                                    <a-row className='checkbox-group-item'>
                                                        <a-checkbox value={item.dataIndex}>{item.title}</a-checkbox>
                                                    </a-row>
                                                )
                                            })}
                                        </a-checkbox-group>
                                    )
                                },
                                title: () => {
                                    return (
                                        <>
                                            <a-checkbox checked={this.checkedAll} indeterminate={this.indeterminate} onChange={(e) => {
                                                this.checkedAll = !this.checkedAll
                                                this.checkedArr = e.target.checked ? this.checkedField : []
                                                this.indeterminate = false
                                            }}>?????????
                                            </a-checkbox>
                                            <a onClick={() => {
                                                this.tableColumns = this.columns
                                                this.checkedArr = this.checkedField
                                            }} style="float:right;line-height:30px">??????</a>
                                        </>
                                    )
                                }
                            }} trigger="click">
                                <SettingOutlined class="toolbar-icon"/>
                            </a-popover>

                        </a-tooltip>
                        {/*TODO Excel ????????????*/}
                    </a-space>
                )
            }

            if (this.layout == 'layoutUpAndDown'){
                return (
                    <div class="table-list-toolbar">
                        <a-row>
                            { this.$slots.toolBarFilter?.()}
                        </a-row>
                        <div class="table-list-toolbar-container">
                            <div class="table-list-toolbar-left" >
                                <a-space>
                                    { this.$slots.toolbarAction?.() }
                                </a-space>
                            </div>
                            <a-space class="table-list-toolbar-right" size={16}>
                                {renderToolBarSetUp()}
                            </a-space>
                        </div>
                    </div>
                )
            }

            if (this.layout == 'layoutLeftAndRight'){
                return (
                    <div class="table-list-toolbar">
                        <div class="table-list-toolbar-container">
                            <div class="table-list-toolbar-left" >
                                <a-space>
                                    { this.$slots.toolbarAction?.() }
                                </a-space>
                            </div>
                            <a-space class="table-list-toolbar-right" size={16}>
                                { this.$slots.toolBarFilter ? <div className="table-list-toolbar-left-content">{this.$slots.toolBarFilter()}</div> : null }
                                {renderToolBarSetUp()}
                            </a-space>
                        </div>
                    </div>
                )
            }
            if (this.layout == 'layoutLeftAndRight2'){
                return (
                    <div class="table-list-toolbar">
                        <div class="table-list-toolbar-container">
                            <div class="table-list-toolbar-left" >
                                { this.$slots.toolBarFilter ? <div className="table-list-toolbar-left-content">{this.$slots.toolBarFilter()}</div> : null }
                            </div>
                            <a-space class="table-list-toolbar-right" size={16}>
                                { this.$slots.toolbarAction?.() }
                                {renderToolBarSetUp()}
                            </a-space>
                        </div>
                    </div>
                )
            }

        }

        const props = {}
        const showAlert = (typeof this.alert === 'object' && this.alert !== null && this.alert.show) && typeof this.rowSelection.selectedRowKeys !== 'undefined' || this.alert

        Object.keys(tableProps()).forEach(k => {
            switch (k){
                case 'rowSelection':
                    if (showAlert && this.rowSelection) {
                        // ??????????????????alert?????????????????? rowSelection ??????
                        props[k] = {
                            ...this.rowSelection,
                            selectedRows: this.selectedRows,
                            selectedRowKeys: this.selectedRowKeys,
                            onChange: (selectedRowKeys, selectedRows) => {
                                this.updateSelect(selectedRowKeys, selectedRows)
                                typeof this[k].onChange !== 'undefined' && this[k].onChange(selectedRowKeys, selectedRows)
                            }
                        }
                        return props[k]
                    } else if (!this.rowSelection) {
                        // ????????????????????? rowSelection ???????????????????????????
                        props[k] = null
                        return props[k]
                    }
                    break;
                case 'columns':
                    return props[k] = this.tableColumns
                    break;
                case 'size':
                    return props[k] = this.setSize
                    break;
            }

            this[k] && (props[k] = this[k])
            return props[k]
        })

        const table = (
            <a-table {...props} v-slots={this.$slots} onChange={this.loadData} onExpand={(expanded, record) => {
                this.$emit('expand', expanded, record)
            }}>
            </a-table>
        )
        return (
            <div class="table-wrapper">
                { this.showToolBar ? renderToolBar() : null }
                { showAlert && this.selectedRowKeys.length > 0 ? renderAlert() : null }
                {table}
            </div>
        )
    }
})
