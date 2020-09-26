<template>
    <div>
    <div class="container">
        <div class="row ">
            <div class="col-12 adminka" v-if="is_admin_ent==1" v-on:click="goto_adminka"  >
                    Админка
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row name">
            <div class="col-12 username">
               {{ user['name'] }}
            </div>
        </div>

            <div class="row userprof ">
                <div class="col-xl-3 col-lg-3 col-md-4 userava " id=''>
                        <label for="customFile" class="my_pointer">
                            <img :src="image_src" id="imgavatar">
                        </label>
                        <input type="file"
                               class="custom-file-input"
                               id="customFile"
                               @change="onAttachmentChange"
                        >
                </div>
            </div>

        <div class="col-xl-12 col-lg-9 col-md-8 usermenu">
            <div class="col-xl-3 col-lg-5 col-md-8 chpass">
                <router-link  :to="{ path: '/password_reset'}">Поменять пароль</router-link>
            </div>
            <div class="col-xl-3 col-lg-5 col-md-8 chpass">
                <router-link  :to="{ path: '/change_mobile'}">Email и телефон</router-link>
            </div>
            <div class="col-xl-3 col-lg-5 col-md-8 chpass" v-if="user['vk_id'] ==''">
            <a href= 'https://oauth.vk.com/authorize?client_id=6721477&redirect_uri=http://localhost/authvk&response_type=code'>Привязать аккаунт ВК</a>
            </div>
            <div class="col-xl-3 col-lg-5 col-md-8 logout">
                <a v-on:click="logout">Выйти</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row ">
            <div class="col-12 myob">
                <router-link  :to="{ path: '/chat'}">Диалоги</router-link>
            </div>
            <div class="col-12 myob">
                <router-link  :to="{ path: '/user_posts'}">Мои объявления</router-link>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                image_src:'',
                user:'',
                is_admin_ent:0,
                messages:[]
            }
        },
        mounted() {
            this.check_ava();
            this.user = auth.user;
            this.is_admin();
                // this.get_user_info_channel();


            // window.Echo.channel('laravel_database_translation')
            //     .listen('TranslationEvent', (e) => {
            //         alert('YYEEEES');
            //     });

            // window.Echo.channel('laravel_database_private-translation.6')
            //     .listen('TranslationEvent', (e) => {
            //         console.log(e);
            //     });

        },
        created() {


        },
        methods: {

            goto_adminka()
            {
                Vue.router.push({name: 'MainViewAdmin'})

            },

            get_user_info_channel()
            {
                axios
                    .post('/get_user_info_channel',{
                    })
            },



            is_admin()
            {
                axios
                    .post('/is_admin',{
                    }).then(response => {
                    if(response.data.length != 0)
                    {
                        this.is_admin_ent=response.data[0].status
                    }

                })
            },

            onAttachmentChange (e) {
                this.attachment = e.target.files[0];
                this.submit();
            },

            check_ava()
            {
                axios
                    .post('/check_ava',{
                    }).then(response => {
                    if(response.data=='1')
                    {
                        this.image_src='/images/avatars/'+auth.user['id']

                    }
                    else
                    {
                        this.image_src='/images/avatars/0'
                    }
                })
            },

            submit () {

                const config = { 'content-type': 'multipart/form-data' };
                const formData = new FormData();
                // formData.append('name', this.name)
                formData.append('attachment', this.attachment);
                axios.post('/store_ava', formData, config)

                    // )
                    .then(response => {

                        if(response.data.status=='success')
                        {
                            this.image_src='/images/avatars/'+auth.user['id']+'_'+response.data.data,
                                axios
                                    .post('/del_ava',{
                                        img_path:'/images/avatars/'+auth.user['id']+'_'+response.data.data,
                                    });

                        }
                        else
                            (
                                this.mes_err=response.data.mes.image,
                                    this.mes_err=this.mes_err[0],
                                    this.showModal=true
                            )

                    })

            },

            logout()
            {
                axios
                    .post('/logout',{
                    })
                    .then(({ data }) => (
                         auth.logout(),
                         Vue.router.push({path:'/'})
                        )
                    );
            }


        }
    }
</script>
