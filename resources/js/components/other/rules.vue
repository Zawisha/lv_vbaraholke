<template>
    <div>
        <div class="video-wrapper"></div>
        <video autoplay></video>
    </div>
</template>

<script>
    export default {
        data() {
            return {


            }
        },
        mounted() {
            document.addEventListener("DOMcontentLoaded", async() =>{
                const video = document.querySelector("video");
                if(!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia)
                {
                    return;
                }
                video.srcObject = await navigator.mediaDevices.getUserMedia({video: true});
                await video.play();
            })
        },
        created() {
        },
        methods: {



        }
    }
</script>
