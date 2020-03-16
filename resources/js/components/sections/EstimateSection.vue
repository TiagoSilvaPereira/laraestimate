<style scoped>
.section:hover {
    /* background-color: #fafafa; */
}
</style>

<template>
    <div class="section p-2 mb-5">
        <small v-if="section.type == 'text'" class="text-primary mb-4">Text Section {{ section.id }}</small>
        <small v-else class="text-primary mb-4">Prices Section {{ section.id }}</small>

        <VueTrix v-model="text" @input="saveText()" />
        <button class="btn btn-sm btn-outline-danger mt-2" @click="remove()"><i class="icon ion-md-trash"></i> Remove</button>
    </div>
</template>

<script>
import VueTrix from "vue-trix";

export default {
    components: {
        VueTrix
    },

    props: ['section'],

    data() {
        return {
            text: null,
        }
    },

    mounted() {
        this.initEditor();
    },

    methods: {

        initEditor() {
            this.text = this.section.text;
        },

        saveText: _.debounce(function() {
            this.$parent.showSavingLabel();

            if(!this.section.id) {
                this.save();
                return;
            }

            this.update();

        }, 300),

        save() {
            let url = '/estimates/:estimate/sections';
            url = url.replace(':estimate', this.$parent.estimate);

            axios.post(url, {
                text: this.text,
                type: this.section.type,
            }).then(({data}) => {
                this.section.id = data.id;
            });
        },

        update() {
            let url = '/estimates/:estimate/sections/:section';
            url = url.replace(':estimate', this.$parent.estimate);
            url = url.replace(':section', this.section.id);

            axios.put(url, {
                text: this.text
            });
        },

        remove() {

            if(!this.section.id) {
                this.$emit('sectionRemoved');
                return;
            }

            let url = '/estimates/:estimate/sections/:section';
            url = url.replace(':estimate', this.$parent.estimate);
            url = url.replace(':section', this.section.id);

            bootbox.confirm('Are you sure?', confirmed => {
                if(confirmed) {
                    axios.delete(url);
                    this.$emit('sectionRemoved');
                }
            });
        }
    }
}
</script>