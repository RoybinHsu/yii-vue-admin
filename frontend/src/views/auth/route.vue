<template>
  <div class="app-container">
    <el-row :gutter="15">
      <el-col :span="16">
        <el-input placeholder="请输入内容" v-model="newRoute"></el-input>
      </el-col>
      <el-col :span="8">
        <el-button type="success" size="medium" plain @click="addNewRoute" icon="el-icon-circle-plus-outline" >添加路由</el-button>
        <el-button type="warning" size="medium" plain @click="freshRouteCache" icon="el-icon-refresh">刷新缓存</el-button>
      </el-col>
    </el-row>
    <br>
    <br>
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
import { routeCreate, routeFresh, routeIndex, routeRemove } from '@/api/auth'

export default {
  name: 'AuthApi',
  data() {
    return {
      newRoute: '',
      titles: ['待添加路由', '已添加路由'],
      assigned: [],
      data: [],
      filterModel(query, item) {
        return query ? item.label.indexOf(query) > -1 : true
      }
    }
  },
  created() {
    this.search()
  },
  methods: {
    search() {
      routeIndex().then(res => {
        const data = res.data.available
        data.push(...res.data.assigned)
        this.data = this.formatData(data)
        this.assigned = res.data.assigned
      }).catch(err => {
        console.error(err)
      })
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
    addNewRoute() {
      if (this.newRoute) {
        let can_add = true
        this.data.forEach(v => {
          if (v.key === this.newRoute) {
            // 已存在
            can_add = false
          }
        })
        if (!can_add) {
          this.$message.error('路由信息已存在, 请您重新填写')
          return false
        }
        this.addRoutes([this.newRoute], true)
        this.newRoute = ''
      } else {
        this.$message.error('请填写要添加路由名称')
      }
    },
    addRoutes(routes, push = false) {
      routeCreate(routes).then(res => {
        if (res.code === 200 && push) {
          const data = this.formatData(routes)
          this.data.push(...data)
          this.assigned.unshift(...routes)
        }
      }).catch(err => {
        console.log(err)
      })
    },
    removeRoutes(routes) {
      routeRemove(routes).then(res => {
      }).catch(err => {
        console.error(err)
      })
    },
    change(right, direction, change) {
      // right 穿梭框右边的数据数组 direction 移动的方向left right, change标识移动的元素数组
      if (direction === 'right') {
        // 添加数据
        this.addRoutes(change)
      } else {
        // 删除数据
        this.removeRoutes(change)
      }
    },
    freshRouteCache() {
      routeFresh().then(res => {
        if (res.code === 200) {
          this.search()
        }
      }).catch()
    }
  }
}
</script>

<style scoped>
.app-container >>> .el-transfer-panel {
  width: 40%;
  height: 700px;
}

.app-container >>> .el-transfer__buttons {
  width: 17%;
}

/deep/ .el-transfer-panel__list.is-filterable {
  height: 600px;
}
</style>

