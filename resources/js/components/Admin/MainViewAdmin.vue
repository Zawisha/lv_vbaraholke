<template>
    <div class="container">
        <div class="row ">
            <div class="col-xl-12 col-lg-9 col-md-8 usermenu">
                <div class="col-xl-3 col-lg-5 col-md-8 chpass workwithposts">
                    <router-link  :to="{ name: 'UsersAdmin' }">
                        Работа с пользователями
                    </router-link>
                </div>
                <div class="col-xl-3 col-lg-5 col-md-8 logout workwithuser">
                    <router-link  :to="{ name: 'PostsAdmin' }">
                        Работа с постами
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {


            }
        },
        mounted() {
            // this.test_login();
        },
        created() {
        },
        methods: {



        }
    }
</script>
