<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <text-section v-for="(textSection, index) in textSections" :key="index" :section="textSection"></text-section>
            </div>

            <div class="col-sm-12">
                <button class="btn btn-primary" @click="addTextSection()">Add Text Section</button>
                <button class="btn btn-success">Add Prices Section</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['estimate'],

    data() {
        return {
            textSections: [],
            priceSections: []
        }
    },

    mounted() {
        this.getTextSections();
    },

    methods: {

        getTextSections() {
            let url = '/estimates/:estimate/sections';
            url = url.replace(':estimate', this.estimate);

            axios.get(url).then(({data}) => {
                this.textSections = data;
            });
        },

        addTextSection() {
            this.textSections.push({
                'text': ''
            });
        }

    }

}
</script>