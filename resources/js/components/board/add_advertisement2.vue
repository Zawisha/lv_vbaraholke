<template id="image-input-template">
    <div class="container">
        <div class="large-12 medium-12 small-12 cell">
            <label>Добавьте изображения
                <input type="file" id="files" ref="files" accept="image/*" multiple v-on:change="handleFilesUpload()"/>
            </label>
        </div>
        <div class="large-12 medium-12 small-12 cell">
            <div class="grid-x">
                <div v-for="(file, key) in files" class="large-4 medium-4 small-6 cell file-listing">
                    {{ file.name }}
                    <img class="preview" v-bind:ref="'image'+parseInt( key )"/>
                    <button type="button" class="btn btn-warning"  v-on:click="deleteImg(key)">Удалить</button>
                </div>
            </div>
        </div>
        <br>
        <div class="large-12 medium-12 small-12 cell clear">
            <button v-on:click="addFiles()">Загрузить изображения</button>
        </div>
        <br>
        <div class="large-12 medium-12 small-12 cell">
            <button v-on:click="submitFiles()">Далее</button>
        </div>
        <button type="button" class="btn btn-primary btn-block procedure_button" v-on:click="test">test</button>
        <modal v-if="showModal" @close="close_modal" v-bind:alert_message=this.alert_message>
            <h3 slot="link">Выберите блок</h3>
        </modal>
    </div>
</template>

<style>
    input[type="file"]{
        position: absolute;
        top: -500px;
    }
    div.file-listing img{
        max-width: 90%;
    }
</style>

<script>

    export default {
        data() {
            return {
                files: [],
                alert_message:[],
                showModal:false

            }
        },
        mounted() {
            console.log(auth.user);
        },
        created() {
        },
        methods: {

            close_modal()
            {
            this.showModal=false;
            },

            deleteImg(key){

                this.files.splice(key, 1);
                this.getImagePreviews();

            },

            test(){
             console.log(this.files);

            },

            handleFilesUpload(){
                let flag = 0;
                let uploadedFiles = this.$refs.files.files;
                for( let i = 0; i < uploadedFiles.length; i++ ){

                    if ( /\.(jpe?g|png)$/i.test( uploadedFiles[i].name ) ) {

                        if(uploadedFiles[i]['size']>8388608)
                        {
                            flag = 1;
                            this.alert_message.push('Допустимый размер файла 8мб')
                        }
                        else
                        {
                            this.files.push( uploadedFiles[i] );
                        }

                    }
                    else
                    {
                        flag = 1;
                        this.alert_message.push('Не верный формат файла')
                    }

                    if (flag == 1)
                    {
                        let sortArr = this.alert_message.filter((it, index) => index === this.alert_message.indexOf(it = it.trim()));
                        this.alert_message = sortArr;
                        this.showModal=true;

                    }

                }
                this.getImagePreviews();
            },

            getImagePreviews(){
                let flag = 0;
                for( let i = 0; i < this.files.length; i++ ){

                    if ( /\.(jpe?g|png)$/i.test( this.files[i].name ) ) {

                        if(this.files[i]['size']>8388608)
                        {
                            this.files.splice(i, 1);
                            flag = 1;
                            this.alert_message.push('Допустимый размер файла 8мб')
                        }
                        else
                        {
                            let reader = new FileReader();
                            reader.addEventListener("load", function(){
                                this.$refs['image'+parseInt( i )][0].src = reader.result;
                            }.bind(this), false);
                            reader.readAsDataURL( this.files[i] );
                        }

                    }
                    else
                    {
                        this.files.splice(i, 1);
                        flag = 1;
                        this.alert_message.push('Не верный формат файла')
                    }
                }

                if (flag == 1)
                {
                    let sortArr = this.alert_message.filter((it, index) => index === this.alert_message.indexOf(it = it.trim()));
                    this.alert_message = sortArr;
                    this.showModal=true;

                }

            },

            addFiles(){
                this.$refs.files.click();
            },

            submitFiles(){

                let formData = new FormData();
                for( let i = 0; i < this.files.length; i++ ){

                    let file = this.files[i];
                    formData.append('files[' + i + ']', file);
                }


                formData.append('product_name', this.$store.state.product_name);
                formData.append('product_desc', this.$store.state.product_desc);
                formData.append('product_price', this.$store.state.product_price);
                formData.append('city', this.$store.state.city);
                formData.append('product_cond', this.$store.state.product_cond);
                formData.append('selected_region', this.$store.state.selected_region);
                formData.append('selected_category', this.$store.state.selected_category);

                axios.post( '/store_pic',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function(){
                    console.log('SUCCESS!!');
                })
                    .catch(function(){
                        console.log('FAILURE!!');
                    });
            },

        }
    }
</script>
