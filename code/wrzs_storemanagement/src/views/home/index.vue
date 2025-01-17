<template>
  <div class="home">
    <el-row :gutter="15">
      <el-col :span="6">
        <el-card>
          <div class="left">
            <span class="kj kj-store"></span>
          </div>
          <div class="right">
            <h4>门店数量</h4>
            <count-to class="p" :endVal="top4Data.store_num" :duration="2000"></count-to>
          </div>
        </el-card>
      </el-col>
      <el-col :span="6">
        <el-card>
          <div class="left">
            <span class="kj kj-home"></span>
          </div>
          <div class="right">
            <h4>房间数量</h4>
            <count-to class="p" :endVal="top4Data.room_num" :duration="2000"></count-to>
          </div>
        </el-card>
      </el-col>
      <el-col :span="6">
        <el-card>
          <div class="left">
            <span class="kj kj-device"></span>
          </div>
          <div class="right">
            <h4>设备数量</h4>
            <count-to class="p" :endVal="top4Data.device_num" :duration="2000"></count-to>
          </div>
        </el-card>
      </el-col>
      <el-col :span="6">
        <el-card>
          <div class="left">
            <span class="kj kj-money"></span>
          </div>
          <div class="right">
            <h4>交易数量</h4>
            <count-to class="p" :endVal="top4Data.order_num" :duration="2000"></count-to>
          </div>
        </el-card>
      </el-col>
    </el-row>
    <div class="update-week-charts">
      <div id="aWeekHistory" style="width:100%;height:400px"></div>
    </div>
  </div>
</template>

<script>
import * as echarts from 'echarts'
import CountTo from 'vue-count-to'
import { getTop4Data, getAWeekHistory } from "@/api/home"

export default {
  name: 'home',
  components: {
    CountTo
  },
  data() {
    return {
      // 顶部4个导航栏数据
      top4Data: {
        device_num: 0,
        order_num: 0,
        room_num: 0,
        store_num: 0
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
            name: '交易数量',
            type: 'line',
            data: [0, 0, 0, 0, 0, 0, 0]
          }
        ]
      },
      aWeekHistoryCharts: null
    }
  },
  methods: {
    // 一周内的历史记录图表
    initAWeekHistoryCharts() {
      this.aWeekHistoryCharts = echarts.init(document.getElementById('aWeekHistory'))
      this.aWeekHistoryCharts.setOption(this.aWeekHistoryOptions)
    },
    // 获取顶部数据
    async getTop4Data() {
      await getTop4Data().then(data => {
        this.top4Data = data.info
      })
    },
    // 获取7日内的交易数量走势图数据
    async getAWeekHistory() {
      // const data = await getAWeekHistory()
      // this.aWeekHistoryOptions.series[0].data = data.daily_number.map(item => item.number)
      this.initAWeekHistoryCharts()
    }
  },
  mounted() {
    this.getTop4Data()
    this.getAWeekHistory()
  }
}
</script>

<style lang="scss" scoped>
::v-deep .el-card__body {
  display: flex;
  align-items: center;
  justify-content: space-between;
  .left {
    .kj, .el-icon-cpu {
      font-size: 60px;
    }
    .kj-store {
      color: #40c9c6;
    }
    .kj-home {
      color: #36a3f7;
    }
    .kj-device {
      color: #f4516c;
    }
    .kj-money {
      color: #34bfa3;
    }
  }
  .right {
    h4 {
      margin-top: 0;
      margin-bottom: 0;
      color: rgba(0, 0, 0, .45);
    }
    .p {
      display: block;
      margin-top: 10px;
      font-size: 20px;
      font-weight: bold;
    }
  }
}

.update-week-charts {
  margin-top: 30px;
}
</style>
