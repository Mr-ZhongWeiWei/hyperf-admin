<template>
    <a-card :loading="loading" :bordered="false" :body-style="{padding: '0'}">
        <div class="salesCard">
            <a-tabs default-active-key="1" size="large" :tab-bar-style="{marginBottom: '24px', paddingLeft: '16px'}">
                <template #rightExtra>
                    <div class="extra-wrapper" >
                        <div class="extra-item">
                            <a>今日</a>
                            <a>本周</a>
                            <a>本月</a>
                            <a>本年</a>
                        </div>
                        <a-range-picker :style="{width: '256px'}" />
                    </div>
                </template>

                <a-tab-pane loading="true" tab="销售量" key="1">
                    <a-row>
                        <a-col :xl="16" :lg="12" :md="12" :sm="24" :xs="24">
                            <div id="xsl" style="width: 100%;height: 300px;margin: 30px"></div>
                        </a-col>
                        <a-col :xl="8" :lg="12" :md="12" :sm="24" :xs="24">
                            <rank-list title="门店销售额排名" :list="rankList"/>
                        </a-col>
                    </a-row>

                </a-tab-pane>
                <a-tab-pane tab="访问量" key="2">
                    <a-row>
                        <a-col :xl="16" :lg="12" :md="12" :sm="24" :xs="24">
                            <div id="xsl" style="width: 100%;height: 300px;margin: 30px"></div>
                        </a-col>
                        <a-col :xl="8" :lg="12" :md="12" :sm="24" :xs="24">
                            <rank-list title="门店销售额排名" :list="rankList"/>
                        </a-col>
                    </a-row>
                </a-tab-pane>
            </a-tabs>
        </div>
    </a-card>
</template>

<script>
import {defineComponent, onMounted, ref} from 'vue';
import { Chart } from '@antv/g2';
export default defineComponent({
    name: 'Analysis',
    setup(){
        onMounted(()=>{
            const data = [
                { year: '1951 年', sales: 38 },
                { year: '1952 年', sales: 52 },
                { year: '1956 年', sales: 61 },
                { year: '1957 年', sales: 145 },
                { year: '1958 年', sales: 48 },
                { year: '1959 年', sales: 38 },
                { year: '1960 年', sales: 38 },
                { year: '1962 年', sales: 38 },
            ];
            const chart = new Chart({
                container: 'xsl',
                autoFit: true,
            });
            chart.data(data);
            chart.scale('sales', {
                nice: true,
            });

            chart.tooltip({
                showMarkers: false
            });
            chart.interaction('active-region');

            chart.interval().position('year*sales').color('year', () => {
                return '#2194ff';
            });
            chart.render();
        })
        const rankList = ref([])
        for (let i = 0; i < 7; i++) {
            rankList.value.push({
                name: '白鹭岛 ' + (i + 1) + ' 号店',
                total: 1234.56 - i * 100
            })
        }
        return {
            rankList
        }
    },

    data() {
        return {
            loading: false,
            pieStyle: {
                stroke: '#fff',
                lineWidth: 1
            },
        }
    },

})
</script>

<style lang="less" scoped>
.extra-wrapper {
    line-height: 55px;
    padding-right: 24px;

    .extra-item {
        display: inline-block;
        margin-right: 24px;

        a {
            margin-left: 24px;
        }
    }
}

.antd-pro-pages-dashboard-analysis-twoColLayout {
    position: relative;
    display: flex;
    display: block;
    flex-flow: row wrap;
}

.antd-pro-pages-dashboard-analysis-salesCard {
    height: calc(100% - 24px);

    /deep/ .ant-card-head {
        position: relative;
    }
}

.dashboard-analysis-iconGroup {
    i {
        margin-left: 16px;
        color: rgba(0, 0, 0, .45);
        cursor: pointer;
        transition: color .32s;
        color: black;
    }
}

.analysis-salesTypeRadio {
    position: absolute;
    right: 54px;
    bottom: 12px;
}
</style>
