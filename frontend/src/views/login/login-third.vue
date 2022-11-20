<template>
  <div id="login-qrcode-container"></div>
</template>

<script>
/* eslint-disable */
export default {
  name: 'Login',
  data() {
    return {}
  },
  created() {
    const _this = this
    // _this.loadDingTalkLoginJs('https://g.alicdn.com/dingding/h5-dingtalk-login/0.21.0/ddlogin.js')
    _this.loadDingTalkLoginJs('http://wwcdn.weixin.qq.com/node/wework/wwopen/js/wwLogin-1.2.4.js')
    _this.$nextTick(function() {
      // let timer = setInterval(function () {
      //   if (window.DTFrameLogin) {
      //     clearInterval(timer)
      //     _this.showDingTalkLoginQrcode()
      //   }
      // }, 20)
      const wwTimer = setInterval(function () {
        if (window.WwLogin) {
          clearInterval(wwTimer)
          _this.showWechatLoginQrcode()
        }
      })
    })

  },
  methods: {
    loadDingTalkLoginJs(src) {
      const s = document.createElement('script')
      s.type = 'text/javascript'
      s.src = src
      document.head.appendChild(s)
    },
    showWechatLoginQrcode() {
      const wwLogin = new WwLogin({
        "id": "login-qrcode-container",
        "appid": 'ww0c6f6831b3d47deb',
        "agentid": "1000004",
        "redirect_uri": 'http://admin.jzcassociates.com/site/callback',
        "state": "Test",
        "href": "",
        "lang": "zh"
      })
    },
    showDingTalkLoginQrcode() {
      window.DTFrameLogin(
        {
          id: 'login-qrcode-container',
          width: 400,
          height: 800
        },
        {
          redirect_uri: encodeURIComponent('http://admin.jzcassociates.com/site/callback'),
          client_id: 'ding1lewgwfsxs8ao5oi',
          corpId: '2054465978',
          scope: 'openid',
          response_type: 'code',
          state: 'test'
        },
        (loginResult) => {
          // const {redirectUrl, authCode, state} = loginResult
          // 这里可以直接进行重定向
          // window.location.href = redirectUrl
          // 也可以在不跳转页面的情况下，使用code进行授权
        },
        (errorMsg) => {
          // 这里一般需要展示登录失败的具体原因
          alert(`Login Error: ${errorMsg}`)
        }
      )
    }
  }
}
</script>

<style scoped>
#login-qrcode-container {
  margin-top: 200px;
  width: 100%;
  text-align: center;
  background: #fff;
  padding: 10px;
}
</style>
