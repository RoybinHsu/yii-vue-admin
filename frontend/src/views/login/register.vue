<template>
  <el-container>
    <el-header></el-header>
    <el-main>
      <el-row :gutter="20">
        <el-col :span="14" :offset="5">
          <el-form ref="form" :model="form" :rules="registerRules" label-width="120px">
            <el-form-item label="用户名称:" prop="username">
              <el-input
                ref="username"
                v-model="form.username"
                tabindex="1"
              ></el-input>
            </el-form-item>
            <el-form-item label="电话号码:" prop="phone">
              <el-input
                ref="phone"
                v-model="form.phone"
                tabindex="2"
              ></el-input>
            </el-form-item>
            <el-form-item label="邮箱:">
              <el-input ref="email" v-model="form.email"></el-input>
            </el-form-item>
            <el-form-item label="密码:" prop="password">
              <el-input ref="password" v-model="form.password"></el-input>
            </el-form-item>
            <el-form-item label="确认密码:" prop="password_confirm">
              <el-input ref="password_confirm" v-model="form.password_confirm"></el-input>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="onSubmit">注册</el-button>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>
import { validUsername, validPhone, validPassword, validEmail } from '@/utils/validate'

export default {
  name: 'Register',
  data() {
    const validateUsername = (rule, value, callback) => {
      if (!validUsername(value)) {
        callback(new Error('用户名不正确必须是2-16字符, 包含汉字,英文字母和数字'))
      } else {
        callback()
      }
    }
    const validatePhone = (rule, value, callback) => {
      if (!validPhone(value)) {
        callback(new Error('输入的手机号码不正确'))
      } else {
        callback()
      }
    }
    const validatePassword = (rule, value, callback) => {
      if (!validPassword(value)) {
        callback(new Error('密码必须是6-18长度 包含字母, 数字和符号'))
      } else {
        callback()
      }
    }
    const passwordConfirm = (rule, value, callback) => {
      if (!validPassword(value)) {
        callback(new Error('密码必须是6-18长度 包含字母, 数字和符号'))
      } else {
        if (value !== this.form.password) {
          callback(new Error('两次密码输入不一致'))
        } else {
          callback()
        }
      }
    }
    const validateEmail = (rule, value, callback) => {
      if (!validEmail(value)) {
        callback(new Error('邮箱格式不正确,请重新输入'))
      } else {
        callback()
      }
    }
    return {
      form: {
        username: '',
        phone: '',
        email: '',
        password: '',
        password_confirm: ''
      },
      registerRules: {
        username: [{ required: true, trigger: 'blur', validator: validateUsername }],
        phone: [{ required: true, trigger: 'blur', validator: validatePhone }],
        email: [{ required: true, trigger: 'blur', validator: validateEmail }],
        password: [{ required: true, trigger: 'blur', validator: validatePassword }],
        password_confirm: [{ required: true, trigger: 'blur', validator: passwordConfirm }]
      }
    }
  },
  methods: {
    onSubmit() {
      this.$refs.form.validate(valid => {
        if (valid) {
          if (this.form.password_confirm !== this.form.password) {
            this.$refs.password_confirm.blur()
            return false
          }
          // 提交数据到后台

        }
      })

    }
  }
}
</script>

<style scoped>

</style>
