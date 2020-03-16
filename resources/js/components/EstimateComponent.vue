<template>
    <div>
        <div>
            <small v-if="saving">Saving...</small>
            <small v-else>All changes are saved</small>
        </div>

        <div class="row">

            <div class="col-sm-12">
                <text-section v-for="(textSection, index) in textSections" :key="index" :section="textSection" @sectionRemoved="removeSection(index, 'text')"></text-section>
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
            saving: false,
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
        },

        showSavingLabel() {
            this.saving = true;

            setTimeout(() => {
                this.saving = false;
            }, 500);
        },

        removeSection(index, type) {

            if(type == 'text') {
                this.textSections.splice(index, 1);
            }

        }

    }

}
</script>