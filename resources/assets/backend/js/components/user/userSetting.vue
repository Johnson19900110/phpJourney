<template>
    <div class="resetForm" style="width: 400px">
        <el-form :model="resetForm" :rules="resetRules" ref="resetForm" label-width="100px" class="demo-ruleForm" :v-loading="resetLoading">
            <el-form-item label="用户名">
                <el-input type="text" v-model="user.name" disabled="disabled"></el-input>
            </el-form-item>
            <el-form-item label="密码" prop="pass">
                <el-input type="password" v-model="resetForm.pass" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="确认密码" prop="pass_confirmation">
                <el-input type="password" v-model="resetForm.pass_confirmation" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="submitForm('resetForm')">确定</el-button>
                <el-button @click="closeForm('resetForm')">重置</el-button>
            </el-form-item>
        </el-form>
    </div>

</template>
<style type="text/css">

</style>
<script type="text/ecmascript-6">
    export default {
        data() {
            return {
                resetLoading: false,
                user: JSON.parse(sessionStorage.getItem('php_journey')),
                resetForm: {
                    pass: '',
                    pass_confirmation: ''
                },
                resetRules: {
                    pass: [
                        {required: true, type: 'string', message: '请填写密码', trigger: 'blur'},
                        {min: 8, message: '密码至少8位', trigger: 'blur'}
                    ],
                    pass_confirmation: [
                        {required: true, type: 'string', message: '请确认密码', trigger: 'blur'},
                        {min: 8, message: '确认密码至少8位', trigger: 'blur'}
                    ]
                }
            }
        },
        methods: {
            submitForm(resetForm) {
                let _this = this;
                let _duration = 1000;
                _this.resetLoading = true;
                _this.$refs[resetForm].validate((valid) => {
                   if(valid) {
                       window.axios.put('/users/1', _this.resetForm).then(function (response) {
                           let data = response.data;
                           if(data.status === 0) {
                               _this.$message({
                                   'message': data.message,
                                   'type': 'success',
                                   'duration': _duration
                               });
                               setTimeout(function () {
                                   sessionStorage.removeItem('php_journey');
                                   _this.$router.push({path: '/login'});
                               }, _duration);
                           }else {
                               _this.$message.error(data.message);
                           }
                       }).catch(function (error) {
                           console.log(error);
                       })
                   }
                });
            },
            closeForm(resetForm) {
                this.$refs[resetForm].resetFields();
            }
        }
    }
</script>