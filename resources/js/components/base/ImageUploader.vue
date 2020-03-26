<style scoped>
.cover {
    object-fit: cover;
    width: 150px;
    height: 150px;
}

.image-viewer {
    width: 150px;
    height: 150px;
    cursor: pointer;
    border: 1px solid #ddd;
}

.image-viewer .button-container  {
    visibility: hidden;
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 150px;
    height: 150px;
    background-color: rgba(0, 0, 0, 0.4)
}

.image-viewer:hover .button-container {
    visibility: visible;
}

</style>

<template>
    <div class="mt-2">
        <div class="mb-2">
            <div class="image-viewer">
                <div class="button-container" v-if="image">
                    <button type="button" class="btn btn-danger" @click.prevent="remove">Remove</button>
                </div>
                <img :src="image" class="cover mr-1" v-if="image">
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <image-selector name="image" class="mr-1" @loaded="onLoad"></image-selector>
        </form>
    </div>
</template>

<script>
    import ImageSelector from './ImageSelector.vue';

    export default {

        props: ['url', 'imagePath'],

        components: { ImageSelector },

        data() {
            return {
                image: this.imagePath || null
            };
        },

        methods: {
            
            onLoad(image) {
                this.image = image.src;
                this.persist(image.file);
            },

            persist(image) {
                let data = new FormData();

                data.append('image', image);
                
                axios.post(this.url, data).then(() => {
                    toast.success('Image uploaded successfully');
                }).catch(error => {
                    if(error.response) {
                        let data = error.response.data;
                        let errors = data.errors;

                        if(errors) {
                            for(let key in errors) {
                                toast.error(errors[key][0]);
                            }
                        } else {
                            toast.error('Something went wrong');
                        }
                    }
                });
            },

            remove() {
                if(!this.image) return;

                bootbox.confirm(translate.get('app.dialogs.are_you_sure'), confirmed => {
                    if(confirmed) {
                        axios.delete(this.url).then(() => {
                            toast.success('Image removed successfully');
                            this.image = null;
                        });
                    }
                });
            }
        }
    }
</script>