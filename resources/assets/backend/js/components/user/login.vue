<template>
    <div class="login-form" v-loading="loading">
        <el-row>
            <el-col :span="24">
                <el-form label-position="right" label-width="80px" :model="loginForm" ref="loginForm" :rules="loginRules">
                    <el-form-item label="用户名：" prop="email">
                        <el-input v-model="loginForm.email" placeholder="请输入用户名"></el-input>
                    </el-form-item>
                    <el-form-item label="密码：" prop="pass">
                        <el-input v-model="loginForm.pass" placeholder="请输入密码"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitLogin('loginForm')">登陆</el-button>
                        <el-button @click="resetLogin('loginForm')">取消</el-button>>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loading: false,
                loginForm: {
                    email: '',
                    pass: ''
                },
                loginRules: {
                    email: [
                        {required: true, message: '请输入用户名', trigger: 'blur'},
                        {type: 'email', message: '用户名格式不正确', trigger: 'blur'}
                    ],
                    pass: [
                        {required: true, message: '请输入密码', trigger: 'blur'},
                        {min:6, max: 10, message: '密码长度应该在6-10个之间', trigger: 'blur'}
                    ]
                }
            };
        },
        methods: {
            submitLogin(loginForm) {
                let _this = this;
                let _duration = 2 * 1000;
                _this.$refs[loginForm].validate((valid) => {
                    if(valid) {
                        _this.loading = true;
                        window.axios.post('/auth/login', _this.loginForm).then(function (response) {
                            let data = response.date;
                        }).catch(function (error) {

                        });
                    }else {

                    }
                });
            },
            resetLogin(loginForm) {
                this.$refs[loginForm].resetFields();
            }
        }
    }
</script>

<style type="text/css">
    body {
        background: #324057;
        color: #FFF;
    }
    .login-form {
        width: 400px;
        padding: 50px;
        margin: 10% auto 0 auto;
        background-color: #ffffff;
        border-radius: 5px;
    }
</style>
