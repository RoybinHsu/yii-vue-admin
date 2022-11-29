<template>
  <div class="app-container">
    <search-box
      :filters="filters"
      :model="searchModel"
      :row-span="6"
      label-width="100px"
      @search="search"
      @reset="reset"
    >
      <el-button
        slot="buttonGroup"
        type="primary"
        size="small"
        plain
        @click="add('创建角色')"
        icon="el-icon-plus">创建角色
      </el-button>
    </search-box>
    <el-row>
      <el-col :span="24">
        <el-table
          v-loading="loading"
          border
          size="mini"
          default-expand-all
          :data="list"
          style="width: 100%"
          align="center"
        >
          <el-table-column
            align="center"
            label="角色名称"
            prop="name">
          </el-table-column>
          <el-table-column
            align="center"
            label="角色描述"
            prop="description">
          </el-table-column>
          <el-table-column
            align="center"
            label="操作"
            width="300px"
          >
            <template v-slot="scope">
              <el-button-group>
                <el-button
                  type="primary"
                  plain
                  size="mini"
                  round
                  icon="el-icon-edit-outline"
                  @click="edit(scope.row)">编辑
                </el-button>
                <el-button
                  type="danger"
                  plain
                  size="mini"
                  round
                  icon="el-icon-delete"
                  @click="del(scope.row)">删除
                </el-button>
                <el-button
                  type="success"
                  plain
                  size="mini"
                  round
                  icon="el-icon-link"
                  @click="assign(scope.row)">权限
                </el-button>
              </el-button-group>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>
    <el-dialog :title="addModalTitle" :visible.sync="addModal" :close-on-click-modal="false">
      <el-form ref="addForm" :model="addForm" :rules="addFormRules" label-width="120px">
        <el-form-item label="名称:" prop="name">
          <el-input
            ref="name"
            v-model="addForm.name"
            tabindex="1"
          ></el-input>
        </el-form-item>
        <el-form-item label="描述:" prop="description">
          <el-input
            ref="description"
            v-model="addForm.description"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addModal = false">取 消</el-button>
        <el-button type="primary" @click="onSubmit">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import SearchBox from '@/components/SearchBox/SearchBox'

export default {
  name: 'SiteAuthPermissionRoleIndex',
  components: { SearchBox },
  data() {
    return {
      type: '',
      filters: [
        {
          type: 'input',
          name: 'name',
          placeholder: '',
          labelText: '名称:',
          options: []
        }
      ],
      searchModel: {},
      list: [],
      availableData: [],
      assigned: [],
      loading: false,
      addModalTitle: '创建角色',
      addModal: false,
      addForm: {
        name: '',
        ruleName: '',
        description: '',
        data: '',
        items: []
      },
      addFormDefault: null,
      addFormRules: {
        name: [{ required: true, trigger: 'blur', message: '缺少权限名称' }]
      },
      isPermission: false,
      isRole: false
    }
  },
  created() {
    const path = this.$route.path
    // 判断是角色页面还是权限页面
    if (path.toLocaleLowerCase() === '/site/auth/role') {
      this.isRole = true
    }
    if (path.toLocaleLowerCase() === '/site/auth/permission') {
      this.isPermission = true
    }
    this.type = this.$route.query.t
    console.log(this.type)
  },
  mounted() {
    console.log(123)
  },
  methods: {
    search() {
      console.log('search')
    },
    reset() {
      console.log('reset')
    },
    onSubmit() {
      console.log('onSubmit')
    },
    edit(row) {
      console.log(row)
    },
    del(row) {
      console.log(row)
    },
    assign(row) {
      console.log(row)
    }
  }
}
</script>

<style scoped>

</style>
