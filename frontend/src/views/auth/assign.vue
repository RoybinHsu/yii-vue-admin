<template>
  <div class="app-container">
    <el-row class="assign-detail">
      <el-col :span="24">
        <el-table
          border
          size="mini"
          default-expand-all
          :data="list"
          style="width: 100%"
          align="center"
        >
          <el-table-column
            v-for="item in cols"
            align="center"
            :key="item.label"
            :label="item.label"
            :prop="item.prop">
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>
    <el-transfer
      @change="change"
      :titles="titles"
      filterable
      :filter-method="filterModel"
      filter-placeholder="请输入路由关键字"
      v-model="assigned"
      :data="data">
    </el-transfer>
  </div>
</template>

<script>
import { assignAssign, assignIndex, assignRemove } from '@/api/auth'

export default {
  name: 'SiteAuthPermissionAssign',
  data() {
    return {
      type: '',
      name: '',
      uid: '',
      description: '',
      titles: [],
      data: [],
      assigned: [],
      filterModel(query, item) {
        return query ? item.label.indexOf(query) > -1 : true
      },
      cols: [],
      list: []
    }
  },
  created() {
    this.type = this.$route.query.type
    this.name = this.$route.query.name
    this.description = this.$route.query.description
    this.uid = this.$route.query.uid
    if (this.isAssignPermission()) {
      this.titles = ['待分配路由', '已分配路由']
      this.cols = [
        { label: '权限名称', prop: 'name' },
        { label: '描述', prop: 'description' }
      ]
      this.list.push({
        name: this.name,
        description: this.description
      })
    }
    if (this.isAssignRole()) {
      this.titles = ['待分配权限和角色', '已分配角色和权限']
      this.cols = [
        { label: '角色名称', prop: 'name' },
        { label: '描述', prop: 'description' }
      ]
      this.list.push({
        name: this.name,
        description: this.description
      })
    }
    if (this.isAssignUser()) {
      this.titles = ['待分配角色', '已分配角色']
      this.cols = [
        { label: '用户名称', prop: 'name' },
        { label: '描述', prop: 'description' }
      ]
      this.list.push({
        name: this.name,
        description: this.description
      })
    }
    this.getData()
  },
  methods: {
    isAssignPermission() {
      return this.type === 'permission'
    },
    isAssignRole() {
      return this.type === 'role'
    },
    isAssignUser() {
      return this.type === 'user'
    },
    formatData(data = []) {
      const ret = []
      data.forEach((v, index) => {
        ret.push({
          label: v,
          key: v
        })
      })
      return ret
    },
    getParams() {
      const data = {}
      data.id = this.name
      if (this.isAssignUser()) {
        data.id = this.uid
      }
      return data
    },
    getData() {
      const data = this.getParams()
      assignIndex(this.type, data).then(res => {
        const data = res.data.available
        data.push(...res.data.assigned)
        this.data = this.formatData(data)
        this.assigned = res.data.assigned
      }).catch(err => {
        console.error(err)
      })
    },
    change(right, direction, change) {
      // right 穿梭框右边的数据数组 direction 移动的方向left right, change标识移动的元素数组
      if (direction === 'right') {
        // 添加数据
        this.assign(change)
      } else {
        // 删除数据
        this.remove(change)
      }
    },
    assign(items) {
      const data = this.getParams()
      data.items = items
      assignAssign(this.type, data).then(res => {
      }).catch(err => {
        console.error(err)
      })
    },
    remove(items) {
      const data = this.getParams()
      data.items = items
      assignRemove(this.type, data).then(res => {
      }).catch(err => {
        console.error(err)
      })
    }
  }
}
</script>

<style scoped>
.app-container >>> .el-transfer-panel {
  width: 40%;
  height: 800px;
}

.app-container >>> .el-transfer__buttons {
  width: 17%;
}

/deep/ .el-transfer-panel__list.is-filterable {
  height: 600px;
}

.assign-detail {
  margin-bottom: 20px;
}
</style>
