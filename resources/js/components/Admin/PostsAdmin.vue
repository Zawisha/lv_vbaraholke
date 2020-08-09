<template>
    <div>
        <div class="container" v-if="zero_posts_flag==0">
            <div class="row zeroposts ">
                <div class="col-12 d-flex justify-content-center" >
                    Работа с постами пользователя
                </div>
            </div>
        </div>

        <div class="container" v-if="zero_posts_flag==1">
            <div class="row zeroposts ">
                <div class="col-12 d-flex justify-content-center" >
                    Нет объявлений
                </div>
            </div>
        </div>

        <div class="container">
            <transition-group name="fade" tag="div" >
                <div :key="post.id" class="row posts " v-for="(post,number) in posts">
                    <div class="col-lg-3 col-md-4 d-md-flex">
                        <div class="carousel_posts col-12  d-flex justify-content-center col-md-12 col-lg-12" v-if="posts_img_arr[number].img_in_arr.length !=0">
                            <carousel :data="posts_img_arr[number].img_in_arr"></carousel>
                        </div>
                        <div v-else>
                            <img :src="'/images/nophoto.png'" class="img-fluid">
                        </div>
                    </div>

                    <div class="col-lg-4  justify-content-center col-md-6 d-md-block headerref">
                        <div class="col-12 d-flex justify-content-center  justify-content-lg-start">

                            <router-link  :to="{ path: '/posts/'+post.id}">{{ post.title }}</router-link>
                        </div>
                        <div class="col-12  descr ">
                            <div class="col-12 d-flex justify-content-center justify-content-md-start time__add">
                                Добавлено: {{post.created_at}}
                            </div>
                            <div class="col-12 d-flex justify-content-center justify-content-md-start region">
                                {{ post.region }}
                            </div>
                            <div class="col-12 d-flex justify-content-center justify-content-md-start city">
                                Город:{{ post.city }}
                            </div>
                            <div class="col-12 d-flex justify-content-center justify-content-md-start condition">
                                <div v-if="post.condition==1" >Состояние:Новый</div>
                                <div v-else >Состояние:Б/у</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-center col-md-3 align-self-md-end  price ">
                        {{ post.price }} руб.
                    </div>
                    <div class="col-12 ">
                        <select v-model=post.moderation>
                            <option disabled>Выберите статус</option>
                            <option v-bind:value='0'>Отклонить</option>
                            <option v-bind:value='1'>Принять</option>
                        </select>
                    </div>
                </div>
            </transition-group>
        </div>
        <button type="button" class="col-12 btn btn-success" v-on:click="save_moderation()">Сохранить</button>
        <div class="container">
            <div class="row paginator d-flex justify-content-center ">
                <div v-on:click="render_start_array()"><v-pagination v-if="total_pages!=0"  v-model="currentPage"  :page-count=total_pages :classes="bootstrapPaginationClasses"></v-pagination></div>
            </div>
        </div>

    </div>
</template>

<style>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
        opacity: 0;
    }
</style>

<script>
    export default {
        data() {
            return {
                posts:[],
                currentPage: 1,
                total_pages:0,
                bootstrapPaginationClasses: {
                    ul: 'pagination',
                    li: 'page-item',
                    liActive: 'active',
                    liDisable: 'disabled',
                    button: 'page-link'
                },

                posts_img_arr:[],
                zero_posts_flag:0,
                category_arr:[],
                regions_arr:[],
                selected_reg:0,
                selected_cat:0,
                sort_by:'1'

            }
        },
        mounted() {
            this.render_start_array(this.posts)
        },
        created() {
        },
        methods: {

            test()
            {
                console.log(this.posts_img_arr);
            },

            save_moderation()
            {
                axios
                    .post('/save_posts_admin',{
                        posts:this.posts

                    }).then(({ data }) =>
                    {
                        if (data.status == 'success')
                        {
                            this.render_start_array();
                        }
                    })
            },

            render_start_array()
            {
                this.posts=[];
                axios
                    .post('/render_posts_list_admin',{
                        offset:this.currentPage,

                    }).then(({ data }) =>
                    {
                        if(data.length != 0)
                        {
                            this.zero_posts_flag=0;
                            let inp = [];
                            let images = [];
                            data.forEach(function(entry) {
                                let img_arr =[];
                                for (let i = 0; i < entry.img.length ; i++) {
                                    img_arr.push('<img :src="'+'\'/images/posts/' +entry.img[i].path +'\'" alt=""/>' )
                                }
                                inp.push({
                                    id:entry.id,
                                    title:entry.title,
                                    description:entry.description,
                                    price:entry.price,
                                    region:entry.reg.region,
                                    city:entry.city,
                                    category:entry.category,
                                    condition:entry.condition,
                                    created_at:entry.created_at,
                                    img_in_arr:img_arr,
                                    moderation:1
                                });
                                images.push({
                                    id:entry.id,
                                    img_in_arr:img_arr
                                });

                            })
                            this.posts=inp;
                            this.posts_img_arr = images;
                        }
                        else
                        {
                            this.zero_posts_flag=1;
                        }

                    }

                );

                this.getTotalCountPosts();

            },

            getTotalCountPosts()
            {
                axios
                    .post('/count_posts_admin',{
                    }).then(({ data }) => (
                    this.total_pages=Math.ceil((data.length)/10)
                ));
            }

        }
    }
</script>
