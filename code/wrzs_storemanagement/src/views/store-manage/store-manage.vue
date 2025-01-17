<template>
  <div class="store-manage">
    <Index v-if="type === 'index'" @componentsChange="componentsChange"></Index>
    <Add v-if="type === 'add'" @componentsChange="componentsChange"></Add>
    <Edit v-if="type === 'edit'" @componentsChange="componentsChange"></Edit>
    <RoomManage v-if="type === 'room'" @componentsChange="componentsChange"></RoomManage>
    <DeviceManage v-if="type === 'device'" @componentsChange="componentsChange"></DeviceManage>
    <DcardManage v-if="type === 'dcard'" @componentsChange="componentsChange"></DcardManage>
    <ManJianManage v-if="type === 'manJian'" @componentsChange="componentsChange"></ManJianManage>
    <ServiceManage v-if="type === 'service'" @componentsChange="componentsChange"></ServiceManage>
  </div>
</template>

<script>
import Index from './store-manage-index'
import Add from './store-manage-add'
import Edit from './store-manage-edit'
import RoomManage from './room-manage/room-manage'
import DeviceManage from './device-manage/device-manage'
import DcardManage from './dcard-manage/dcard-manage'
import ManJianManage from './manJian-manage/manJian-manage'
import ServiceManage from './service-manage/service-manage.vue'

export default {
  name: 'store-manage',
  components: {
    DcardManage,
    Index,
    Add,
    Edit,
    RoomManage,
    DeviceManage,
    ManJianManage,
    ServiceManage
  },
  data() {
    return {
      type: 'index'
    }
  },
  methods: {
    // 组件改变
    componentsChange(e) {
      this.type = e
    }
  }
}
</script>

<style scoped>

</style>
