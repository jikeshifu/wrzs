<template>
  <div class="home">
    <el-row type="flex" justify="space-between" :gutter="15">
      <el-col :span="6">
        <el-card shadow="always">
          <div class="card-flex">
            <div class="left">
              <i class="xbfont xb-lock"></i>
            </div>
            <div class="right">
              <h4>门锁数量</h4>
              <count-to class="p" :endVal="top4Data.lock" :duration="2000"></count-to>
            </div>
          </div>
        </el-card>
      </el-col>
      <el-col :span="6">
        <el-card shadow="always">
          <div class="card-flex">
            <div class="left">
              <i class="xbfont xb-online"></i>
            </div>
            <div class="right">
              <h4>在线数量</h4>
              <count-to class="p" :endVal="top4Data.on_line" :duration="2000"></count-to>
            </div>
          </div>
        </el-card>
      </el-col>
      <el-col :span="6">
        <el-card shadow="always">
          <div class="card-flex">
            <div class="left">
              <i class="xbfont xb-offline"></i>
            </div>
            <div class="right">
              <h4>离线数量</h4>
              <count-to class="p" :endVal="top4Data.off_line" :duration="2000"></count-to>
            </div>
          </div>
        </el-card>
      </el-col>
      <el-col :span="6">
        <el-card shadow="always">
          <div class="card-flex">
            <div class="left">
              <i class="xbfont xb-gateway"></i>
            </div>
            <div class="right">
              <h4>网关数量</h4>
              <count-to class="p" :endVal="top4Data.gateway" :duration="2000"></count-to>
            </div>
          </div>
        </el-card>
      </el-col>
    </el-row>
    <div class="update-open-charts">
      <div id="aWeekHistory" style="width:100%;height:400px"></div>
    </div>
  </div>
</template>

<script>
import * as echarts from 'echarts'
import {getTop4Data, getAWeekHistory} from "@/api/home"
import CountTo from 'vue-count-to'

export default {
  name: 'home',
  components: {
    CountTo
  },
  data() {
    return {
      // 顶部4个导航栏数据
      top4Data: {
        gateway: 0,
        lock: 0,
        on_line: 0,
        off_line: 0
      },
      // 一周内的历史记录
      aWeekHistoryOptions: {
        legend: {
          top: 'top'
        },
        tooltip: {
          trigger: 'axis'
        },
        grid: {
          left: '1%',
          right: '1%',
          bottom: 0,
          containLabel: true
        },
        xAxis: {
          type: 'category',
          data: ['0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00']
        },
        yAxis: {
          type: 'value'
        },
        series: [
          {
            name: '开门次数',
            type: 'line',
            data: [0, 0, 0, 0, 0, 0, 0]
          }, {
            name: '异常次数',
            type: 'line',
            data: [0, 0, 0, 0, 0, 0, 0]
          }, {
            name: '历史总数',
            type: 'line',
            data: [0, 0, 0, 0, 0, 0, 0]
          }
        ]
      },
      aWeekHistoryCharts: null
    }
  },
  beforeDestroy() {
    // this.aWeekHistoryCharts.dispose()
    // this.aWeekHistoryCharts = null
  },
  methods: {
    // 一周内的历史记录图表
    initAWeekHistoryCharts() {
      this.aWeekHistoryCharts = echarts.init(document.getElementById('aWeekHistory'))
      this.aWeekHistoryCharts.setOption(this.aWeekHistoryOptions)
    },
    // 获取顶部数据
    async getTop4Data() {
      await getTop4Data().then((data) => {
        this.top4Data = data.data
      })
    },
    // 获取7日内的开门次数走势图数据
    async getAWeekHistory() {
      await getAWeekHistory().then(data => {
        this.aWeekHistoryOptions.series[0].data = data.daily_number.map(item => item.number)
        this.aWeekHistoryOptions.series[1].data = data.unusuall_number.map(item => item.number)
        this.aWeekHistoryOptions.series[2].data = data.total_number.map(item => item.number)
        this.aWeekHistoryOptions.xAxis.data = data.daily_number.map(item => item.date)
        this.initAWeekHistoryCharts()
      })
    }
  },
  mounted() {
    // this.getTop4Data()
    // this.getAWeekHistory()
  }
}
</script>

<style lang="scss" scoped>
.card-flex {
  display: flex;
  align-items: center;
  justify-content: space-between;

  .xbfont {
    font-size: 60px;
  }

  .xb-lock {
    color: #40c9c6;
  }

  .xb-online {
    color: #36a3f7;
  }

  .xb-offline {
    color: #f4516c;
  }

  .xb-gateway {
    color: #34bfa3;
  }

  h4 {
    color: rgba(0, 0, 0, .45);
    font-size: 16px;
    margin-top: 0;
    margin-bottom: 0;
  }

  .p {
    display: block;
    font-size: 20px;
    margin-top: 10px;
    font-weight: bold;
  }
}

.update-open-charts,
.lock-action-charts {
  margin-top: 30px;
}
</style>
