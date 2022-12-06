<template>
  <div class="app-container">
    <search-box
      :filters="filters"
      :model="searchModel"
      :row-span="6"
      label-width="80px"
      @search="search"
      @reset="reset"
    >
      <el-button
        slot="buttonGroup"
        type="success"
        plain
        size="small"
        icon="el-icon-refresh"
        @click="upload"
        title="将前端页面菜单路由信息上传至后台数据库">同步菜单
      </el-button>
    </search-box>
    <section class="hidden-sm-and-down">
      <el-row>
        <el-col :span="24">
          <el-table
            ref="menuTable"
            border
            size="mini"
            default-expand-all
            :data="menuList"
            style="width: 100%"
            align="center"
          >
            <el-table-column
              align="center"
              label="标题"
              width="90px"
              prop="title">
            </el-table-column>
            <el-table-column
              align="center"
              label="父级"
              width="90px"
              prop="pid_title">
            </el-table-column>
            <el-table-column
              align="center"
              label="名称"
              prop="name">
            </el-table-column>
            <el-table-column
              align="center"
              label="页面路径"
              prop="path">
            </el-table-column>
            <el-table-column
              align="center"
              label="重定向"
              prop="redirect">
            </el-table-column>
            <el-table-column
              align="center"
              label="API"
              prop="api">
            </el-table-column>
            <el-table-column
              align="center"
              label="隐藏"
              width="80px"
              prop="hidden"
            >
              <el-tag slot-scope="scope" :type="scope.row.hidden == 1 ? 'info' : 'success'" size="small">
                {{ scope.row.hidden == 1 ? '隐藏' : '显示' }}
              </el-tag>
            </el-table-column>
            <el-table-column
              align="center"
              label="图标"
              width="50px"
              prop="icon"
              slot="icon"
            >
              <i slot-scope="scope" :class="scope.row.icon"></i>
            </el-table-column>
            <el-table-column
              align="center"
              width="80px"
              label="操作"
              slot="append"
            >
            </el-table-column>
          </el-table>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24" class="pagination-box ">
          <el-pagination
            @current-change="pageChange"
            background
            :page-size="searchModel.limit"
            :current-page="searchModel.page"
            layout="prev, pager, next"
            :total="menuTotal">
          </el-pagination>
        </el-col>
      </el-row>
    </section>
    <section class="hidden-md-and-up">
<!--      手机端显示 -->
      <min-table :data="menuList" :load-more="minLoad" table="menuTable" :loading="loading" :no-more="noMore" :load-more-btn="loadMoreBtn">
        <min-table-column
          label="标题"
          prop="title"
          slot="title"
          slot-scope="scope"
          :row="scope.row"></min-table-column>
        <min-table-column
          label="父级"
          prop="pid_title"
          slot="pid_title"
          slot-scope="scope"
          :row="scope.row"></min-table-column>
        <min-table-column
          label="名称"
          prop="name"
          slot="name"
          slot-scope="scope"
          :row="scope.row"></min-table-column>
        <min-table-column
          label="页面路径"
          prop="path"
          slot="path"
          slot-scope="scope"
          :row="scope.row"></min-table-column>
        <min-table-column
          label="重定向"
          prop="redirect"
          slot="redirect"
          slot-scope="scope"
          :row="scope.row"></min-table-column>
        <min-table-column
          label="API"
          prop="api"
          slot="api"
          slot-scope="scope"
          :row="scope.row"></min-table-column>
        <min-table-column
          label="隐藏"
          prop="hidden"
          slot="hidden"
          slot-scope="scope"
          :row="scope.row">
          <el-tag :type="scope.row.hidden == 1 ? 'info' : 'success'" size="small">
            {{ scope.row.hidden == 1 ? '隐藏' : '显示' }}
          </el-tag>
        </min-table-column>
        <min-table-column
          label="图标"
          prop="icon"
          slot="icon"
          slot-scope="scope"
          :row="scope.row">
          <i :class="scope.row.icon"></i>
        </min-table-column>
      </min-table>
    </section>
  </div>
</template>

<script>
import SearchBox from '@/components/SearchBox/SearchBox'
import { formatBackendMenuList } from '@/utils'
import { uploadMenus, getUserMenus } from '@/api/user'
import MinTable from '@/components/MinTable/Table'
import MinTableColumn from '@/components/MinTable/Column'

export default {
  name: 'Menu',
  components: { SearchBox, MinTable, MinTableColumn },
  data() {
    return {
      filters: [
        {
          type: 'input',
          name: 'path',
          placeholder: '',
          labelText: '路径:',
          options: []
        },
        {
          type: 'input',
          name: 'title',
          placeholder: '',
          labelText: '标题:',
          options: []
        },
        {
          type: 'input',
          name: 'pid_title',
          placeholder: '',
          labelText: '父级:',
          options: []
        },
        {
          type: 'input',
          name: 'api',
          placeholder: '',
          labelText: 'API:',
          options: []
        }
      ],
      searchModel: {
        path: '',
        title: '',
        pid_title: '',
        api: '',
        page: 1,
        limit: 20
      },
      menuList: [],
      menuTotal: 0,
      loading: false,
      noMore: false,
      loadMoreBtn: true
    }
  },
  created() {
    if (!this.defaultSearch) {
      this.defaultSearch = Object.assign({}, this.searchModel)
    }
    this.search()
  },
  methods: {
    search() {
      this.loading = true
      getUserMenus(this.searchModel).then(res => {
        if (res.code === 200) {
          if (res.data.menus.length === 0 || res.data.data.length < this.searchModel.limit) {
            // 没有
            this.noMore = true
          } else {
            this.loadMoreBtn = true
          }
          this.menuList = res.data.menus
          this.menuTotal = res.data.total
        }
      }).catch(err => {
        console.error(err)
      }).finally(() => {
        this.loading = false
      })
    },
    reset() {
      this.searchModel = Object.assign({}, this.defaultSearch)
      this.search()
    },
    upload() {
      this.$alert('请确认是否同步菜单?', '同步菜单', {
        confirmButtonText: '确定',
        callback: action => {
          const routers = formatBackendMenuList(this.$router.options.routes)
          uploadMenus(routers).then(res => {
            this.$message.success('菜单数据同步成功')
            window.location.reload()
          })
        }
      })
    },
    pageChange(val) {
      this.searchModel.page = val
      this.search()
    },
    minLoad() {
      this.searchModel.page += 1
      this.search()
    }
  }
}
</script>

<style scoped>

</style>
